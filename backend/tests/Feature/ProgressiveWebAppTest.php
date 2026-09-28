<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressiveWebAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_home_exposes_installable_app_metadata(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('manifest.webmanifest', false)
            ->assertSee('assets/pwa.js', false)
            ->assertSee('app-service-grid', false)
            ->assertSee('app-service-icon', false)
            ->assertSee('<svg viewBox="0 0 24 24">', false)
            ->assertSee('Logo BHP Medan')
            ->assertSee('Selamat Datang di BHP Medan')
            ->assertSee('Layanan publik terintegrasi dan transparan')
            ->assertSee('PENGUMUMAN')
            ->assertSeeInOrder(['Sistem Informasi Pelayanan Publik', 'Survei Layanan', 'Pengaduan', 'Batamen'])
            ->assertSee('href="https://www.batamen.com/"', false)
            ->assertDontSee('Soon')
            ->assertDontSee('main-nav', false)
            ->assertDontSee('menu-toggle', false)
            ->assertDontSee('bhp-logo-showcase', false)
            ->assertSee('welcome-mascot', false)
            ->assertSee('Selamat datang di Informasi dan Layanan BHP Medan.')
            ->assertSee('data-pwa-install-panel', false)
            ->assertSee('data-pwa-install', false)
            ->assertSee('Pasang Batamen Info')
            ->assertSee('Tambahkan ke Layar Utama')
            ->assertDontSee('Tentang Kami');
    }

    public function test_manifest_contains_required_application_icons(): void
    {
        $manifest = json_decode((string) file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('/', $manifest['id']);
        $this->assertSame('Batamen Info', $manifest['name']);
        $this->assertSame('192x192', $manifest['icons'][0]['sizes']);
        $this->assertSame('512x512', $manifest['icons'][1]['sizes']);
        $this->assertFileExists(public_path('assets/app-icon-192.png'));
        $this->assertFileExists(public_path('assets/app-icon-512.png'));
    }

    public function test_offline_page_and_service_worker_are_available(): void
    {
        $offlinePage = (string) file_get_contents(public_path('offline.html'));
        $serviceWorker = (string) file_get_contents(public_path('service-worker.js'));

        $this->assertStringContainsString('Anda sedang offline', $offlinePage);
        $this->assertStringContainsString("request.method !== 'GET'", $serviceWorker);
        $this->assertStringContainsString("request.mode === 'navigate'", $serviceWorker);
        $this->assertStringContainsString('/assets/bhp-medan-building.jpeg', $serviceWorker);
        $this->assertFileExists(public_path('assets/bhp-medan-building.jpeg'));
    }

    public function test_scroll_navigation_is_limited_to_home_page(): void
    {
        $navigationScript = (string) file_get_contents(public_path('assets/script.js'));

        $this->assertStringContainsString("!document.body.classList.contains('inner-page')", $navigationScript);
        $this->assertStringContainsString("addEventListener('scroll'", $navigationScript);
        $this->assertStringContainsString('updateHomeNavigation', $navigationScript);
    }

    public function test_public_service_pages_use_the_shared_bhp_logo_header(): void
    {
        foreach ([route('information.index'), route('surveys.index'), route('staff-surveys.create'), route('complaints.create'), route('tests.index')] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('assets/logo-bhp-medan-display.png', false)
                ->assertSee('Logo BHP Medan')
                ->assertDontSee('main-nav', false)
                ->assertDontSee('menu-toggle', false)
                ->assertSee('service-back-button', false)
                ->assertSee('Kembali');
        }
    }
}
