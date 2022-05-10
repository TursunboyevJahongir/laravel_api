<?php

namespace App\Core\Traits;

trait EnumCasts
{
    public function get($model, $key, $value, $attributes)
    {
        $data = $value ? [$value => $this->getTranslate($value)] : null;
        if (request()->exists('enums')) {
            return $data ? $data + $this->getTranslateValuesToArray() : $this->getTranslateValuesToArray();
        }

        return $data;
    }

    //before set check if value is valid else return exception and rollback
    public function set($model, string $key, $value, array $attributes)
    {
        if (!$this->isValid($value)) {
            throw new \Exception(__('validation.in_array',
                                    ['attribute' => $value, 'other' => "$key:" . $this]), 422);
        }

        return $value;
    }
}
