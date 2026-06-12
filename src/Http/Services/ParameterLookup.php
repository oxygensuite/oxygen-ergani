<?php

namespace OxygenSuite\OxygenErgani\Http\Services;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Responses\ParameterCollection;
use OxygenSuite\OxygenErgani\Responses\ParameterResponse;

class ParameterLookup extends Service
{
    /**
     * Available parameter types for lookup
     */
    public const SEPE = 'Sepe';
    public const OAED = 'Oaed';
    public const STAKOD = 'Stakod';
    public const KALLIKRATIS_COMMUNITY = 'KallikratisKoinothta';
    public const KALLIKRATIS_MUNICIPALITY = 'KallikratisDhmos';
    public const KALLIKRATIS_REGIONAL_UNIT = 'KallikratisPerifereiaEnothta';
    public const KALLIKRATIS_REGION = 'KallikratisPerifereia';
    public const NATIONALITY = 'Nationality';
    public const ID_TYPE = 'TyposTaytotitas';
    public const RESIDENCE_PERMIT = 'ResidencePermit';
    public const DOY = 'Doy';
    public const EDUCATION_LEVEL = 'EpipedoMorfosis';
    public const SUBJECT_AREA = 'SubjectArea';
    public const SUBJECT_GROUP = 'SubjectGroup';
    public const EDUCATION_AGENCY = 'EducationAgency';
    public const LANGUAGE = 'Language';
    public const SPECIALTY = 'Step92';
    public const OAED_PROGRAM = 'ProgramaOaed';
    public const TRAFFIC_SPECIALTIES = 'TraficEmploymentSpecialties';
    public const OVERTIME_REASON = 'OvertimeAitiologia';
    public const TERMINATION_REASON = 'LogosApolyshs';
    public const BANK = 'Bank';
    public const RAPID_EXCEPTION_REASON = 'RapidExceptionReason';
    public const SINGLE_PARENT_CASE = 'OneParentCase';
    public const WORK_CARD_DELAY_REASON = 'WorkCardDelayReason';
    public const WORK_TIME_TYPE = 'WorkTimeType';
    public const SIXTH_DAY_KAD = 'SixthDayKAD';
    public const CHANGE_TYPE = 'TypeMetabolon';
    public const PRIMARY_INSURANCE = 'ForeisKyriasAsfalisis';
    public const SUPPLEMENTARY_INSURANCE = 'ForeisEpikourikisAsfalisis';

    /**
     * Retrieves parameter values for the specified parameter type.
     *
     * @param string $parameter The parameter type to look up (use class constants)
     *
     * @throws ErganiException
     */
    public function handle(string $parameter): ParameterCollection
    {
        $data = $this->execute(['Parameter' => $parameter])->json();
        $parameters = $data[$this->serviceCode()]['Param'] ?? [];

        $items = array_map(
            fn(array $param) => new ParameterResponse($param),
            $parameters,
        );

        return new ParameterCollection($items);
    }

    protected function serviceCode(): string
    {
        return 'EX_BASE_03';
    }
}
