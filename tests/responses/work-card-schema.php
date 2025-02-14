<?php

return [
    'title' => 'Δηλώσεις Κάρτας',
    'json' => [
        'Cards' => [
            'Card' => [
                [
                    'f_afm_ergodoti' => 'f_afm_ergodoti',
                    'f_aa' => 'f_aa',
                    'f_comments' => 'f_comments',
                    'Details' => [
                        'CardDetails' => [
                            [
                                'f_afm' => 'f_afm',
                                'f_eponymo' => 'f_eponymo',
                                'f_onoma' => 'f_onoma',
                                'f_type' => '0',
                                'f_reference_date' => '2025-02-15',
                                'f_date' => '2025-02-15T01:02:53.24919+02:00',
                                'f_aitiologia' => 'f_aitiologia'
                            ],
                            [
                                'f_afm' => 'f_afm',
                                'f_eponymo' => 'f_eponymo',
                                'f_onoma' => 'f_onoma',
                                'f_type' => '0',
                                'f_reference_date' => '2025-02-15',
                                'f_date' => '2025-02-15T01:02:53.24919+02:00',
                                'f_aitiologia' => 'f_aitiologia'
                            ]
                        ]
                    ]
                ],
                [
                    'f_afm_ergodoti' => 'f_afm_ergodoti',
                    'f_aa' => 'f_aa',
                    'f_comments' => 'f_comments',
                    'Details' => [
                        'CardDetails' => [
                            [
                                'f_afm' => 'f_afm',
                                'f_eponymo' => 'f_eponymo',
                                'f_onoma' => 'f_onoma',
                                'f_type' => '0',
                                'f_reference_date' => '2025-02-15',
                                'f_date' => '2025-02-15T01:02:53.24919+02:00',
                                'f_aitiologia' => 'f_aitiologia'
                            ],
                            [
                                'f_afm' => 'f_afm',
                                'f_eponymo' => 'f_eponymo',
                                'f_onoma' => 'f_onoma',
                                'f_type' => '0',
                                'f_reference_date' => '2025-02-15',
                                'f_date' => '2025-02-15T01:02:53.24919+02:00',
                                'f_aitiologia' => 'f_aitiologia'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    'propertiesInfo' => [
        [
            'name' => 'f_afm_ergodoti',
            'title' => 'ΑΡΙΘΜΟΣ ΦΟΡΟΛΟΓΙΚΟΥ ΜΗΤΡΩΟΥ (Α.Φ.Μ.)',
            'restrictions' => [
                [
                    'name' => 'pattern',
                    'value' => '([0-9]){9}'
                ],
                [
                    'name' => 'length',
                    'value' => '9'
                ]
            ]
        ],
        [
            'name' => 'f_aa',
            'title' => 'Α/Α ΠΑΡΑΡΤΗΜΑΤΟΣ',
            'restrictions' => [
                [
                    'name' => 'pattern',
                    'value' => '[0-9]{1,5}'
                ]
            ]
        ],
        [
            'name' => 'f_comments',
            'title' => 'ΣΧΟΛΙΑ',
            'restrictions' => [
                [
                    'name' => 'maxLength',
                    'value' => '200'
                ]
            ]
        ],
        [
            'name' => 'f_afm',
            'title' => 'ΑΡΙΘΜΟΣ ΦΟΡΟΛΟΓΙΚΟΥ ΜΗΤΡΩΟΥ (Α.Φ.Μ.)',
            'restrictions' => [
                [
                    'name' => 'pattern',
                    'value' => '([0-9]){9}'
                ],
                [
                    'name' => 'length',
                    'value' => '9'
                ]
            ]
        ],
        [
            'name' => 'f_eponymo',
            'title' => 'ΕΠΩΝΥΜΟ',
            'restrictions' => [
                [
                    'name' => 'maxLength',
                    'value' => '50'
                ]
            ]
        ],
        [
            'name' => 'f_onoma',
            'title' => 'ΟΝΟΜΑ',
            'restrictions' => [
                [
                    'name' => 'maxLength',
                    'value' => '30'
                ]
            ]
        ],
        [
            'name' => 'f_type',
            'title' => 'Τύπος Κίνησης (0) ΕΙΣΟΔΟΣ (1) ΕΞΟΔΟΣ',
            'restrictions' => [
                [
                    'name' => 'enumeration',
                    'value' => '0'
                ],
                [
                    'name' => 'enumeration',
                    'value' => '1'
                ]
            ]
        ],
        [
            'name' => 'f_reference_date',
            'title' => 'ΗΜ/ΝΙΑ Αναφοράς',
            'restrictions' => []
        ],
        [
            'name' => 'f_date',
            'title' => 'ΗΜ/ΝΙΑ Κίνησης',
            'restrictions' => []
        ],
        [
            'name' => 'f_aitiologia',
            'title' => 'ΚΩΔΙΚΟΣ ΑΙΤΙΟΛΟΓΙΑΣ (Σε περίπτωση Εκπρόθεσμου)',
            'restrictions' => [
                [
                    'name' => 'pattern',
                    'value' => '([0-9]{1,10})|([ ]*)'
                ]
            ]
        ]
    ]
];