<?php
/**
 * Created by PhpStorm.
 * User: Web Dev
 * Date: 1/28/2018
 * Time: 3:00 PM
 */

namespace elmokna\laravelParseScaffolder\Generators;

use elmokna\laravelParseScaffolder\ParseHelpers;
use Parse\ParseSchema;

class ModelGenerator extends BaseGenerator
{
    public function generate()
    {
        $fileName = ucfirst($this->tableName) . ".php";
        $destination = app_path("Http/$fileName");

        $this->move(__DIR__ . "/../templates/models/TemplateModel.php", $destination);

        //Add Content
        $this->__replaceContent($destination);

        $this->command->info("$fileName generated");
    }

    private function __replaceContent(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        //replace TemplateController class name to the current given class name
        $fileContent = str_replace("TemplateModel", ucfirst($this->tableName), $fileContent);

        //replace parse table name field with the current given class name
        $fileContent = str_replace("CLASS-NAME-HERE", $this->tableNameOriginal, $fileContent);

        //Assuming that at this point parse schema is reachable
        try {
            //get all fields in a class
            $fields = $this->writableTableFields;

            //create Setters and Getters with this fields
            $settersContent = [];
            $gettersContent = [];
            $includesContent = [];
            foreach ($fields as $fieldKey => $fieldValue) {
                $generatedSetters = "";
                $generatedGetters = "";
                switch ($fieldValue["type"]) {
                    case "Boolean":
                        $res = $this->generateMethod("bool", $fieldKey);

                        $generatedSetters = $res[0];
                        $generatedGetters = $res[1];
                        break;
                    case "String":
                        $res = $this->generateMethod("String", $fieldKey);

                        $generatedSetters = $res[0];
                        $generatedGetters = $res[1];
                        break;
                    case "Number":
                        $res = $this->generateMethod("Int", $fieldKey);

                        $generatedSetters = $res[0];
                        $generatedGetters = $res[1];
                        break;
                    case "Date":
                        $res = $this->generateMethod("", $fieldKey);
                        $generatedSetters = $res[0];

                        $generatedGetters = "public function get" . ucfirst($fieldKey) . "( )";
                        $generatedGetters .= "\n{";
                        $generatedGetters .= "\n try {";
                        $generatedGetters .= "\nreturn \$this->get(\"$fieldKey\")->format('d-m-Y');";
                        $generatedGetters .= "\n} catch (\Exception \$e) {return null;\n}";
                        $generatedGetters .= "\n}";
                        break;
                    case "File":
                        $generatedSetters = "public function set" . ucfirst($fieldKey) . "(\$" . lcfirst($fieldKey) . ")";
                        $generatedSetters .= "\n{";
                        $generatedSetters .= "\n try {";
                        $generatedSetters .= "\n \$" . lcfirst($fieldKey) . "->move(public_path('uploads'), $" . lcfirst($fieldKey) . "->getClientOriginalName());";
                        $generatedSetters .= "\n \$file = ParseFile::createFromFile(public_path(\"uploads/\".\$" . lcfirst($fieldKey) . "->getClientOriginalName()), \$" . lcfirst($fieldKey) . "->getClientOriginalName());";
                        $generatedSetters .= "\n\$this->set(\"$fieldKey\", \$file);";
                        $generatedSetters .= "\n} catch (\Exception \$e) {\n}";
                        $generatedSetters .= "\n}";


                        $generatedGetters = "public function get" . ucfirst($fieldKey) . "( )";
                        $generatedGetters .= "\n{";
                        $generatedGetters .= "\n try {";
                        $generatedGetters .= "\nreturn \$this->get(\"$fieldKey\")->getURL();";
                        $generatedGetters .= "\n} catch (\Exception \$e) {return null;\n}";
                        $generatedGetters .= "\n}";
                        break;
                    case "Relation":
                        $targetClass = $fieldValue["targetClass"];

                        $generatedSetters = "public function set" . ucfirst($fieldKey) . "(array \$" . lcfirst($fieldKey) . ")";
                        $generatedSetters .= "\n{";
                        $generatedSetters .= "\n try {";
                        $generatedSetters .= "\n foreach(\$" . lcfirst($fieldKey) . " as \$item){";
                        $generatedSetters .= "\n \$this->getRelation(\"" . $fieldKey . "\")->add( new ParseObject(\"$targetClass\",\$item));";
                        $generatedSetters .= "\n }";
                        $generatedSetters .= "\n} catch (\Exception \$e) {\n}";
                        $generatedSetters .= "\n}";



                        $generatedGetters = "public function get" . ucfirst($fieldKey) . "( )";
                        $generatedGetters .= "\n{";
                        $generatedGetters .= "\n try {";
                        $generatedGetters .= "\nreturn \$this->get(\"$fieldKey\");";
                        $generatedGetters .= "\n} catch (\Exception \$e) {return null;\n}";
                        $generatedGetters .= "\n}";
                        break;
                    case "GeoPoint":
                        $res = $this->generateMethod("", $fieldKey);

                        $generatedSetters = "public function set" . ucfirst($fieldKey) . "(\$" . lcfirst($fieldKey) . ")";
                        $generatedSetters .= "\n{";
                        $generatedSetters .= "\n\$coordinates = explode(\",\",\$" . lcfirst($fieldKey) . ");";
                        $generatedSetters .= "\n\$point = new ParseGeoPoint(\$coordinates[0], \$coordinates[1]);";
                        $generatedSetters .= "\n try {";
                        $generatedSetters .= "\nreturn \$this->set(\"$fieldKey\", \$point);";
                        $generatedSetters .= "\n} catch (\Exception \$e) {return null;\n}";
                        $generatedSetters .= "\n}";

                        $generatedGetters = $res[1];


                        break;
                    case "Pointer":
                        array_push($includesContent, "\n \$query->includeKey(\"$fieldKey\");");
                        $res = $this->generateMethod("", $fieldKey);
                        /*Setters*/
                        $generatedSetters = "public function set" . ucfirst($fieldKey) . "(String \$" . lcfirst($fieldKey) . ")";
                        $generatedSetters .= "\n{";
                        $generatedSetters .= "\n try {";
                        $generatedSetters .= "\n\$this->set(\"$fieldKey\", new ParseObject( \"" . $fieldValue["targetClass"] . "\" , \$" . lcfirst($fieldKey) . "));";
                        $generatedSetters .= "\n} catch (\Exception \$e) {\n}";
                        $generatedSetters .= "\n}";

                        $generatedGetters = $res[1];

                        break;
                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }
                if ($generatedSetters && $generatedGetters) {
                    array_push($settersContent, $generatedSetters);
                    array_push($gettersContent, $generatedGetters);
                }
            }

            //$fileContent = str_replace("/*INCLUDES-HERE*/", implode("\n", $includesContent), $fileContent);
            $fileContent = str_replace("/*SETTERS-HERE*/", implode("\n", $settersContent), $fileContent);

            $fileContent = str_replace("/*GETTERS-HERE*/", implode("\n", $gettersContent), $fileContent);

        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }

        file_put_contents($file_path, $fileContent);
    }

