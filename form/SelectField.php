<?php 
namespace thecore\phpmvc\form;
use thecore\phpmvc\form\BaseField;

class SelectField extends BaseField {

    public function selectField($optionValues) {
        $html = '<option></option>';
        foreach($optionValues as $key => $value) {
            $html .= "<option value='$key'>$value</option>";
        }
        $this->optionValues = $html;
        return $this;
    }

    public function renderInput(): string
    {
        return sprintf('<select type="text" name="%s" value="%s" class="form-control %s" id="%s">%s</select>',
        $this->attribute,
        $this->model->{$this->attribute},
        $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        $this->attribute,
        $this->optionValues,
        );
    }
}