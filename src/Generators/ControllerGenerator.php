<?php
/**
 * Created by PhpStorm.
 * User: Web Dev
 * Date: 1/28/2018
 * Time: 3:00 PM
 */

namespace approcks\laravelParseScaffolder\Generators;

use approcks\laravelParseScaffolder\ParseHelpers;
use Parse\ParseException;
use Parse\ParseSchema;

class ControllerGenerator extends BaseGenerator
{

    public function generate()
    {
        $fileName = ucfirst($this->tableName) . "Controller.php";

        $destination = app_path("Http/Controllers/$fileName");

        $this->move(__DIR__ . "/../templates/controllers/TemplateController.php", $destination);

        $this->__replaceContent($destination);

        $this->generateIndex($destination);
        $this->generateCreate($destination);
        $this->generateEdit($destination);
        $this->generateStore($destination);
        $this->generateUpdate($destination);
        $this->generateShow($destination);
        $this->generateDestroy($destination);

        $this->command->info("$fileName generated");
    }

    private function __replaceContent(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        //replace TemplateController class name to the current given class name
        $fileContent = str_replace("TemplateController", ucfirst($this->tableName) . "Controller", $fileContent);
        //$fileContent = str_replace("/*USE-HERE*/", "use App\\Http\\" . ucfirst($this->tableName) . ";", $fileContent);
        $fileContent = str_replace("/*REGISTER-HERE*/", ucfirst($this->tableName) . "::registerSubclass();", $fileContent);

        file_put_contents($file_path, $fileContent);
    }

    private function generateIndex(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        $indexContent = "\$json_data = " . ucfirst($this->tableName) . "::QueryAll();";

        $indexContent .= "\nreturn view('$this->tableName.index',
            compact('json_data'));";

        $fileContent = str_replace("/*INDEX-METHOD-HERE*/", $indexContent, $fileContent);

