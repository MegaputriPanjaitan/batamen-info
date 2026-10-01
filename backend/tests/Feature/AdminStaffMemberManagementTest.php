<?php

namespace Tests\Feature;

use App\Models\StaffMember;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminStaffMemberManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_add_staff_member_with_photo(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.staff.store'), [
            'name' => 'Petugas Baru',
            'nip' => '198205112005011001',
            'service_slugs' => ['perwalian', 'pengampuan'],
            'photo' => UploadedFile::fake()->image('petugas.jpg', 500, 500),
            'is_active' => '1',
        ])->assertRedirect()->assertSessionHas('success');

        $staff = StaffMember::query()->where('name', 'Petugas Baru')->firstOrFail();
        $this->assertTrue($staff->is_active);
        $this->assertSame('198205112005011001', $staff->nip);
        $this->assertSame(['perwalian', 'pengampuan'], $staff->service_slugs);
        Storage::disk('public')->assertExists($staff->photo_path);
    }

    public function test_admin_can_edit_and_deactivate_staff_member(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        Storage::disk('public')->put('staff-photos/old.jpg', 'old');
        $staff = StaffMember::factory()->create(['photo_path' => 'staff-photos/old.jpg']);

        $this->actingAs($admin)->put(route('admin.staff.update', $staff), [
            'name' => 'Petugas Diperbarui',
            'nip' => '198410272002122002',
            'service_slugs' => ['hak-waris'],
            'photo' => UploadedFile::fake()->image('new.jpg', 500, 500),
        ])->assertRedirect(route('admin.staff.index'))->assertSessionHas('success');

        $staff->refresh();
        $this->assertFalse($staff->is_active);
        $this->assertSame('Petugas Diperbarui', $staff->name);
        $this->assertSame(['hak-waris'], $staff->service_slugs);
        Storage::disk('public')->assertMissing('staff-photos/old.jpg');
        Storage::disk('public')->assertExists($staff->photo_path);
    }

    public function test_staff_management_page_lists_staff_and_keeps_main_navigation_unchanged(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        StaffMember::factory()->create(['name' => 'Petugas Terdaftar']);

        $this->actingAs($admin)->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee('Manajemen Data Petugas')
            ->assertSee('Petugas Terdaftar')
            ->assertSee('Edit')
            ->assertDontSee('Nonaktifkan')
            ->assertDontSee('Aktifkan')
            ->assertSee('admin-back-button', false)
            ->assertSeeInOrder(['Dashboard', 'Pengaduan', 'Survei Petugas']);
    }

    public function test_admin_can_toggle_staff_member_active_status_directly(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $staff = StaffMember::factory()->create(['is_active' => true]);

        $this->actingAs($admin)->patch(route('admin.staff.status', $staff), [
            'is_active' => false,
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertFalse($staff->refresh()->is_active);

        $this->actingAs($admin)->patch(route('admin.staff.status', $staff), [
            'is_active' => true,
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertTrue($staff->refresh()->is_active);
    }

    public function test_non_admin_cannot_manage_staff_members(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.staff.index'))
            ->assertForbidden();

        $this->app['auth']->guard()->logout();
        $this->get(route('admin.staff.index'))->assertRedirect(route('login'));
    }
}
