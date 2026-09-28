<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'type' => ['nullable', Rule::in(config('complaints.types'))],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $complaints = Complaint::query()
            ->when($validated['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->when($validated['search'] ?? null, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.complaints.index', [
            'complaints' => $complaints,
            'complaintTypes' => config('complaints.types'),
        ]);
    }

    public function show(Complaint $complaint): View
    {
        return view('admin.complaints.show', compact('complaint'));
    }

    public function evidence(Complaint $complaint): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($complaint->evidence_path), 404);

        $extension = pathinfo($complaint->evidence_path, PATHINFO_EXTENSION)
            ?: pathinfo($complaint->evidence_original_name, PATHINFO_EXTENSION);
        $downloadName = 'Bukti-Pengaduan-'.$complaint->ticket_number.($extension ? '.'.$extension : '');

        return Storage::disk('local')->download($complaint->evidence_path, $downloadName);
    }

    public function form(Complaint $complaint): Response
    {
        $content = view('admin.complaints.form', compact('complaint'))->render();

        return response($content)
            ->header('Content-Type', 'application/msword; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="Form-Pengaduan-'.$complaint->ticket_number.'.doc"');
    }
}
