<?php

use Illuminate\Support\Facades\Facade;

return [
    'default_service_days' => 1,
    'default_assignee' => 2,
    'default_country_id' => 228,
    'address_labels' => [
        'address_line_1' => 'Street Address',
        'address_line_2' => 'Address Line 2',
        'address_city' => 'Town / City',
        'address_region' => 'Region / County',
        'address_postal_code' => 'Postcode',
    ],
    'currency' =>
        [
            'code' => 'GBP',
            'symbol' => '£',
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'precision' => 2,
        ],
    'customer_notifications' => [
        'from_name' => 'BPS Vending Ltd',
        'email' => 'sales@bpsvending.co.uk',
        'telephone' => '01254 395 000',
    ]
];
