<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyResponseRequest;
use App\Models\StaffMember;
use App\Models\SurveyResponse;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SurveyResponseController extends Controller
{
    public function availability(Request $request): JsonResponse
    {
        $phone = $this->normalizePhone((string) $request->input('respondent_phone'));
        $request->merge(['respondent_phone' => $phone]);

        $validated = $request->validate([
            'respondent_phone' => ['required', 'string', 'regex:/^08[0-9]{8,13}$/'],
            'service_slug' => ['required', 'string', Rule::in(array_keys(config('public_services')))],
        ]);

        $phoneHash = hash_hmac('sha256', $validated['respondent_phone'], (string) config('app.key'));
        $ratedStaffIds = SurveyResponse::query()
            ->where('respondent_phone_hash', $phoneHash)
            ->where('service_slug', $validated['service_slug'])
            ->whereDate('survey_date', today())
            ->pluck('staff_member_id')
            ->unique()
            ->values();

        return response()->json(['rated_staff_ids' => $ratedStaffIds]);
    }

    public function create(): View
    {
        $staffMembers = StaffMember::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('staff-survey', [
            'staffMembers' => $staffMembers,
            'publicServices' => config('public_services'),
            'surveyGroups' => config('survey.groups'),
        ]);
    }

    public function store(StoreSurveyResponseRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        try {
            $surveyResponse = DB::transaction(function () use ($request, $validated): SurveyResponse {
                $response = SurveyResponse::create([
                    'staff_member_id' => $validated['staff_member_id'],
                    'respondent_phone' => $validated['respondent_phone'],
                    'respondent_phone_hash' => $validated['respondent_phone_hash'],
                    'service_slug' => $validated['service_slug'],
                    'survey_date' => today(),
                    'respondent_ip' => $request->ip(),
                    'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
                ]);
                $response->ratings()->createMany($validated['ratings']);

                return $response;
            });
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'respondent_phone' => 'Nomor HP ini sudah menilai petugas yang sama untuk layanan tersebut hari ini.',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Penilaian berhasil dikirim.', 'response_id' => $surveyResponse->id], 201);
        }

        return redirect()->route('staff-surveys.success')->with([
            'success' => 'Terima kasih. Penilaian Anda berhasil dikirim.',
            'submission_type' => 'staff_survey',
        ]);
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($phone, '62')) {
            return '0'.substr($phone, 2);
        }

        return str_starts_with($phone, '8') ? '0'.$phone : $phone;
    }
}
