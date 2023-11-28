<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'table' => env('LARASEARCH_TABLE', 'searchable'),
    'cache' => env('LARASEARCH_CACHE', true),
    'queue' => env('LARASEARCH_QUEUE', true)
];
