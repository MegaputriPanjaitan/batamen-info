<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComplaintRequest;
use App\Models\Complaint;
use App\Support\ComplaintTicketNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ComplaintController extends Controller
{
    public function store(StoreComplaintRequest $request, ComplaintTicketNumber $ticketNumber): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->only(['phone', 'email', 'complaint_type', 'report']);
        $evidence = $request->file('evidence');
        $ticketNumber = $ticketNumber->next($data['complaint_type']);
        $evidenceFileName = 'Bukti-Pengaduan-'.$ticketNumber.'.'.$evidence->extension();

        $complaint = Complaint::create([
            'ticket_number' => $ticketNumber,
            'phone' => $data['phone'],
            'email' => $data['email'],
            'type' => $data['complaint_type'],
            'report' => $data['report'],
            'evidence_path' => $evidence->storeAs('complaint-evidence', $evidenceFileName, 'local'),
            'evidence_original_name' => $evidence->getClientOriginalName(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Pengaduan berhasil dikirim.', 'ticket_number' => $complaint->ticket_number], 201);
        }

        return redirect()->route('complaints.success')->with([
            'success' => 'Pengaduan berhasil dikirim.',
            'submission_type' => 'complaint',
            'ticket_number' => $complaint->ticket_number,
        ]);
    }
}
