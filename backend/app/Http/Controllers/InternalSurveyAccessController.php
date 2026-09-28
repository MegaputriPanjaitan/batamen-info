<?php

namespace App\Http\Controllers;

use App\Models\InternalSurveyEmployee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InternalSurveyAccessController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->only('nip'), [
            'nip' => ['required', 'digits_between:8,20'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits_between' => 'NIP harus terdiri dari 8 sampai 20 angka.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'internalSurvey')->with('open_internal_survey_login', true);
        }

        $employee = InternalSurveyEmployee::query()
            ->where('nip', $request->string('nip')->toString())
            ->where('is_active', true)
            ->first();

        if (! $employee) {
            return back()
                ->withErrors(['nip' => 'NIP tidak terdaftar atau akses pegawai sedang tidak aktif.'], 'internalSurvey')
                ->with('open_internal_survey_login', true);
        }

        $request->session()->put('internal_survey_employee_id', $employee->id);
        return redirect()->away(config('services.internal_survey.url'));
    }
}