        file_put_contents($file_path, $fileContent);
    }

    private function generateCreate(String $file_path)
    {
        $fileContent = file_get_contents($file_path);
        $indexContent = "";

        try {
            $fields = $this->writableTableFields;
            $viewParams = [];
            $uses = [];

            array_push($uses, "use App\\Http\\" . ucfirst($this->tableName) . ";");

            foreach ($fields as $fieldKey => $fieldValue) {
                switch ($fieldValue["type"]) {
                    case "Pointer":
                        $targetClass = $fieldValue["targetClass"];

                        $indexContent .= "\$$targetClass = " . preg_replace("/[^a-zA-Z]/", "", $targetClass) . "::QueryAll();";
                        $indexContent .= "\$$targetClass = \$$targetClass" . "[\"status_code\"] == 200 ? \$$targetClass" . "[\"data\"] : [];";

                        array_push($viewParams, "\"$targetClass\"");

                        array_push($uses, "use App\\Http\\" . ucfirst(
                                preg_replace("/[^a-zA-Z]/", "", $targetClass)
                            ) . ";");
                        break;
                    case "Relation":
                      //  $targetClass = $fieldValue["targetClass"];
                        break;
                    case "Boolean":
                    case "String":
                    case "Number":
                    case "Date":
                    case "File":
                    case "GeoPoint":

                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }
            }
        } catch (\Exception $e) {
        }

        $indexContent .= "\nreturn view('$this->tableName.create', compact(" . implode(",", $viewParams) . "));";

        $fileContent = str_replace("/*CREATE-METHOD-HERE*/", $indexContent, $fileContent);

        $fileContent = str_replace("/*USE-HERE*/", implode("\n", $uses), $fileContent);

        file_put_contents($file_path, $fileContent);
    }

    private function generateShow(String $file_path)
    {
        $fileContent = file_get_contents($file_path);
        $editContent = "\$item = $this->tableName" . "::QueryGet(\$id);";
        $editContent .= "\$item = \$item[\"status_code\"] ==200? \$item[\"data\"]:[];";

        try {
            $fields = $this->writableTableFields;
            $content = [];
            $viewParams = [];

            array_push($viewParams, "\"item\"");

            foreach ($fields as $fieldKey => $fieldValue) {
                switch ($fieldValue["type"]) {
                    case "Pointer":
                        $editContent .= "\$$fieldKey = " . ucfirst($this->tableName) . "::QueryAll();";
                        $editContent .= "\$$fieldKey = \$$fieldKey" . "[\"status_code\"] == 200 ? \$$fieldKey" . "[\"data\"] : [];";

                        array_push($viewParams, "\"$fieldKey\"");
                        break;
                    case "Boolean":
                    case "String":
                    case "Number":
                    case "Date":
                    case "File":
                    case "GeoPoint":
                    case "Relation":
                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }
            }
        } catch (\Exception $e) {
        }

        $editContent .= "\nreturn view('$this->tableName.show', compact(" . implode(",", $viewParams) . "));";


        $fileContent = str_replace("/*SHOW-METHOD-HERE*/", $editContent, $fileContent);
        file_put_contents($file_path, $fileContent);
    }

    private function generateEdit(String $file_path)
    {
        $fileContent = file_get_contents($file_path);
        $editContent = "\$item = $this->tableName" . "::QueryGet(\$id);";
        $editContent .= "\$item = \$item[\"status_code\"] ==200? \$item[\"data\"]:[];";

        try {
            $fields = $this->writableTableFields;
            $content = [];
            $viewParams = [];

            array_push($viewParams, "\"item\"");

            foreach ($fields as $fieldKey => $fieldValue) {
                switch ($fieldValue["type"]) {
                    case "Pointer":
                        $targetClass = $fieldValue["targetClass"];

                        $editContent .= "\$$targetClass = " . preg_replace("/[^a-zA-Z]/", "", $targetClass) . "::QueryAll();";
                        $editContent .= "\$$targetClass = \$$targetClass" . "[\"status_code\"] == 200 ? \$$targetClass" . "[\"data\"] : [];";

                        array_push($viewParams, "\"$targetClass\"");
                        break;
                    case "Relation":
                        $targetClass = $fieldValue["targetClass"];

                        $editContent .= "\$$targetClass = " . preg_replace("/[^a-zA-Z]/", "", $targetClass) . "::QueryAll();";
                        $editContent .= "\$$targetClass = \$$targetClass" . "[\"status_code\"] == 200 ? \$$targetClass" . "[\"data\"] : [];";

                        array_push($viewParams, "\"$targetClass\"");
                        break;
                    case "Boolean":
                    case "String":
                    case "Number":
                    case "Date":
                    case "File":
                    case "GeoPoint":
                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }
            }
        } catch (\Exception $e) {
        }

        $editContent .= "\nreturn view('$this->tableName.edit', compact(" . implode(",", $viewParams) . "));";

        $fileContent = str_replace("/*EDIT-METHOD-HERE*/", $editContent, $fileContent);
        file_put_contents($file_path, $fileContent);
    }

    private function generateStore(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        try {
            $fields = $this->writableTableFields;
            $content = [];

            $line = "\$object = new " . ucfirst($this->tableName) . "();";

            array_push($content, $line);
            foreach ($fields as $fieldKey => $fieldValue) {
                $line = "";

                switch ($fieldValue["type"]) {
                    case "Boolean":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(filter_var(\$request->get(\"$fieldKey\"), FILTER_VALIDATE_BOOLEAN));";
                        break;
                    case "String":
                    case "Pointer":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(\$request->get(\"$fieldKey\"));";
                        break;
                    case "Number":
                        $line = "\$object->set" . ucfirst($fieldKey) . "((Int)\$request->get(\"$fieldKey\"));";
                        break;
                    case "Date":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(new \DateTime(\$request->get(\"$fieldKey\")));";
                        break;

                    case "File":
                        $line = "if (Input::has('$fieldKey'))";
                        $line .= "\n\$object->set" . ucfirst($fieldKey) . "(Input::file(\"$fieldKey\"));";
                        break;
                    case "GeoPoint":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(\$request->get(\"$fieldKey\"));";
                        break;

                    case "Relation":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(\$request->get(\"$fieldKey\"));";
                        break;
                    case "ACL":
                    case "Object":


                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }

                if ($content) {
                    array_push($content, $line);
                }
            }

            $line = "\$object->save();
            \nreturn \$this->index();";
            array_push($content, $line);

            $fileContent = str_replace("/*STORE-METHOD-HERE*/", implode("\n", $content), $fileContent);
        } catch (ParseException $e) {
        }


        file_put_contents($file_path, $fileContent);
    }

    private function generateUpdate(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        try {
            $fields = $this->writableTableFields;
            $content = [];

            $line = "\$object = new " . ucfirst($this->tableName) . "(\$id);";

            array_push($content, $line);
            foreach ($fields as $fieldKey => $fieldValue) {
                $line = "";

                switch ($fieldValue["type"]) {
                    case "Boolean":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(filter_var(\$request->get(\"$fieldKey\"), FILTER_VALIDATE_BOOLEAN));";
                        break;
                    case "String":
                    case "Pointer":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(\$request->get(\"$fieldKey\"));";
                        break;
                    case "Number":
                        $line = "\$object->set" . ucfirst($fieldKey) . "((Int)\$request->get(\"$fieldKey\"));";
                        break;
                    case "Date":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(new \DateTime(\$request->get(\"$fieldKey\")));";
                        break;

                    case "File":

                        $line = "if (Input::has('$fieldKey'))";
                        $line .= "\n\$object->set" . ucfirst($fieldKey) . "(Input::file(\"$fieldKey\"));";
                        break;
                    case "GeoPoint":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(\$request->get(\"$fieldKey\"));";
                        break;

                    case "Relation":
                        $line = "\$object->set" . ucfirst($fieldKey) . "(\$request->get(\"$fieldKey\"));";
                        break;
                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }

                if ($content) {
                    array_push($content, $line);
                }
            }

            $line = "\$object->save();
            \nreturn \$this->index();";
            array_push($content, $line);

            $fileContent = str_replace("/*UPDATE-METHOD-HERE*/", implode("\n", $content), $fileContent);
        } catch (ParseException $e) {
        }

        file_put_contents($file_path, $fileContent);
    }

    private function generateDestroy(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        $deleteContent = "\$object = new $this->tableName(\$id);";
        $deleteContent .= "\$object->destroy();";
        $deleteContent .= "\n return \$this->index();";
        $fileContent = str_replace("/*DESTROY-METHOD-HERE*/", $deleteContent, $fileContent);
        file_put_contents($file_path, $fileContent);
    }
}