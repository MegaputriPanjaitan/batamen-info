<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PublicServiceController extends Controller
{
    public function index(): View
    {
        return view('public-services', [
            'services' => config('public_services'),
        ]);
    }

    public function show(string $service): View
    {
        $services = config('public_services');

        abort_unless(is_array($services) && array_key_exists($service, $services), 404);

        return view('public-service-detail', [
            'service' => $services[$service],
            'serviceSlug' => $service,
        ]);
    }
}
