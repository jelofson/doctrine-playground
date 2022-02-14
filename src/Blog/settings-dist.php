<?php

return [
    'base_uri'=>'',
    'title'=>'Fun with Doctrine',
    'db'=>[
        'driver'   => 'pdo_mysql',
        'user'     => 'juser',
        'password' => '********',
        'dbname'   => 'blog',
        'pdo_dsn'  => 'mysql:dbname=blog;dbhost=localhost;charset=utf8'
    ],
    'form'=>[
        'default_classes'=>[
            'text'=>'form-control',
            'textarea'=>'form-control',
            'select'=>'form-select',
            'date'=>'form-control',
            'checkbox'=>'form-check-input',
            'email'=>'form-control',
            'number'=>'form-control',
        ],
        'blacklist'=>[
            'id'
        ]
    ]
];