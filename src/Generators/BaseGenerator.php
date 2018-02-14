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
    /*The table name striped from any spacial characters and spaces.
    This will be used for method names*/
    protected $tableName = "";
    /*The table name in its original form exactly like it is in parse database
    ex. _User, Foo_Bar */
    protected $tableNameOriginal = "";
    /*Reference to command object, this will be used to print data to the command line*/
    protected $command;
    /*An Array of All fields in the parse table*/
    protected $tableFields;
    /*An Array of All Writable fields in the parse table (Without objectId,createdDate, updatedAt)
    This will be used to generate edit and create without those fields*/
    protected $writableTableFields;
    /*
     NONE:Plain HTML
     metronic: with metronic css classes
    */
    protected $template;

    /**
     * Inits Base Generator Objects
     * @param Command $command A Reference to the Command Object
     * @param String $tableName Parse table name to Generate For
     * @param String $template The type of template to be generated (NONE:Plain HTML, metronic: with metronic css classes)
     */
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

    /**
     * Moves a file from a source location to a target destination directory.
     * If the file already exists in the destination directory, a user will be prompted to replace it or skip
     * @param String $source
     * @param String $destination
     */
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