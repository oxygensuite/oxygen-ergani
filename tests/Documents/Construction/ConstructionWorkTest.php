<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Construction;

use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkDeclaration;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionEmployee;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionWork;
use Tests\TestCase;

class ConstructionWorkTest extends TestCase
{
    public function test_construction_work_submit(): void
    {
        $work = ConstructionWork::make()
            ->setBranchCode(0)
            ->setAmoe('1234567890')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDateFrom('01/02/2026')
            ->setDateTo('28/02/2026')
            ->setPhase('Foundation')
            ->setLaborInspectionCode('12345')
            ->setMunicipalityCode('12345678')
            ->setComments('test')
            ->addEmployee(
                ConstructionEmployee::make()
                    ->setAfm('999999999')
                    ->setAmka('12345678901')
                    ->setAma('12345678')
                    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
                    ->setFirstName('ΙΩΑΝΝΗΣ')
                    ->setFatherName('ΓΕΩΡΓΙΟΣ')
                    ->setDate('19/02/2026')
                    ->setTimeFrom('08:00')
                    ->setTimeTo('16:00')
                    ->setCancellation(false)
                    ->setSpecialtyCode('411090')
                    ->setWorkPermitNumber('')
                    ->setHireDate('01/01/2026')
                    ->setGrossDailyWage(80.00)
                    ->setNotes(''),
            );

        $document = new ConstructionWorkDeclaration('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'construction-work.json'));
        $response = $document->handle($work);

        $this->assertIsArray($response);
        $this->assertSame('300', $response[0]->id);
        $this->assertSame('E12-300', $response[0]->protocol);
    }

    public function test_construction_work_model(): void
    {
        $employee = ConstructionEmployee::make()
            ->setAfm('999999999')
            ->setAmka('12345678901')
            ->setAma('12345678')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setFatherName('ΓΕΩΡΓΙΟΣ')
            ->setDate('19/02/2026')
            ->setTimeFrom('08:00')
            ->setTimeTo('16:00')
            ->setCancellation(false)
            ->setSpecialtyCode('411090')
            ->setWorkPermitNumber('A123')
            ->setHireDate('01/01/2026')
            ->setGrossDailyWage(80.50)
            ->setNotes('Test');

        $work = ConstructionWork::make()
            ->setBranchCode(0)
            ->setAmoe('1234567890')
            ->setRelatedProtocol('ΠΡΩ123')
            ->setRelatedDate('15/02/2026')
            ->setDateFrom('01/02/2026')
            ->setDateTo('28/02/2026')
            ->setPhase('Foundation')
            ->setLaborInspectionCode('12345')
            ->setMunicipalityCode('12345678')
            ->setComments('Comments')
            ->setEmployees([$employee]);

        $this->assertSame(0, $work->getBranchCode());
        $this->assertSame('1234567890', $work->getAmoe());
        $this->assertSame('ΠΡΩ123', $work->getRelatedProtocol());
        $this->assertSame('15/02/2026', $work->getRelatedDate());
        $this->assertSame('01/02/2026', $work->getDateFrom());
        $this->assertSame('28/02/2026', $work->getDateTo());
        $this->assertSame('Foundation', $work->getPhase());
        $this->assertSame('12345', $work->getLaborInspectionCode());
        $this->assertSame('12345678', $work->getMunicipalityCode());
        $this->assertSame('Comments', $work->getComments());
        $this->assertCount(1, $work->getEmployees());

        $emp = $work->getEmployee(0);
        $this->assertSame('999999999', $emp->getAfm());
        $this->assertSame('12345678901', $emp->getAmka());
        $this->assertSame('12345678', $emp->getAma());
        $this->assertSame('ΠΑΠΑΔΟΠΟΥΛΟΣ', $emp->getLastName());
        $this->assertSame('ΙΩΑΝΝΗΣ', $emp->getFirstName());
        $this->assertSame('ΓΕΩΡΓΙΟΣ', $emp->getFatherName());
        $this->assertSame('19/02/2026', $emp->getDate());
        $this->assertSame('08:00', $emp->getTimeFrom());
        $this->assertSame('16:00', $emp->getTimeTo());
        $this->assertSame('0', $emp->getCancellation());
        $this->assertSame('411090', $emp->getSpecialtyCode());
        $this->assertSame('A123', $emp->getWorkPermitNumber());
        $this->assertSame('01/01/2026', $emp->getHireDate());
        $this->assertSame(80.5, $emp->getGrossDailyWage());
        $this->assertSame('Test', $emp->getNotes());
    }

    public function test_construction_work_to_sorted_array(): void
    {
        $work = ConstructionWork::make()
            ->setBranchCode(0)
            ->setAmoe('1234567890')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDateFrom('01/02/2026')
            ->setDateTo('28/02/2026')
            ->setPhase('')
            ->setLaborInspectionCode('12345')
            ->setMunicipalityCode('12345678')
            ->setComments('')
            ->addEmployee(
                ConstructionEmployee::make()
                    ->setAfm('999999999')
                    ->setAmka('12345678901')
                    ->setAma('12345678')
                    ->setLastName('TEST')
                    ->setFirstName('TEST')
                    ->setFatherName('TEST')
                    ->setDate('19/02/2026')
                    ->setTimeFrom('08:00')
                    ->setTimeTo('16:00')
                    ->setCancellation('0')
                    ->setSpecialtyCode('411090')
                    ->setWorkPermitNumber('')
                    ->setHireDate('01/01/2026')
                    ->setGrossDailyWage(80.00)
                    ->setNotes(''),
            );

        $array = $work->toSortedArray();

        $this->assertArrayHasKey('f_aa_pararthmatos', $array);
        $this->assertArrayHasKey('f_amoe', $array);
        $this->assertArrayHasKey('Ergazomenoi', $array);
        $this->assertArrayHasKey('AmoeErgazomenosDate', $array['Ergazomenoi']);

        $employee = $array['Ergazomenoi']['AmoeErgazomenosDate'][0];
        $this->assertArrayHasKey('f_afm', $employee);
        $this->assertArrayHasKey('f_apodoxes', $employee);
        $this->assertSame('80,00', $employee['f_apodoxes']);
    }
}
