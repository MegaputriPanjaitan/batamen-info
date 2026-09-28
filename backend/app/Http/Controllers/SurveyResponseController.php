<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyResponseRequest;
use App\Models\StaffMember;
use App\Models\SurveyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SurveyResponseController extends Controller
{
    public function create(): View
    {
        $staffMembers = StaffMember::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('staff-survey', [
            'staffMembers' => $staffMembers,
            'surveyGroups' => config('survey.groups'),
        ]);
    }

    public function store(StoreSurveyResponseRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $surveyResponse = DB::transaction(function () use ($request, $validated): SurveyResponse {
            $response = SurveyResponse::create([
                'staff_member_id' => $validated['staff_member_id'],
                'respondent_ip' => $request->ip(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
            ]);
            $response->ratings()->createMany($validated['ratings']);

            return $response;
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Penilaian berhasil dikirim.', 'response_id' => $surveyResponse->id], 201);
        }

        return redirect()->route('staff-surveys.success')->with([
            'success' => 'Terima kasih. Penilaian Anda berhasil dikirim.',
            'submission_type' => 'staff_survey',
        ]);
    }
}
