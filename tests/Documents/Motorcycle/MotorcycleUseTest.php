<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Motorcycle;

use DateTime;
use OxygenSuite\OxygenErgani\Enums\VehicleOwner;
use OxygenSuite\OxygenErgani\Http\Documents\Motorcycle\MotorcycleUse;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleUseDeclaration;
use OxygenSuite\OxygenErgani\Models\Motorcycle\MotorcycleVehicle;
use Tests\TestCase;

class MotorcycleUseTest extends TestCase
{
    public function test_motorcycle_use_submit(): void
    {
        $document = new MotorcycleUse('test-access-token');
        $mock = $this->mockResponse(200, 'motorcycle-use.json');
        $document->getConfig()->setHandler($mock);
        $response = $document->handle($this->sampleDeclaration());

        $this->assertStringEndsWith('Documents/WebE13', $mock->getLastRequest()->getUri()->getPath());
        $this->assertSame('600', $response[0]->id);
        $this->assertSame('E13-600', $response[0]->protocol);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(1, $body['MsE13']['ME13']);
        $this->assertCount(1, $body['MsE13']['ME13'][0]['Mixanes']['MixanesE4']);
    }

    public function test_motorcycle_use_submit_multiple(): void
    {
        $document = new MotorcycleUse('test-access-token');
        $mock = $this->mockResponse(200, 'motorcycle-use.json');
        $document->getConfig()->setHandler($mock);
        $document->handle([$this->sampleDeclaration(), $this->sampleDeclaration()->setBranchCode(1)]);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertCount(2, $body['MsE13']['ME13']);
        $this->assertSame(1, $body['MsE13']['ME13'][1]['f_aa_pararthmatos']);
    }

    /**
     * The payload must match the official ERGANI E13 sample (testE13.json), field for field and in order.
     */
    public function test_matches_ergani_sample(): void
    {
        $expected = [
            'f_aa_pararthmatos' => '0',
            'f_ypiresia_sepe' => '24010',
            'f_ypiresia_oaed' => '101213',
            'f_comments' => '',
            'f_afm_proswpoy' => '000000000',
            'Mixanes' => [
                'MixanesE4' => [
                    [
                        'f_licence_plate' => 'ΥΡΟ4534',
                        'f_is_owner' => '1',
                        'f_afm_ergazomenou' => '888888888',
                        'f_eponimo_ergazomenou' => 'ΕΠΩΝΥΜΟ_01',
                        'f_onoma_ergazomenou' => 'ΟΝΟΜΑ_01',
                        'f_onoma_patera' => 'ΟΝΟΜΑ_02',
                        'f_date_enarxis_xrisis' => '15/09/2025',
                        'f_hour_enarxis_xrisis' => '08:00',
                        'f_date_lixis_xrisis' => '',
                        'f_paratiriseis' => '',
                    ],
                ],
            ],
        ];

        $this->assertSame($expected, $this->sampleDeclaration()->toSortedArray());
    }

    public function test_model_accessors(): void
    {
        $declaration = $this->sampleDeclaration()->setComments('Σχόλια');

        $this->assertSame('0', $declaration->getBranchCode());
        $this->assertSame('24010', $declaration->getLaborInspectionCode());
        $this->assertSame('101213', $declaration->getDypaServiceCode());
        $this->assertSame('Σχόλια', $declaration->getComments());
        $this->assertSame('000000000', $declaration->getLegalRepresentativeAfm());
        $this->assertCount(1, $declaration->getVehicles());
        $this->assertNull($declaration->getVehicle(1));

        $vehicle = $declaration->getVehicle(0);
        $this->assertSame('ΥΡΟ4534', $vehicle->getLicencePlate());
        $this->assertSame('1', $vehicle->getOwner());
        $this->assertSame('888888888', $vehicle->getAfm());
        $this->assertSame('ΕΠΩΝΥΜΟ_01', $vehicle->getLastName());
        $this->assertSame('ΟΝΟΜΑ_01', $vehicle->getFirstName());
        $this->assertSame('ΟΝΟΜΑ_02', $vehicle->getFatherName());
        $this->assertSame('15/09/2025', $vehicle->getStartDate());
        $this->assertSame('08:00', $vehicle->getStartTime());
        $this->assertSame('', $vehicle->getEndDate());
        $this->assertSame('', $vehicle->getNotes());

        $vehicle->setOwner(VehicleOwner::EMPLOYER)->setNotes('Εταιρικό όχημα');
        $this->assertSame('0', $vehicle->getOwner());
        $this->assertSame('Εταιρικό όχημα', $vehicle->getNotes());

        $declaration->setVehicles([MotorcycleVehicle::make(), MotorcycleVehicle::make()]);
        $this->assertCount(2, $declaration->getVehicles());
    }

    public function test_datetime_support(): void
    {
        $vehicle = MotorcycleVehicle::make()
            ->setStartDate(new DateTime('2025-09-15'))
            ->setEndDate(new DateTime('2025-12-31'));

        $this->assertSame('15/09/2025', $vehicle->getStartDate());
        $this->assertSame('31/12/2025', $vehicle->getEndDate());
    }

    private function sampleDeclaration(): MotorcycleUseDeclaration
    {
        return MotorcycleUseDeclaration::make()
            ->setBranchCode('0')
            ->setLaborInspectionCode('24010')
            ->setDypaServiceCode('101213')
            ->setComments('')
            ->setLegalRepresentativeAfm('000000000')
            ->addVehicle(
                MotorcycleVehicle::make()
                    ->setLicencePlate('ΥΡΟ4534')
                    ->setOwner(VehicleOwner::EMPLOYEE)
                    ->setAfm('888888888')
                    ->setLastName('ΕΠΩΝΥΜΟ_01')
                    ->setFirstName('ΟΝΟΜΑ_01')
                    ->setFatherName('ΟΝΟΜΑ_02')
                    ->setStartDate('15/09/2025')
                    ->setStartTime('08:00')
                    ->setEndDate('')
                    ->setNotes(''),
            );
    }

    public function test_with_defaults_keeps_vehicles_as_wrapper(): void
    {
        $array = MotorcycleUseDeclaration::make()->withDefaults()->toSortedArray();

        $this->assertSame(['MixanesE4' => []], $array['Mixanes']);
    }
}
