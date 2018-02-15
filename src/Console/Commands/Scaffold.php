<?php

namespace approcks\laravelParseScaffolder\Console\Commands;

use approcks\laravelParseScaffolder\Generators\ControllerGenerator;
use approcks\laravelParseScaffolder\Generators\ModelGenerator;
use approcks\laravelParseScaffolder\Generators\ViewGenerator;
use Illuminate\Console\Command;

/*
 * @see https://ourcodeworld.com/articles/read/248/how-to-create-a-custom-console-command-artisan-for-laravel-5-3
 * */

class Scaffold extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {parseTableName} {--template=none}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // Get Table name from command line with argument name "parseTableName"
        $parseTableName = $this->argument('parseTableName');

        //Get template option from command line
        $template = $this->option("template");

        //Init Model Generator
        $modelGenerator = new ModelGenerator($this, $parseTableName, $template);
        //Generate Model File for the given parse table
        $modelGenerator->generate();

        //Init Controller Generator
        $controllerGenerator = new ControllerGenerator($this, $parseTableName, $template);
        //Generate A Resource Controller File for the given parse table
        $controllerGenerator->generate();

        //Init Views Controller
        $viewsGenerator = new ViewGenerator($this, $parseTableName, $template);
        //Generate 4 Views (Index, Create, Show,Edit) Files for the given parse table
        $viewsGenerator->generate();
    }

}
