<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SurveyController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate(['staff_member_id' => ['nullable', 'integer', 'exists:staff_members,id']]);

        $surveys = SurveyResponse::query()
            ->with('staffMember:id,name,position')
            ->withAvg('ratings', 'score')
            ->when($validated['staff_member_id'] ?? null, fn ($query, $staffId) => $query->where('staff_member_id', $staffId))
            ->latest()
            ->paginate(15)
            ->withQueryString();
        $staffMembers = StaffMember::query()
            ->select(['id', 'name', 'position', 'photo_path'])
            ->withCount('surveyResponses')
            ->withAvg('ratings', 'score')
            ->orderByDesc('ratings_avg_score')
            ->orderByDesc('survey_responses_count')
            ->orderBy('name')
            ->get();

        return view('admin.surveys.index', compact('surveys', 'staffMembers'));
    }

    public function show(SurveyResponse $surveyResponse): View
    {
        $surveyResponse->load(['staffMember', 'ratings']);
        $questions = collect(config('survey.groups'))
            ->flatMap(fn (array $group) => $group['questions'])
            ->values()
            ->mapWithKeys(fn (string $question, int $index) => ['q'.($index + 1) => $question]);

        return view('admin.surveys.show', compact('surveyResponse', 'questions'));
    }

    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate(['staff_member_id' => ['nullable', 'integer', 'exists:staff_members,id']]);
        $fileName = 'laporan-survei-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($validated): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Tanggal', 'Petugas', 'Jabatan', 'Rata-rata Nilai']);

            SurveyResponse::query()
                ->with('staffMember:id,name,position')
                ->withAvg('ratings', 'score')
                ->when($validated['staff_member_id'] ?? null, fn ($query, $staffId) => $query->where('staff_member_id', $staffId))
                ->latest()
                ->chunk(200, function ($surveys) use ($output): void {
                    foreach ($surveys as $survey) {
                        fputcsv($output, [
                            $survey->created_at->format('d/m/Y H:i'),
                            $survey->staffMember->name,
                            $survey->staffMember->position,
                            number_format((float) $survey->ratings_avg_score, 2, '.', ''),
                        ]);
                    }
                });

            fclose($output);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
