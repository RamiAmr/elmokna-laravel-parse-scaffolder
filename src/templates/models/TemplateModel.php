<?php

namespace App\Http;

use Parse\ParseException;
use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseGeoPoint;
use Parse\ParseFile;

class TemplateModel extends ParseObject
{
    public static $parseClassName = "CLASS-NAME-HERE";


    public function __construct(String $objectId = null, bool $isPointer = false)
    {
        try {
            parent::__construct(static::$parseClassName, $objectId, $isPointer);
            try {
                $this->fetch();
            } catch (\Exception $e) {

            }
        } catch (\Exception $e) {
            logger($e->getMessage(), [__METHOD__, $this->getClassName()]);
        }
    }

    /*SETTERS-HERE*/

    /*GETTERS-HERE*/

    public static function QueryAll()
    {
        $query = new ParseQuery(static::$parseClassName);
        /*INCLUDES-HERE*/
        $parseArray = $query->find();
        $json_data = array();
        $json_data["data"] = array();
        if (count($parseArray)) {
            $json_data["status_code"] = 200;
            $json_data["count"] = count($parseArray);

            foreach ($parseArray as $parseObject) {
                array_push($json_data["data"], self::toJson($parseObject));
            }
            return $json_data;
        } else {
            return [
                "status_code" => 204,
                $json_data["count"] = 0
            ];
        }
    }

    public static function QueryGet(String $objectId)
    {
        $query = new ParseQuery(static::$parseClassName);

        /*INCLUDES-HERE*/
        try {
            $parseObject = $query->get($objectId);
            $json_item = array();
            $json_item["data"] = array();
            if (count($parseObject)) {
                $json_item["status_code"] = 200;
                $json_item["count"] = count($parseObject);

                $json_item["data"]= self::toJson($parseObject);
                return $json_item;
            } else {
                return [
                    "status_code" => 204,
                    $json_item["count"] = 0
                ];
            }

        } catch (ParseException $e) {
            return null;
        }
    }

    public static function toJson(ParseObject $parseObject)
    {
        try {
            $Json_data = $parseObject->getAllKeys();
            $Json_data['objectId'] = $parseObject->getObjectId();
            $Json_data['createdAt'] = $parseObject->getCreatedAt()->format('d-m-Y');
            $Json_data['updatedAt'] = $parseObject->getUpdatedAt()->format('d-m-Y');
            $Json_data['type'] = static::$parseClassName;
            return $Json_data;

        } catch (\Exception $e) {
            return [
                "status_code" => 404
            ];
        }
    }
}
