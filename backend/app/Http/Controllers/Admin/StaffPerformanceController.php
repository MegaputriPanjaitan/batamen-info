<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffMember;
use App\Models\SurveyRating;
use App\Models\SurveyResponse;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class StaffPerformanceController extends Controller
{
    public function __invoke(StaffMember $staffMember): View
    {
        $staffMember->loadCount('surveyResponses')->loadAvg('ratings', 'score');

        $months = collect(range(5, 0))->map(fn (int $monthsAgo) => CarbonImmutable::now()->startOfMonth()->subMonths($monthsAgo));
        $monthlyPerformance = [
            'labels' => $months->map(fn (CarbonImmutable $month) => $month->format('m/Y'))->all(),
            'averages' => $months->map(fn (CarbonImmutable $month) => round((float) SurveyRating::query()
                ->whereHas('surveyResponse', fn ($query) => $query
                    ->where('staff_member_id', $staffMember->id)
                    ->whereBetween('created_at', [$month, $month->endOfMonth()]))
                ->avg('score'), 2))->all(),
            'responses' => $months->map(fn (CarbonImmutable $month) => SurveyResponse::query()
                ->where('staff_member_id', $staffMember->id)
                ->whereBetween('created_at', [$month, $month->endOfMonth()])
                ->count())->all(),
        ];

        $questionNumber = 1;
        $categoryPerformance = collect(config('survey.groups'))->map(function (array $group) use ($staffMember, &$questionNumber): array {
            $questionKeys = collect($group['questions'])->map(function () use (&$questionNumber): string {
                return 'q'.$questionNumber++;
            });

            return [
                'label' => $group['title'],
                'average' => round((float) SurveyRating::query()
                    ->whereIn('question_key', $questionKeys)
                    ->whereHas('surveyResponse', fn ($query) => $query->where('staff_member_id', $staffMember->id))
                    ->avg('score'), 2),
            ];
        });

        return view('admin.staff.performance', [
            'staffMember' => $staffMember,
            'monthlyPerformance' => $monthlyPerformance,
            'categoryPerformance' => [
                'labels' => $categoryPerformance->pluck('label')->all(),
                'averages' => $categoryPerformance->pluck('average')->all(),
            ],
        ]);
    }
}
