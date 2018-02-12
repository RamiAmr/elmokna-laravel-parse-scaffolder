<?php
/**
 * Created by PhpStorm.
 * User: Web Dev
 * Date: 1/28/2018
 * Time: 3:07 PM
 */

namespace approcks\laravelParseScaffolder\Generators;

use approcks\laravelParseScaffolder\enums\Templates;
use approcks\laravelParseScaffolder\ParseHelpers;
use Illuminate\Console\Command;

class BaseGenerator
{
    protected $tableName = "";
    protected $tableNameOriginal = "";
    protected $command = "";
    protected $tableFields;
    protected $writableTableFields;
    protected $template;

    public function __construct(Command $command, String $tableName, String $template = Templates::NONE)
    {
        ParseHelpers::initParse();

        $this->tableNameOriginal = $tableName;
        $this->tableName = preg_replace("/[^a-zA-Z]/", "", $tableName);
        $this->command = $command;

        $this->writableTableFields = ParseHelpers::getParseTableFields($this->tableNameOriginal, true);
        $this->tableFields = ParseHelpers::getParseTableFields($this->tableNameOriginal, false);

        $this->template = $template;
    }

    protected function move(String $source, String $destination)
    {
        $hasBeenMoved = false;

        if (file_exists($destination)) {
            //if file already exists ask for confirmation to overwrite it
            if ($this->command->confirm("$destination Exist, do you wish to Overwrite it?", true)) {
                $this->command->line("Over writing $destination...");
                $hasBeenMoved = copy($source, $destination);
            } else {
                $this->command->line("Overwriting denied");
            }
        } else {
            //file doesn't exist copy it to destination directory
            $hasBeenMoved = copy($source, $destination);
        }

        if (!$hasBeenMoved) {
            $this->command->error("File has not been moved");
        }
    }

}