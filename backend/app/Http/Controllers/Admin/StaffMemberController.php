<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStaffMemberRequest;
use App\Http\Requests\Admin\UpdateStaffMemberRequest;
use App\Http\Requests\Admin\UpdateStaffStatusRequest;
use App\Models\StaffMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $staffMembers = StaffMember::query()
            ->withCount('surveyResponses')
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.staff.index', [
            'staffMembers' => $staffMembers,
            'publicServices' => config('public_services'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(StoreStaffMemberRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except('photo');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('staff-photos', 'public');
        }

        StaffMember::query()->create($validated);

        return back()->with('success', 'Data petugas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(StaffMember $staffMember): View
    {
        return view('admin.staff.edit', [
            'staffMember' => $staffMember,
            'publicServices' => config('public_services'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffMemberRequest $request, StaffMember $staffMember): RedirectResponse
    {
        $validated = $request->safe()->except('photo');
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            if ($staffMember->photo_path) {
                Storage::disk('public')->delete($staffMember->photo_path);
            }

            $validated['photo_path'] = $request->file('photo')->store('staff-photos', 'public');
        }

        $staffMember->update($validated);

        return redirect()->route('admin.staff.index')->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function updateStatus(UpdateStaffStatusRequest $request, StaffMember $staffMember): RedirectResponse
    {
        $staffMember->update(['is_active' => $request->boolean('is_active')]);

        $message = $staffMember->is_active
            ? 'Petugas berhasil diaktifkan dan akan tampil pada formulir survei.'
            : 'Petugas berhasil dinonaktifkan dan tidak lagi tampil pada formulir survei.';

        return back()->with('success', $message);
    }
}
