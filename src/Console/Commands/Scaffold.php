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
        // Get single Parameters
        $parseTableName = $this->argument('parseTableName');
        // Get all
        $template = $this->option("template");

        if (!$parseTableName) {
            $this->error("Parse Table Name Must Be Specified");
        }

        $modelGenerator = new ModelGenerator($this, $parseTableName, $template);
        $modelGenerator->generate();

        $controllerGenerator = new ControllerGenerator($this, $parseTableName, $template);
        $controllerGenerator->generate();

        $viewsGenerator = new ViewGenerator($this, $parseTableName, $template);
        $viewsGenerator->generate();
    }

    /*// Stop execution and ask a question
     $answer = $this->ask('What is your name?');

    // Ask for sensitive information
    $password = $this->secret('What is the password?');

    // Choices
    $name = $this->choice('What is your name?', ['Taylor', 'Dayle'], $default);

    // Confirmation
    if ($this->confirm('Is '.$name.' correct, do you wish to continue? [y|N]')) {
        //
    }

    $this->line("Some text");
    $this->info("Hey, watch this !");
    $this->comment("Just a comment passing by");
    $this->question("Why did you do that?");
    $this->error("Ops, that should not happen.");*/
}
