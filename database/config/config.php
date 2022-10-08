<?php

function databaseConfig(): array
{
    return [
        'sqlite' =>
            [
                'DATABASE_URL' => "sqlite:" . __DIR__ . '/../dump/'. $_ENV['DATABASE_FILE']
            ],

        'mysql' => [
            'DATABASE_URL' => $_ENV['DATABASE_URL']
        ]
    ];
}