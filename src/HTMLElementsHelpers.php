<?php
/**
 * Created by PhpStorm.
 * User: Web Dev
 * Date: 2/7/2018
 * Time: 1:22 PM
 */

namespace elmokna\laravelParseScaffolder;


use elmokna\laravelParseScaffolder\enums\Templates;

/*
    This class is responsible for Returning html classes according to the specified template
*/

class HTMLElementsHelpers
{
    public static function getFormInputElementClasses(String $template)
    {
        switch ($template) {
            case Templates::METRONIC:
                return "form-control m-input";
                break;
            case Templates::NONE:
            default:
                return "";
                break;
        }
    }

    public static function getLabelElementClasses(String $template)
    {
        return static::getFormInputElementClasses($template);
    }

    public static function getNumberInputElementClasses(String $template)
    {
        return static::getFormInputElementClasses($template);
    }

    public static function getSelectInputElementClasses(String $template)
    {
        return static::getFormInputElementClasses($template);
    }

    public static function getTextInputElementClasses(String $template)
    {
        return static::getFormInputElementClasses($template);
    }

    public static function getFileInputElementClasses(String $template)
    {
        switch ($template) {
            case Templates::METRONIC:
                return "custom-file-input";
                break;
            case Templates::NONE:
            default:
                return "";
                break;
        }
    }

    public static function getFileInputElement(String $template, String $fieldKey)
    {
        switch ($template) {
            case Templates::METRONIC:
                return "<div class='custom-file'>{!!Form::file(\"$fieldKey\",[\"class\"=>\"" . static::getFileInputElementClasses($template) . "\"])!!} <label class=\"custom-file-label selected\" for=\"$fieldKey\"></label></div>";
                break;
            case Templates::NONE:
            default:
                return "{!!Form::file(\"$fieldKey\")!!}";
                break;
        }
    }

    public static function encapsulateDate(String $template, $HtmlBody)
    {
        switch ($template) {
            case Templates::METRONIC:
                return "<div class=\"input-group date\">"
                    . $HtmlBody .
                    "<div class=\"input-group-append\">
													<span class=\"input-group-text\">
														<i class=\"la la-calendar\"></i>
													</span>
												</div>
											</div>";
                break;
            case Templates::NONE:
            default:
                return "$HtmlBody";
                break;
        }
    }

    public static function encapsulateHTML(String $template, $HtmlBody)
    {

        $wrapperHtml = "";

        switch ($template) {
            case Templates::METRONIC:
                $wrapperHtml = "<div class=\"form-group m-form__group\">";
                /*$wrapperHtml .= "\n<div class=\"row\">";
                $wrapperHtml .= "\n<div class=\"col-xs-6\">";*/

                $wrapperHtml .= $HtmlBody;

                /*$wrapperHtml .= "\n</div>";
                $wrapperHtml .= "\n</div>";*/
                $wrapperHtml .= "\n</div>";
                break;
            case Templates::NONE:
            default:
                $wrapperHtml .= $HtmlBody;
                break;
        }

        return $wrapperHtml;
    }
}