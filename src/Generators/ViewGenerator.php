<?php
/**
 * Created by PhpStorm.
 * User: Web Dev
 * Date: 1/28/2018
 * Time: 3:00 PM
 */

namespace elmokna\laravelParseScaffolder\Generators;


use elmokna\laravelParseScaffolder\enums\Templates;
use elmokna\laravelParseScaffolder\HTMLElementsHelpers;

class ViewGenerator extends BaseGenerator
{

    public function generate()
    {
        $base_source_path = __DIR__ . "/../templates/views/";

        switch ($this->template) {
            case Templates::METRONIC:
                $base_source_path .= Templates::METRONIC . "/";
                break;

            case Templates::NONE:
            default:
                $base_source_path .= "base/";
                break;
        }

        $base_destination_path = resource_path("views/" . $this->tableName);
        if (!file_exists($base_destination_path)) {
            mkdir($base_destination_path, 0777, true);
        }

        $index = $base_destination_path . "/index.blade.php";
        $show = $base_destination_path . "/show.blade.php";
        $create = $base_destination_path . "/create.blade.php";
        $edit = $base_destination_path . "/edit.blade.php";

        //MoveCONTENT-HERE
        $this->move($base_source_path . "index.blade.php", $index);
        $this->move($base_source_path . "show.blade.php", $show);
        $this->move($base_source_path . "create.blade.php", $create);
        $this->move($base_source_path . "edit.blade.php", $edit);


        $this->__generateIndexPage($index);
        $this->command->info($this->tableName . "/index.blade.php generated");
        $this->__generateShowPage($show);
        $this->command->info($this->tableName . "/show.blade.php generated");
        $this->__generateCreatePage($create);
        $this->command->info($this->tableName . "/create.blade.php generated");
        $this->__generateEditPage($edit);
        $this->command->info($this->tableName . "/edit.blade.php generated");

    }

    private function __generateIndexPage(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        try {
            $fields = $this->tableFields;

            $htmlTableHeadersContent = [];
            $htmlTableDataContent = [];
            foreach ($fields as $fieldKey => $fieldValue) {

                if (!($fieldKey == "objectId" || $fieldKey == "createdAt" || $fieldKey == "updatedAt"))
                    switch ($fieldValue["type"]) {
                        case "Boolean":
                            $tableHeader = "<th>$fieldKey</th>";
                            array_push($htmlTableHeadersContent, $tableHeader);

                            /*href="{{action("SandboxController@show")}}*/
                            $tableData = "<td>{{array_key_exists(\"$fieldKey\",\$json_item) ? \$json_item[\"$fieldKey\"]? \"True\":\"False\" : \"False\"}}</td>";
                            //$tableData = "<td>{{\$json_item[\"$fieldKey\"]}}</td>";
                            array_push($htmlTableDataContent, $tableData);
                            break;
                            break;
                        case "String":
                        case "Number":
                            $tableHeader = "<th>$fieldKey</th>";
                            array_push($htmlTableHeadersContent, $tableHeader);

                            /*href="{{action("SandboxController@show")}}*/
                            $tableData = "<td>{{array_key_exists(\"$fieldKey\",\$json_item) ? str_limit(\$json_item[\"$fieldKey\"],50) : \"N/A\"}}</td>";
                            //$tableData = "<td>{{\$json_item[\"$fieldKey\"]}}</td>";
                            array_push($htmlTableDataContent, $tableData);
                            break;

                        case "GeoPoint":
                            $tableHeader = "<th>$fieldKey</th>";
                            array_push($htmlTableHeadersContent, $tableHeader);
                            $tableData = "<td>{{array_key_exists(\"$fieldKey\",\$json_item) ? \$json_item[\"$fieldKey\"]->getLatitude().\",\".\$json_item[\"$fieldKey\"]->getLongitude() : \"N/A\"}}</td>";

                            array_push($htmlTableDataContent, $tableData);
                            break;

                    }
            }

            $fileContent = str_replace("{{--CREATE-NAME-HERE--}}", $this->tableName, $fileContent);

            $fileContent = str_replace("INDEX-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@index\")}}", $fileContent);
            $fileContent = str_replace("CREATE-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@create\")}}", $fileContent);
            $fileContent = str_replace("SHOW-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@show\",[\$json_item[\"objectId\"]])}}", $fileContent);
            $fileContent = str_replace("EDIT-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@edit\",[\$json_item[\"objectId\"]])}}", $fileContent);
            //$fileContent = str_replace("DELETE-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@destroy\",[\$json_item[\"objectId\"]])}}", $fileContent);


            $fileContent = str_replace("{{--TABLE-HEAD-HERE--}}",
                implode("\n", $htmlTableHeadersContent), $fileContent);

            $fileContent = str_replace("{{--TABLE-CONTENT-HERE--}}",
                implode("\n", $htmlTableDataContent), $fileContent);

        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }

        file_put_contents($file_path, $fileContent);
    }

