<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientArea\Proposals\ProposalSubmitRequest;
use App\Http\Requests\ServiceRequest;
use App\Models\Proposal;
use App\Support\ActiveSite;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function show(): View
    {
        return view('service.service');
    }

    public function submit(ServiceRequest $request): View
    {
        dd($request->all());
    }
}
