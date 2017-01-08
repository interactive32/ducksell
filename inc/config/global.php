<?php

return [

    'tmp' => storage_path().'/tmp/',
    'file_path' => storage_path().'/files/',
    'file_tmp' => storage_path().'/files/TMP/',

    'types' => [
        '1' => 'information',
        '2' => 'important',
        '3' => 'warning',

    ],

    'email-drivers' => [
        'smtp' => 'smtp',
        'mail' => 'php mail()',
    ],

    'email-encryption' => [
        'ssl' => 'ssl',
        'tls' => 'tls',
        '' => 'none',
    ],

    'per_page' => [
        5,
        10,
        20,
        25,
        50,
        100,
    ],

];

