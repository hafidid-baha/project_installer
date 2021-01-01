<?php

/** all the packege configuration you will need */
return [
    "min_php_version" => "7.2.5",
    "required_extensions" => [
        'openssl',
        'pdo',
        'mbstring',
        'xml',
        'ctype',
        'gd',
        'tokenizer',
        'JSON',
        'bcmath',
        'exif',
        'cURL',
        'fileinfo',
    ],
    "required_permissions" => [
        'storage'           => '0777',
        'storage/app'       => '0777',
        'storage/framework' => '0777',
        'storage/logs'      => '0777',
        'bootstrap/cache'   => '0777',
    ],
];
