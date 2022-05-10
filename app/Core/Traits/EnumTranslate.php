<?php

namespace App\Core\Traits;

trait EnumTranslate
{
    /**
     * get translate values on 3 languages with keys. uz,ru,en get from config app.available_locales
     * write translation to lang file: lang/enums.php "fileName_Key" example: "CompanyTypeEnum_CHAIN=>Сеть"
     * or
     * (example:
     * first_key => [
     *          'uz'=>'value',
     *          'ru'=>'value',
     *          'en'=>'value'
     * ],
     * second_key => [
     *          'uz'=>'value',
     *          'ru'=>'value',
     *          'en'=>'value'
     * ])
     */
    public static function getTranslateValues(): array
    {
        $translate = [];
        $constants = self::reflection()->getConstants();
        foreach ($constants as $key => $value) {
            if (trans()->has("enums." . self::reflection()->getShortName() . "_" . $key)) {
                $translate[$value] = __("enums." . self::reflection()->getShortName() . "_" . $key);
            } else {
                foreach (config('app.available_locales') as $locale) {
                    $translate[$value][$locale] = $value;
                }
            }
        }

        return $translate;
    }

    //get translate values on 3 languages
    public static function getTranslateValuesByKey(string $key)
    {
        return self::getTranslateValues()[$key];
    }

    //get translate of value
    public static function getTranslate(string $key, string $lang = null): string
    {
        return is_array(self::getTranslateValuesByKey($key))
            ? self::getTranslateValuesByKey($key)[$lang ?? app()->getLocale()]
            : self::getTranslateValuesByKey($key);
    }

    //get translate values on 3 languages
    public static function getTranslateValuesToArray()
    {
        $translate = [];
        $constants = self::reflection()->getConstants();
        foreach ($constants as $key => $value) {
            $translate[$value] = self::getTranslate($value);
        }

        return $translate;
    }
}
