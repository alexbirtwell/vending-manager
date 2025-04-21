<?php

namespace App\Http\Integrations\SendLayer\Data;

class Recipient
{
    public function __construct(
        public string $email,
        public ?string $name = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name ?? $this->email,
        ];
    }
}
