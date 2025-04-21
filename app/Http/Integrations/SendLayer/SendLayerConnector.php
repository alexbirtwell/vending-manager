<?php

namespace App\Http\Integrations\SendLayer;

use App\Concerns\Integration\HasLogging;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class SendLayerConnector extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    public ?int $tries = 3;
    public ?int $retryInterval = 3000;

    /**
     * The Base URL of the API.
     */
    public function resolveBaseUrl(): string
    {
        return 'https://console.sendlayer.com/api/v1/';
    }
}
