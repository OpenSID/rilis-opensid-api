<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // database
        $this->addHealthcheck('database', function () {
            try {
                DB::connection()->getPdo();
            } catch (Exception $e) {
                return false;
            }

            return true;
        });

        // ftp
        $this->addHealthcheck('ftp', function () {
            try {
                Storage::disk('ftp')->allFiles();
            } catch (Exception $e) {
                return false;
            }

            return true;
        });

        // email
        $this->addHealthcheck('email', function () {
            try {
                Mail::getSwiftMailer()->getTransport()->start();
            } catch (Exception $e) {
                return false;
            }

            return true;
        });

        // production
        $this->addHealthcheck('production', function() {
            return app()->isProduction() && ! app()->hasDebugModeEnabled()
                ? true
                : false;
        });
    }
}
