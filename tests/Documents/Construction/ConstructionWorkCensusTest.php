<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Documents\Construction;

use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkCensus;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensus;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensusEmployee;
use Tests\TestCase;

class ConstructionWorkCensusTest extends TestCase
{
    public function test_construction_work_census_submit(): void
    {
        $census = ConstructionCensus::make()
            ->setBranchCode(0)
            ->setAmoe('1234567890')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDateFrom('01/01/2026')
            ->setDateTo('31/01/2026')
            ->setPhase('Foundation')
            ->setLaborInspectionCode('12345')
            ->setYear(2026)
            ->setMonth(1)
            ->setMunicipalityCode('12345678')
            ->setComments('test')
            ->addEmployee(
                ConstructionCensusEmployee::make()
                    ->setAfm('999999999')
                    ->setAmka('12345678901')
                    ->setAma('12345678')
                    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
                    ->setFirstName('ΙΩΑΝΝΗΣ')
                    ->setFatherName('ΓΕΩΡΓΙΟΣ')
                    ->setDaysWorked(22)
                    ->setSpecialtyCode('411090')
                    ->setWorkPermitNumber('')
                    ->setHireDate('01/01/2026')
                    ->setGrossEarnings(1760.00)
                    ->setNotes(''),
            );

        $document = new ConstructionWorkCensus('test-access-token');
        $document->getConfig()->setHandler($this->mockResponse(200, 'construction-work-census.json'));
        $response = $document->handle($census);

        $this->assertIsArray($response);
        $this->assertSame('301', $response[0]->id);
        $this->assertSame('E12A-301', $response[0]->protocol);
    }

    public function test_construction_census_model(): void
    {
        $employee = ConstructionCensusEmployee::make()
            ->setAfm('999999999')
            ->setAmka('12345678901')
            ->setAma('12345678')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setFatherName('ΓΕΩΡΓΙΟΣ')
            ->setDaysWorked(22)
            ->setSpecialtyCode('411090')
            ->setWorkPermitNumber('')
            ->setHireDate('01/01/2026')
            ->setGrossEarnings(1760.00)
            ->setNotes('Notes');

        $census = ConstructionCensus::make()
            ->setBranchCode(0)
            ->setAmoe('1234567890')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDateFrom('01/01/2026')
            ->setDateTo('31/01/2026')
            ->setPhase('Framing')
            ->setLaborInspectionCode('12345')
            ->setYear(2026)
            ->setMonth(1)
            ->setMunicipalityCode('12345678')
            ->setComments('Test')
            ->setEmployees([$employee]);

        $this->assertSame(0, $census->getBranchCode());
        $this->assertSame('2026', $census->getYear());
        $this->assertSame('1', $census->getMonth());
        $this->assertCount(1, $census->getEmployees());

        $emp = $census->getEmployee(0);
        $this->assertSame('22', $emp->getDaysWorked());
        $this->assertSame(1760.0, $emp->getGrossEarnings());
    }

    public function test_construction_census_to_sorted_array(): void
    {
        $census = ConstructionCensus::make()
            ->setBranchCode(0)
            ->setAmoe('')
            ->setRelatedProtocol('')
            ->setRelatedDate('')
            ->setDateFrom('01/01/2026')
            ->setDateTo('31/01/2026')
            ->setPhase('')
            ->setLaborInspectionCode('12345')
            ->setYear(2026)
            ->setMonth(1)
            ->setMunicipalityCode('12345678')
            ->setComments('')
            ->addEmployee(
                ConstructionCensusEmployee::make()
                    ->setAfm('999999999')
                    ->setAmka('12345678901')
                    ->setAma('12345678')
                    ->setLastName('TEST')
                    ->setFirstName('TEST')
                    ->setFatherName('TEST')
                    ->setDaysWorked(20)
                    ->setSpecialtyCode('411090')
                    ->setWorkPermitNumber('')
                    ->setHireDate('01/01/2026')
                    ->setGrossEarnings(1500.00)
                    ->setNotes(''),
            );

        $array = $census->toSortedArray();

        $this->assertArrayHasKey('f_year', $array);
        $this->assertArrayHasKey('f_month', $array);
        $this->assertArrayHasKey('Ergazomenoi', $array);

        $employee = $array['Ergazomenoi']['AmoeErgazomenosDate'][0];
        $this->assertArrayHasKey('f_days_worked', $employee);
        $this->assertSame('1.500,00', $employee['f_apodoxes']);
    }
}
