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
            ->assertSee('Portal Akses Sistem Informasi BHP Medan')
            ->assertSee('Layanan publik terintegrasi dan transparan')
            ->assertSeeInOrder(['Jam operasional', 'Senin–Kamis 08.00–16.00 WIB', 'Jumat 08.00–16.30 WIB'])
            ->assertSeeInOrder(['Layanan Publik', 'Sistem Informasi Pelayanan Publik', 'Survei Layanan', 'Layanan Pengaduan Masyarakat'])
            ->assertSee('href="'.route('public-services.index').'"', false)
            ->assertSee('href="https://www.batamen.com/"', false)
            ->assertDontSee('Soon')
            ->assertDontSee('main-nav', false)
            ->assertDontSee('menu-toggle', false)
            ->assertDontSee('bhp-logo-showcase', false)
            ->assertSee('welcome-mascot', false)
            ->assertSee('Buka Batamen')
            ->assertSee('mascot-batamen-icon', false)
            ->assertDontSee('Akses layanan digital Batamen')
            ->assertDontSee('data-mascot-message', false)
            ->assertSee('data-pwa-install-panel', false)
            ->assertSee('data-pwa-install-panel hidden', false)
            ->assertSee('data-pwa-install', false)
            ->assertSee('Pasang PASTI Batamen')
            ->assertSee('Logo Pengayoman')
            ->assertDontSee('Tentang Kami');
    }

    public function test_manifest_contains_required_application_icons(): void
    {
        $manifest = json_decode((string) file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame('/', $manifest['id']);
        $this->assertSame('PASTI Batamen', $manifest['name']);
        $this->assertSame('PASTI Batamen', $manifest['short_name']);
        $this->assertSame('192x192', $manifest['icons'][0]['sizes']);
        $this->assertSame('512x512', $manifest['icons'][1]['sizes']);
        $this->assertFileExists(public_path('assets/app-icon-192.png'));
        $this->assertFileExists(public_path('assets/app-icon-512.png'));
    }

    public function test_browser_controls_the_native_pwa_install_prompt(): void
    {
        $pwaScript = (string) file_get_contents(public_path('assets/pwa.js'));

        $this->assertStringContainsString("navigator.serviceWorker.register('/service-worker.js')", $pwaScript);
        $this->assertStringContainsString('beforeinstallprompt', $pwaScript);
        $this->assertStringContainsString('installPrompt.prompt()', $pwaScript);
        $this->assertStringContainsString('installPanel.hidden = true', $pwaScript);
        $this->assertStringContainsString('installPanel.hidden = false', $pwaScript);
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
