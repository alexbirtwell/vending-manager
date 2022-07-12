<?php

use App\Filament\Resources\ActivityResource;

return [

    'resource' => [
        'filament-resource' => ActivityResource::class,
        'group' => null,
        'sort'  => null,
    ],

    'paginate' => [5, 10, 25, 50],

];
