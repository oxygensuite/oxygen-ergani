<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Services;

use OxygenSuite\OxygenErgani\Http\Services\ServicesList;
use Tests\TestCase;

class ServicesListTest extends TestCase
{
    public function test_services_list(): void
    {
        $list = new ServicesList('test-access-token');
        $list->getConfig()->setHandler($this->mockResponse(200, 'services-list.json'));
        $response = $list->handle();

        $this->assertIsArray($response);
        $this->assertCount(6, $response);

        $row0 = $response[0];
        $this->assertSame('EX_BASE_01', $row0['name']);
        $this->assertSame('ΣΤΟΙΧΕΙΑ ΕΡΓΟΔΟΤΗ', $row0['description']);
        $this->assertSame('IsInCardSector (Όχι:0,Ναι:1): Ο εργοδότης είναι σε Κλάδο Ένταξης στην Κάρτα Εργασίας (Στο περιβάλλον δοκιμών οι εργοδότες δεν είναι ενταγμένοι σε Κλάδους Ένταξης).', $row0['instructions']);
        $this->assertIsArray($row0['parameters']);
        $this->assertEmpty($row0['parameters']);

        $row1 = $response[1];
        $this->assertSame('EX_BASE_02', $row1['name']);
        $this->assertSame('ΣΤΟΙΧΕΙΑ ΠΑΡΑΡΤΗΜΑΤΩΝ', $row1['description']);
        $this->assertNull($row1['instructions']);
        $this->assertIsArray($row1['parameters']);
        $this->assertEmpty($row1['parameters']);

        $row2 = $response[2];
        $this->assertSame('EX_BASE_03', $row2['name']);
        $this->assertSame('ΣΤΟΙΧΕΙΑ ΠΑΡΑΜΕΤΡΙΚΩΝ', $row2['description']);
        $this->assertSame('Parameter:Λίστα Τιμών Sepe: Υπηρεσίες ΣΕΠΕ, Oaed: Υπηρεσίες ΟΑΕΔ, Stakod: Κ.Α.Δ., KallikratisKoinothta: Κοινότητες Καλλικράτη, KallikratisDhmos: Δήμοι Καλλικράτη, KallikratisPerifereiaEnothta: Περιφερειακές Ενότητες Καλλικράτη, KallikratisPerifereia: Περιφέρειες, Nationality: Εθνικότητες, TyposTaytotitas: Τύποι Ταυτοτήτων, ResidencePermit: Άδειες Παραμονής, Doy: ΔΟΥ, EpipedoMorfosis: Επίπεδα Μόρφωσης, SubjectArea: Θεματικά Πεδία, SubjectGroup: Θεματικές Ενότητες, EducationAgency: Φορείς Κατάρτισης, Language: Ξένες Γλώσσες, Step92: Ειδικότητες, ProgramaOaed: Προγράμματα ΟΑΕΔ, TraficEmploymentSpecialties: Ειδικότητες Προσωπικού ΚΤΕΛ, OvertimeAitiologia: Αιτιολογίες Υπερωριών, LogosApolyshs: Αιτιολογίες Απόλυσης, Bank: Τράπεζες, RapidExceptionReason: Λόγοι εξαίρεσης από την υποχρέωση υποβολής εβδομαδιαίων διαγνωστικών ελέγχων, OneParentCase: Περιπτώσεις μονού γονέα, WorkCardDelayReason: Λόγοι εκπρόθεσμης δήλωσης έναρξης/λήξης εργασίας, WorkTimeType: Τύποι Αναλυτικών Οργάνωσης Χρόνου Εργασίας, SixthDayKAD: ΚΑΔ 6ης Μέρας, TypeMetabolon: Τύποι Μεταβολών, ForeisKyriasAsfalisis: Φορείς Κύριας Ασφάλισης, ForeisEpikourikisAsfalisis: Φορείς Επικουρικής Ασφάλισης', $row2['instructions']);
        $this->assertIsArray($row2['parameters']);
        $this->assertSame('Parameter', $row2['parameters'][0]['name']);
        $this->assertNull($row2['parameters'][0]['description']);
        $this->assertTrue($row2['parameters'][0]['isRequired']);
        $this->assertSame('Text', $row2['parameters'][0]['type']);
        $this->assertSame(0, $row2['parameters'][0]['maxLength']);

        $row3 = $response[3];
        $this->assertSame('EX_BASE_04', $row3['name']);
        $this->assertSame('ΣΤΟΙΧΕΙΑ ΜΗΝΙΑΙΑΣ ΚΑΤΑΣΤΑΣΗΣ', $row3['description']);
        $this->assertNull($row3['instructions']);
        $this->assertIsArray($row3['parameters']);
        $this->assertCount(2, $row3['parameters']);
        $this->assertSame('ReportYear', $row3['parameters'][0]['name']);
        $this->assertSame('ΕΤΟΣ', $row3['parameters'][0]['description']);
        $this->assertTrue($row3['parameters'][0]['isRequired']);
        $this->assertSame('Decimal', $row3['parameters'][0]['type']);
        $this->assertSame('ReportMonth', $row3['parameters'][1]['name']);
        $this->assertSame('ΜΗΝΑΣ', $row3['parameters'][1]['description']);
        $this->assertTrue($row3['parameters'][1]['isRequired']);
        $this->assertSame('Decimal', $row3['parameters'][1]['type']);

        $row4 = $response[4];
        $this->assertSame('EX_BASE_05', $row4['name']);
        $this->assertSame('ΣΤΟΙΧΕΙΑ ΤΡΕΧΟΥΣΑΣ ΚΑΤΑΣΤΑΣΗΣ ΔΥΝΑΜΙΚΟΥ', $row4['description']);
        $this->assertNull($row4['instructions']);
        $this->assertIsArray($row4['parameters']);
        $this->assertCount(1, $row4['parameters']);
        $this->assertSame('afm', $row4['parameters'][0]['name']);
        $this->assertFalse($row4['parameters'][0]['isRequired']);
        $this->assertSame('Text', $row4['parameters'][0]['type']);

        $row5 = $response[5];
        $this->assertSame('EX_BASE_06', $row5['name']);
        $this->assertSame('ΣΤΟΙΧΕΙΑ ΚΑΤΑΣΤΑΣΗΣ ΑΠΟΔΟΧΗΣ ΟΥΣΙΩΔΩΝ ΟΡΩΝ', $row5['description']);
        $this->assertNotNull($row5['instructions']);
        $this->assertIsArray($row5['parameters']);
        $this->assertCount(3, $row5['parameters']);
        $this->assertSame('afm', $row5['parameters'][0]['name']);
        $this->assertTrue($row5['parameters'][0]['isRequired']);
        $this->assertSame('Text', $row5['parameters'][0]['type']);
        $this->assertSame('protocol', $row5['parameters'][1]['name']);
        $this->assertTrue($row5['parameters'][1]['isRequired']);
        $this->assertSame('Text', $row5['parameters'][1]['type']);
        $this->assertSame('date', $row5['parameters'][2]['name']);
        $this->assertTrue($row5['parameters'][2]['isRequired']);
        $this->assertSame('Date', $row5['parameters'][2]['type']);
    }
}
