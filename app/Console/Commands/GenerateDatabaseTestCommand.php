<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateDatabaseTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:dump-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump the given database schema for test purpose only.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('schema:dump', [
            '--database' => 'mysql',
            '--path' => 'database/schema/tests-schema.dump',
        ]);
    }
}
