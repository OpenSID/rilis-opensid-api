<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class HealthCheckController extends \App\Services\HealthCheck\HealthCheckController
{
    /**
     * {@inheritdoc}
     */
    public function registerHealthchecks(Request $request)
    {
        $this->withOutput();

        // premium
        $this->addHealthcheck('premium', function () {
            try {
                Http::withOptions(['base_uri' => config('services.layanan.domain')])
                    ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
                    ->withToken(config('services.layanan.secret'))
                    ->get('api/v1/pelanggan/domain', ['kode_desa' => config('services.layanan.key')])
                    ->throw();
            } catch (Exception $e) {
                Log::error($e);
                return $e->getMessage();
            }

            return true;
        });

        // database
        $this->addHealthcheck('database', function () {
            try {
                DB::connection()->getPdo();
            } catch (Exception $e) {
                Log::error($e);
                return $e->getMessage();
            }

            return true;
        });

        // ftp
        $this->addHealthcheck('ftp', function () {
            try {
                Storage::disk('ftp')->allFiles();
            } catch (Exception $e) {
                Log::error($e);
                return $e->getMessage();
            }

            return true;
        });

        // email
        $this->addHealthcheck('email', function () use($request) {
            if (!env('MAIL_FROM_ADDRESS')) {
                return 'MAIL_FROM_ADDRESS perlu di isi';
            }
            try {
                if ($request['email']='ignore') {
                    return true;
                }
                Mail::raw('Cek email', function ($msg) {
                    $msg->to(env('MAIL_FROM_ADDRESS'))->subject('Test Email');
                });
            } catch (Exception $e) {
                Log::error($e);
                return $e->getMessage();
            }

            return true;
        });

        // production
        $this->addHealthcheck('production', function () {
            return app()->isProduction() && !app()->hasDebugModeEnabled()
                ? true
                : false;
        });

        // appkey
        $this->addHealthcheck('appkey', function () {
            $appkey = get_app_key();

           if ($appkey == null) {
            return false;
           }
           return true;
        });
    }
}
