<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ServiceAccessEvent;
use App\Models\SurveyRating;
use App\Models\SurveyResponse;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $statistics = [
            'complaints' => Complaint::query()->count(),
            'surveys' => SurveyResponse::query()->count(),
            'average_rating' => round((float) SurveyRating::query()->avg('score'), 2),
            'spkp_spak' => ServiceAccessEvent::query()->where('service', ServiceAccessEvent::SPKP_SPAK)->count(),
            'internal_integrity' => ServiceAccessEvent::query()->where('service', ServiceAccessEvent::INTERNAL_INTEGRITY)->count(),
        ];

        $recentSurveys = SurveyResponse::query()
            ->with('staffMember:id,name,position')
            ->withAvg('ratings', 'score')
            ->latest()
            ->limit(3)
            ->get();

        $recentComplaints = Complaint::query()
            ->latest()
            ->limit(3)
            ->get(['id', 'ticket_number', 'type', 'created_at']);

        $months = collect(range(5, 0))->map(fn (int $monthsAgo) => CarbonImmutable::now()->startOfMonth()->subMonths($monthsAgo));
        $chartData = [
            'labels' => $months->map(fn (CarbonImmutable $month) => $month->format('m/Y'))->all(),
            'complaints' => $months->map(fn (CarbonImmutable $month) => Complaint::query()
                ->whereBetween('created_at', [$month, $month->endOfMonth()])
                ->count())->all(),
        ];

        return view('admin.dashboard', compact('statistics', 'recentSurveys', 'recentComplaints', 'chartData'));
    }
}
