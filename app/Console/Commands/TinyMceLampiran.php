<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TinyMceLampiran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:lampiran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy lampiran ke localstorage';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $templates = Storage::disk('ftp')->allFiles('template-surat/lampiran/');
        foreach ($templates as $template) {
            $file = Storage::disk('ftp')->get($template);
            Storage::put($template, $file);
        }
    }
}
