<?php

namespace thecore\phpmvc\database;

use thecore\phpmvc\Model;
use thecore\phpmvc\Application;


abstract class DbModel extends Model {
    abstract public static function tableName(): string;
    abstract public function attributes(): array;
    abstract public static function primaryKey(): string;

    public function save() {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $implodeAttributes = implode(',', $attributes);
        $implodeParams = implode(',', $params);
        $stmt = self::prepare("INSERT INTO $tableName ($implodeAttributes) VALUES($implodeParams);");
        foreach($attributes as $attribute) {
            $stmt->bindValue(":$attribute", $this->{$attribute}['value']);
        }
        $stmt->execute();
        return true;
    }

    public static function prepare($sql) {
        return Application::$app->database->pdo->prepare($sql);
    }

    public static function findOne($where) {
        $tableName = static::tableName(); 
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();

        return  $stmt->fetchObject(static::class); //static class will get the class instance whenver the findOne was call
    }
}