    private function __generateShowPage(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        try {
            $fields = $this->writableTableFields;

            $htmlFormContent = [];

            foreach ($fields as $fieldKey => $fieldValue) {
                $htmlElement = "";
                switch ($fieldValue["type"]) {
                    case "Boolean":
                        $htmlElement = "{!! Form::select(\"$fieldKey\",
                        [1 => 'True', 0 => 'False'],(int)\$item[\"$fieldKey\"],[\"disabled\"=>\"true\",'class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "'])!!}";
                        break;
                    case "String":
                        $htmlElement = "{!!Form::text(\"$fieldKey\", null, [\"disabled\"=>\"true\",'class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "','id' => \"$fieldKey\"])!!}";
                        break;
                    case "Number":
                        $htmlElement = "{!!Form::number(\"$fieldKey\", null, [\"disabled\"=>\"true\",'class'=>'" . HTMLElementsHelpers::getNumberInputElementClasses($this->template) . "','id' => \"$fieldKey\"])!!}";
                        break;

                    case "Date":

                        $htmlElement = HTMLElementsHelpers::encapsulateDate($this->template,
                            "{!!Form::date(\"$fieldKey\", \Carbon\Carbon::createFromTimestamp(\$item[\"$fieldKey\"]->getTimeStamp()),[\"disabled\"=>\"true\",\"class\"=>\"" . HTMLElementsHelpers::getNumberInputElementClasses($this->template) . "\",\"id\"=>\"m_datepicker_3\"])!!}");
                        break;

                    case "File":
                        //$htmlElement = HTMLElementsHelpers::getFileInputElement($this->template);
                        break;
                    case "GeoPoint":
                        $htmlElement = "";

                        switch ($this->template) {
                            case Templates::METRONIC:
                                $htmlElement .= "<div class=\"m-input-icon m-input-icon--right\">";
                                break;
                        }

                        $htmlElement .= "{!!Form::text(\"$fieldKey\", \$item[\"$fieldKey\"]->getLatitude().\",\".\$item[\"$fieldKey\"]->getLongitude(), ['class'=>'" . HTMLElementsHelpers::getNumberInputElementClasses($this->template) . "','id' => \"$fieldKey\",
                         \"title\"=>\"Enter a valid Co-ordinate\",
                         \"disabled\"=>\"true\",
                         \"pattern\"=>\"([-+]?\d{1,2}[.]\d+),\s*([-+]?\d{1,3}[.]\d+)\"])!!}";


                        switch ($this->template) {
                            case Templates::METRONIC:
                                $htmlElement .= "<span class=\"m-input-icon__icon m-input-icon__icon--right\">
													<span>
														<i class=\"la la-map-marker\"></i>
													</span>
												</span></div>";
                                break;
                        }
                        break;
                    case "Pointer":
                        $htmlElement = "<select class='" . HTMLElementsHelpers::getNumberInputElementClasses($this->template) . "' id='$fieldKey' name='$fieldKey' disabled>
                                            <option value=''>Choose an Option</option>
                                            @foreach(\$$fieldKey as \$item_$fieldKey)
                                                <option value='{{\$item_$fieldKey" . "[\"objectId\"]}}'>{{\$item_$fieldKey" . "" . "[\"name\"]}}</option>
                                            @endforeach
                                        </select>";
                        break;
                    case "Relation":
                        //TODO
                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }

                if (!empty($htmlElement)) {

                    $wrappedHtml = HTMLElementsHelpers::encapsulateHTML($this->template,
                        "\n{!! Form::label(\"$fieldKey\") !!}
                                   \n $htmlElement");


                    array_push($htmlFormContent, $wrappedHtml);

                }
            }

            $fileContent = str_replace("INDEX-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@index\")}}", $fileContent);
            $fileContent = str_replace("TemplateController",
                ucfirst($this->tableName) . "Controller", $fileContent);

            $fileContent = str_replace("{{--TYPE-HERE--}}",
                ucfirst($this->tableName), $fileContent);

            $fileContent = str_replace("{{--FORM-CONTENT-HERE--}}",
                implode("\n", $htmlFormContent), $fileContent);

        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
        file_put_contents($file_path, $fileContent);
    }

    private function __generateCreatePage(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        try {
            $fields = $this->writableTableFields;

            $htmlFormContent = [];

            foreach ($fields as $fieldKey => $fieldValue) {
                $htmlElement = "";
                switch ($fieldValue["type"]) {
                    case "Boolean":
                        $htmlElement = "{!!Form::select(\"$fieldKey\", 
                        [\"true\" => 'True', \"false\" => 'False'],null,['class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "'])!!}";
                        break;
                    case "String":
                        $htmlElement = "{!!Form::text(\"$fieldKey\", null, ['class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "','id' => \"$fieldKey\"])!!}";
                        break;
                    case "Number":
                        $htmlElement = "{!!Form::number(\"$fieldKey\", null, ['class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "','id' => \"$fieldKey\"])!!}";
                        break;

                    case "Date":
                        $htmlElement = HTMLElementsHelpers::encapsulateDate($this->template,
                            "{!!Form::date(\"$fieldKey\", \Carbon\Carbon::now(),[\"class\"=>\"" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "\",\"id\"=>\"m_datepicker_3\"])!!}");

                        break;
                    case "GeoPoint":
                        $htmlElement = "";

                        switch ($this->template) {
                            case Templates::METRONIC:
                                $htmlElement .= "<div class=\"m-input-icon m-input-icon--right\">";
                                break;
                        }

                        $htmlElement .= "{!!Form::text(\"$fieldKey\", null, ['class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "','id' => \"$fieldKey\",
                         \"title\"=>\"Enter a valid Co-ordinate\",
                         \"pattern\"=>\"([-+]?\d{1,2}[.]\d+),\s*([-+]?\d{1,3}[.]\d+)\"])!!}";


                        switch ($this->template) {
                            case Templates::METRONIC:
                                $htmlElement .= "<span class=\"m-input-icon__icon m-input-icon__icon--right\">
													<span>
														<i class=\"la la-map-marker\"></i>
													</span>
												</span></div>";
                                break;
                        }
                        break;
                    case "File":
                        $htmlElement = HTMLElementsHelpers::getFileInputElement($this->template, $fieldKey);
                        break;
                    case "Pointer":
                        $targetClass = $fieldValue["targetClass"];

                        $htmlElement = "<select class='" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "' id='$fieldKey' name='$fieldKey' required>
                                            <option value=''>Choose an Option</option>
                                            @foreach(\$$targetClass as \$item_$targetClass)
                                                <option value='{{\$item_$targetClass" . "[\"objectId\"]}}'>{{\$item_$targetClass" . "" . "[\"name\"]}}</option>
                                            @endforeach
                                        </select>";
                        break;
                    case "Relation":
                        $targetClass = $fieldValue["targetClass"];
                        $htmlElement = "<select class='" . HTMLElementsHelpers::getSelectInputElementClasses($this->template) . "' id='$fieldKey' name='$fieldKey"."[]' required multiple>
                                            <option value=''>Choose an Option</option>
                                            @foreach(\$$targetClass as \$item_$targetClass)
                                                <option value='{{\$item_$targetClass" . "[\"objectId\"]}}'>{{\$item_$targetClass" . "" . "[\"name\"]}}</option>
                                            @endforeach
                                        </select>";

                        break;
                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }

                if (!empty($htmlElement)) {

                    $wrappedHtml = HTMLElementsHelpers::encapsulateHTML($this->template,
                        "\n{!! Form::label(\"$fieldKey\") !!}
                                   \n $htmlElement");


                    array_push($htmlFormContent, $wrappedHtml);
                }
            }

            $fileContent = str_replace("INDEX-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@index\")}}", $fileContent);
            $fileContent = str_replace("TemplateController",
                ucfirst($this->tableName) . "Controller", $fileContent);

            $fileContent = str_replace("{{--FORM-CONTENT-HERE--}}",
                implode("\n", $htmlFormContent), $fileContent);

        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
        file_put_contents($file_path, $fileContent);
    }

    private function __generateEditPage(String $file_path)
    {
        $fileContent = file_get_contents($file_path);

        try {
            $fields = $this->writableTableFields;

            $htmlFormContent = [];

            foreach ($fields as $fieldKey => $fieldValue) {
                $htmlElement = "";
                switch ($fieldValue["type"]) {
                    case "Boolean":
                        $htmlElement = "{!! Form::select(\"$fieldKey\",
                        [1 => 'True', 0 => 'False'],(int)\$item[\"$fieldKey\"],['class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "'])!!}";
                        break;
                    case "String":
                        $htmlElement = "{!!Form::text(\"$fieldKey\", null, ['class'=>'" . HTMLElementsHelpers::getLabelElementClasses($this->template) . "','id' => \"$fieldKey\"])!!}";
                        break;
                    case "Number":
                        $htmlElement = "{!!Form::number(\"$fieldKey\", null, ['class'=>'" . HTMLElementsHelpers::getNumberInputElementClasses($this->template) . "','id' => \"$fieldKey\"])!!}";
                        break;
                    case "Date":

                        $htmlElement = HTMLElementsHelpers::encapsulateDate($this->template,
                            "{!!Form::date(\"$fieldKey\", \Carbon\Carbon::createFromTimestamp(\$item[\"$fieldKey\"]->getTimeStamp()),[\"class\"=>\"" . HTMLElementsHelpers::getNumberInputElementClasses($this->template) . "\",\"id\"=>\"m_datepicker_3\"])!!}");
                        break;
                    case "File":
                        $htmlElement = HTMLElementsHelpers::getFileInputElement($this->template, $fieldKey);
                        break;
                    case "GeoPoint":
                        $htmlElement = "";

                        switch ($this->template) {
                            case Templates::METRONIC:
                                $htmlElement .= "<div class=\"m-input-icon m-input-icon--right\">";
                                break;
                        }

                        $htmlElement .= "{!!Form::text(\"$fieldKey\", \$item[\"$fieldKey\"]->getLatitude().\",\".\$item[\"$fieldKey\"]->getLongitude(), ['class'=>'" . HTMLElementsHelpers::getNumberInputElementClasses($this->template) . "','id' => \"$fieldKey\",
                         \"title\"=>\"Enter a valid Co-ordinate\",
                         \"pattern\"=>\"([-+]?\d{1,2}[.]\d+),\s*([-+]?\d{1,3}[.]\d+)\"])!!}";


                        switch ($this->template) {
                            case Templates::METRONIC:
                                $htmlElement .= "<span class=\"m-input-icon__icon m-input-icon__icon--right\">
													<span>
														<i class=\"la la-map-marker\"></i>
													</span>
												</span></div>";
                                break;
                        }
                        break;


                        break;
                    case "Pointer":

                        $targetClass = $fieldValue["targetClass"];

                        $htmlElement = "<select class='" . HTMLElementsHelpers::getSelectInputElementClasses($this->template) . "' id='$fieldKey' name='$fieldKey' required>
                                            <option value=''>Choose an Option</option>
                                            @foreach(\$$targetClass as \$item_$targetClass)
                                                <option value='{{\$item_$targetClass" . "[\"objectId\"]}}' @if(\$item_" . $targetClass . "[\"objectId\"] == \$item[\"$fieldKey\"]->getObjectId()) selected=\"selected\" @endif>{{\$item_$targetClass" . "" . "[\"name\"]}}</option>
                                            @endforeach
                                        </select>";
                        break;
                    case "Relation":

                        $targetClass = $fieldValue["targetClass"];
                        $htmlElement = "<select class='" . HTMLElementsHelpers::getSelectInputElementClasses($this->template) . "' id='$fieldKey' name='$fieldKey"."[]' required multiple>
                                            <option value=''>Choose an Option</option>
                                            @foreach(\$$targetClass as \$item_$targetClass)
                                                <option value='{{\$item_$targetClass" . "[\"objectId\"]}}'>{{\$item_$targetClass" . "" . "[\"name\"]}}</option>
                                            @endforeach
                                        </select>";
                        break;
                    case "ACL":
                    case "Object":
                    case "Array":
                    case "Null":
                    default:
                        //UnSupported, Yet
                        break;
                }

                if (!empty($htmlElement)) {

                    $wrappedHtml = HTMLElementsHelpers::encapsulateHTML($this->template,
                        "\n{!! Form::label(\"$fieldKey\") !!}
                                   \n $htmlElement");


                    array_push($htmlFormContent, $wrappedHtml);

                }
            }

            $fileContent = str_replace("INDEX-ACTION-HERE", "{{action(\"" . ucfirst($this->tableName) . "Controller@index\")}}", $fileContent);
            $fileContent = str_replace("TemplateController",
                ucfirst($this->tableName) . "Controller", $fileContent);

            $fileContent = str_replace("{{--TYPE-HERE--}}",
                ucfirst($this->tableName), $fileContent);

            $fileContent = str_replace("{{--FORM-CONTENT-HERE--}}",
                implode("\n", $htmlFormContent), $fileContent);

        } catch (\Exception $e) {
            $this->command->error($e->getMessage());
        }
        file_put_contents($file_path, $fileContent);
    }
}