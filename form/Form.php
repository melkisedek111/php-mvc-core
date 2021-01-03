<?php

namespace app\core\form;

use app\core\Model;
use app\core\form\InputField;
use app\core\form\SelectField;

class Form {
    public static function begin(string $action,string  $method) {
        echo sprintf('<form action="%s" method="%s" enctype="multipart/form-data">', $action, $method);
        return new Form();
    }

    public static function end() {
        echo '</form>';
    }

    public function field(Model $model, $attribute) {
        return new InputField($model, $attribute);
    }

    public function selectField(Model $model, $attribute) {
        return new SelectField($model, $attribute);
    }

}