<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissionSuccessController extends Controller
{
    public function complaint(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('submission_type') !== 'complaint') {
            return redirect()->route('home');
        }

        return view('submission-success', [
            'title' => 'Pengaduan berhasil dikirim',
            'message' => 'Terima kasih. Pengaduan Anda telah kami terima.',
            'ticketNumber' => $request->session()->get('ticket_number'),
        ]);
    }

    public function staffSurvey(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('submission_type') !== 'staff_survey') {
            return redirect()->route('home');
        }

        return view('submission-success', [
            'title' => 'Survei berhasil dikirim',
            'message' => 'Terima kasih atas penilaian Anda.',
            'ticketNumber' => null,
        ]);
    }
}
