<?php
/**
 * Created by PhpStorm.
 * User: Ha Anh Son
 * Date: 15/01/2015
 * Time: 5:45 CH
 */
use Phalcon\Mvc\Model\Validator,
    Phalcon\Mvc\Model\ValidatorInterface;

class CustomValidator extends Validator implements ValidatorInterface
{

    public function validate($model)
    {
        $field = $this->getOption('field');

        $min = $this->getOption('min');
        $max = $this->getOption('max');

        $value = $model->$field;

        if ($min <= $value && $value <= $max) {
            $this->appendMessage(
                "The field doesn't have the right range of values",
                $field,
                "CustomValidator"
            );
            return false;
        }
        return true;
    }

}