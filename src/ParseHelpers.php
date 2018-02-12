<?php
/**
 * Created by PhpStorm.
 * User: Web Dev
 * Date: 1/30/2018
 * Time: 5:28 PM
 */

namespace approcks\laravelParseScaffolder;


use Parse\ParseClient;
use Parse\ParseException;
use Parse\ParseSchema;
use Parse\ParseSessionStorage;

class ParseHelpers
{
    public static function getParseTableFields(String $tableName, bool $isWritable = true)
    {
        $parseSchema = new ParseSchema($tableName);
        try {
            $tableSchema = $parseSchema->get();
            $fields = $tableSchema["fields"];

            if ($isWritable) {
                unset($fields["objectId"]);
                unset($fields["createdAt"]);
                unset($fields["updatedAt"]);
            }

            return $fields;
        } catch (ParseException $e) {
            logger($e->getMessage(), ["getParseTableFields", "ParseHelpers"]);
            return [];
        }
    }

    public static function initParse()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        try {
            ParseClient::initialize(env("DB_USERNAME"), "", env("DB_PASSWORD"));
            ParseClient::setServerURL(env("DB_HOST") . ":" . env("DB_PORT"), 'parse');
            ParseClient::setStorage(new ParseSessionStorage());
        } catch (\Exception $e) {
            logger($e->getMessage());
        }
    }
}