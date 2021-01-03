<?php 

namespace app\core\form;
use app\core\Model;
use app\core\form\BaseField;

class InputField extends BaseField {

    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_number = 'number';
    public const TYPE_SELECT = 'select';
    public string $optionValues = '';
    public string $type;

    public function __construct(Model $model, string $attribute) {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function passwordField() {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
    
 
    public function renderInput(): string
    {
        return sprintf('<input type="%s" class="form-control %s" name="%s" value="%s" id="%s">',
        $this->type,
        $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        $this->attribute,
        $this->model->{$this->attribute},
        $this->attribute,
        );
    }
}