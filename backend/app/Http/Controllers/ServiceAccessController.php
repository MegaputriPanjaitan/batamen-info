<?php

namespace App\Http\Controllers;

use App\Models\ServiceAccessEvent;
use Illuminate\Http\RedirectResponse;

class ServiceAccessController extends Controller
{
    public function spkpSpak(): RedirectResponse
    {
        ServiceAccessEvent::create(['service' => ServiceAccessEvent::SPKP_SPAK]);

        return redirect()->away(config('services.spkp_spak.url'));
    }

    public function internalSurvey(): RedirectResponse
    {
        ServiceAccessEvent::create(['service' => ServiceAccessEvent::INTERNAL_INTEGRITY]);

        return redirect()->route('surveys.index')->with('open_internal_survey_login', true);
    }
}
