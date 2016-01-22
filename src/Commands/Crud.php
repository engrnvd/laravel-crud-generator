<?php

namespace Nvd\Crud\Commands;

use Illuminate\Console\Command;
use Nvd\Crud\Db;
use Nvd\Crud\Generators\RouteGenerator;

class Crud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nvd:crud
        {tableName : The name of the table or the string "all" if you want to generate crud for all the tables at once.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate crud for a specific table or all tables in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $table = $this->argument('tableName');
//        $fields = Db::fields($table);
//        dd($fields);
        (new RouteGenerator($table))->generate();
    }
}
