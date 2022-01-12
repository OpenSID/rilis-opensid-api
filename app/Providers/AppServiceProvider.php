<?php

namespace App\Providers;

use App\Supports\Md5Hashing;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->afterResolving('hash', function () {
            $this->app['hash']->extend('md5', function () {
                return new Md5Hashing();
            });
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootConfig();
        $this->bootLogQuery();
        $this->bootStrPerkiraanMembaca();
        $this->bootHandlePremium();
    }

    protected function bootLogQuery()
    {
        if ($this->app->environment('local')) {
            Event::listen(QueryExecuted::class, function ($query) {
                $bindings = collect($query->bindings)->map(function ($param) {
                    if (is_numeric($param)) {
                        return $param;
                    } else {
                        return "'$param'";
                    }
                });

                $this->app->log->debug(Str::replaceArray('?', $bindings->toArray(), $query->sql));
            });
        }
    }

    protected function bootConfig()
    {
        // Boot config dari database.
        config([
            // config desa
            'desa' => Cache::remember('desa', 24 * 60 * 60, function () {
                return Schema::hasTable('config')
                    ? DB::table('config as c')
                        ->selectRaw('c.*, (case when p.id is null then m.pamong_nama else p.nama end) as nama_kepala_desa')
                        ->join('tweb_desa_pamong as m', 'c.pamong_id', '=', 'm.pamong_id', 'left')
                        ->join('tweb_penduduk as p', 'p.id', '=', 'm.id_pend', 'left')
                        ->where('m.pamong_ub', 1)
                        ->get()
                        ->map(function ($item) {
                            return (array) $item;
                        })
                        ->first()
                    : null;
            }),
            // config aplikasi
            'aplikasi' => Cache::remember('aplikasi', 24 * 60 * 60, function () {
                return Schema::hasTable('setting_aplikasi')
                    ? DB::table('setting_aplikasi')
                        ->get(['key', 'value'])
                        ->keyBy('key')
                        ->transform(function ($setting) {
                            return $setting->value;
                        })
                        ->toArray()
                    : null;
            }),
        ]);
    }

    protected function bootStrPerkiraanMembaca()
    {
        /**
         * Returns an estimated reading time in a string
         * idea from @link http://briancray.com/posts/estimated-reading-time-web-design/
         *
         * @param  string $content the content to be read
         * @param  int    $wpm
         * @return string estimated read time eg. 1 minute, 30 seconds
         */
        Str::macro('perkiraanMembaca', function ($content = '', $wpm = 200) {
            $wordCount = str_word_count(strip_tags($content));

            $minutes = (int) floor($wordCount / $wpm);
            $seconds = (int) floor($wordCount % $wpm / ($wpm / 60));

            $str_minutes = ($minutes === 1) ? 'minute' : 'minutes';
            $str_seconds = ($seconds === 1) ? 'second' : 'seconds';

            if ($minutes === 0) {
                return "{$seconds} {$str_seconds}";
            } else {
                return "{$minutes} {$str_minutes}, {$seconds} {$str_seconds}";
            }
        });
    }

    protected function bootHandlePremium()
    {
        if (
            $this->app->environment('local') ||
            $this->app->runningInConsole() ||
            $this->app->runningUnitTests()) {
            return;
        }

        $this->app['router']->pushMiddlewareToGroup('api', \App\Http\Middleware\HandlePremiumMiddleware::class);
    }
}