    /**
     *  Generate Setters And getters for basic data types
     * TODO separate this method to 2 one fpr setter and the other for getter
     * @param String $type A string specifying the casting type ex. (Int)
     * @param String $fieldKey The
     * @return array an array of 2 elements.
     * The First one is the Setter method as String,
     * The Second one is the getter method as String
     */
    private function generateMethod(String $type, String $fieldKey)
    {
        /*Setters*/
        $generatedSetters = "public function set" . ucfirst($fieldKey) . "($type \$" . lcfirst($fieldKey) . ")";
        $generatedSetters .= "\n{";
        $generatedSetters .= "\n try {";
        $generatedSetters .= "\n\$this->set(\"$fieldKey\", \$" . lcfirst($fieldKey) . ");";
        $generatedSetters .= "\n} catch (\Exception \$e) {\n}";
        $generatedSetters .= "\n}";


        /*Getters*/
        $generatedGetters = "public function get" . ucfirst($fieldKey) . "( )";
        $generatedGetters .= "\n{";
        $generatedGetters .= "\n try {";
        $generatedGetters .= "\nreturn \$this->get(\"$fieldKey\");";
        $generatedGetters .= "\n} catch (\Exception \$e) {return null;\n}";
        $generatedGetters .= "\n}";

        return [$generatedSetters, $generatedGetters];
    }
}