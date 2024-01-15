<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class ServiceRequest extends Request
{
    public function rules(): array
    {
        return [
            'machine_uuid' => ['required', 'integer'],
            'user_id' => ['required', 'integer'],
            'description' => ['nullable', 'string'],
            'email' => ['required', 'string', 'max:255'],
        ];
    }

}
