<?php

namespace Tests\Feature;

use App\Models\InternalSurveyEmployee;
use App\Models\ServiceAccessEvent;
use App\Models\StaffMember;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPageControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[DataProvider('publicPages')]
    public function test_public_page_renders_expected_content(string $routeName, string $expectedText): void
    {
        $this->get(route($routeName))
            ->assertOk()
            ->assertSee($expectedText)
            ->assertSee(asset('assets/styles.css'));
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function publicPages(): array
    {
        return [
            'home' => ['home', 'Informasi'],
            'public services' => ['public-services.index', 'Temukan layanan yang Anda perlukan'],
            'information' => ['information.index', 'Informasi publik dalam satu akses'],
            'surveys' => ['surveys.index', 'Survei Petugas Layanan'],
            'staff survey' => ['staff-surveys.create', 'Bagaimana pengalaman pelayanan Anda?'],
            'complaint' => ['complaints.create', 'Sampaikan laporan dengan aman'],
            'test' => ['tests.index', 'Belum ada test tersedia'],
        ];
    }

    public function test_staff_survey_uses_agreement_scale(): void
    {
        $this->get(route('staff-surveys.create'))
            ->assertOk()
            ->assertSeeInOrder(['Sangat Tidak Setuju', 'Tidak Setuju', 'Cukup', 'Setuju', 'Sangat Setuju'])
            ->assertDontSee('Sangat Tidak Baik');
    }

    public function test_complaint_form_displays_contact_examples(): void
    {
        $this->get(route('complaints.create'))
            ->assertOk()
            ->assertSee('placeholder="Contoh: 081234567890"', false)
            ->assertSee('placeholder="Contoh: nama@email.com"', false);
    }

    public function test_service_pages_display_the_updated_public_labels(): void
    {
        $this->get(route('information.index'))
            ->assertOk()
            ->assertSee('Pusat Informasi Pelayanan Publik')
            ->assertSee('PPID BHP Medan')
            ->assertSee('Informasi yang Anda cari belum tersedia? Akses informasi publik lainnya di PPID BHP Medan.')
            ->assertSee('href="https://bhpmedan.kemenkum.go.id/pusat-informasi/ppid/tentang-ppid"', false)
            ->assertSee('ppid-access-card', false)
            ->assertSeeInOrder(['Manual IKU Kemenkum', 'PPID BHP Medan', 'Terhubung dengan kami'])
            ->assertDontSee('Pusat Informasi Terpadu');

        $this->get(route('surveys.index'))
            ->assertOk()
            ->assertSee('Survei Layanan')
            ->assertDontSee('Survei Pelayanan BHP Medan');

        $this->get(route('complaints.create'))
            ->assertOk()
            ->assertSee('Layanan Pengaduan Masyarakat')
            ->assertDontSee('Kanal Pengaduan Resmi');
    }

    public function test_staff_survey_separates_staff_selection_and_questions(): void
    {
        $this->get(route('staff-surveys.create'))
            ->assertOk()
            ->assertSee('data-survey-step="staff"', false)
            ->assertSee('data-survey-step="questions"', false)
            ->assertDontSee('Lanjut ke Pertanyaan')
            ->assertDontSee('Ganti petugas')
            ->assertDontSee('Cari petugas');
    }

    public function test_staff_survey_requires_confirmation_before_questions(): void
    {
        StaffMember::factory()->create();

        $this->get(route('staff-surveys.create'))
            ->assertOk()
            ->assertSee('data-staff-confirm-dialog', false)
            ->assertSee('Apakah ini petugasnya?')
            ->assertSee('data-confirm-staff-photo', false)
            ->assertSee('data-confirm-staff-nip', false)
            ->assertSee('Anda tidak dapat memilih petugas ini lagi.')
            ->assertSee('data-confirm-staff-survey', false);
    }

    public function test_internal_survey_displays_nip_login_dialog(): void
    {
        $this->get(route('surveys.index'))
            ->assertOk()
            ->assertSee(route('internal-surveys.login'), false)
            ->assertSee('Login Survei Integritas')
            ->assertSee('name="nip"', false);
    }

    public function test_survey_types_are_displayed_in_the_requested_order(): void
    {
        $this->get(route('surveys.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'Survei Tuntas Waris',
                'Survei SPAK',
                'Survei Petugas Layanan',
                'Survei Integritas',
            ]);
    }

    public function test_public_services_page_lists_eight_services_and_links_to_details(): void
    {
        $this->get(route('public-services.index'))
            ->assertOk()
            ->assertSeeInOrder([
                'Ketidakhadiran (Afwezigheid)',
                'Pendaftaran dan Pembukaan Wasiat',
                'Kepailitan',
                'Penatausahaan Uang Pihak Ketiga',
                'Perwalian',
                'Pengampuan',
                'Surat Keterangan Hak Waris',
                'Harta Peninggalan Tidak Terurus',
            ])
            ->assertSee(route('public-services.show', 'perwalian'), false)
            ->assertSee(route('public-services.show', 'hak-waris'), false)
            ->assertDontSee('Layanan BHP</small>', false);
    }

    public function test_public_service_detail_displays_official_requirements_tariffs_and_procedure(): void
    {
        $this->get(route('public-services.show', 'perwalian'))
            ->assertOk()
            ->assertSee('Dokumen persyaratan')
            ->assertSee('Penetapan perwalian')
            ->assertSee('Biaya dan tarif')
            ->assertSee('Rp200.000 per permohonan')
            ->assertSee('Alur pengurusan')
            ->assertDontSee('Jangka waktu')
            ->assertDontSee('Produk pelayanan')
            ->assertDontSee('Sumber resmi');

        $this->get(route('public-services.show', 'layanan-tidak-ada'))
            ->assertNotFound();
    }

    public function test_only_active_registered_nip_can_open_internal_survey(): void
    {
        $employee = InternalSurveyEmployee::create([
            'nip' => '198765432109876543',
            'name' => 'Pegawai Uji',
            'is_active' => true,
        ]);

        $this->post(route('internal-surveys.access'), ['nip' => '111111111111111111'])
            ->assertSessionHasErrors('nip', null, 'internalSurvey');

        $this->post(route('internal-surveys.access'), ['nip' => $employee->nip])
            ->assertRedirect(config('services.internal_survey.url'))
            ->assertSessionHas('internal_survey_employee_id', $employee->id);

    }

    public function test_external_and_staff_survey_accesses_are_counted(): void
    {
        $this->get(route('spkp-spak.access'))
            ->assertRedirect(config('services.spkp_spak.url'));

        $this->get(route('internal-surveys.login'))
            ->assertRedirect(route('surveys.index'))
            ->assertSessionHas('open_internal_survey_login', true);

        $this->assertDatabaseHas('service_access_events', ['service' => ServiceAccessEvent::SPKP_SPAK]);
        $this->assertDatabaseHas('service_access_events', ['service' => ServiceAccessEvent::INTERNAL_INTEGRITY]);
    }
}
