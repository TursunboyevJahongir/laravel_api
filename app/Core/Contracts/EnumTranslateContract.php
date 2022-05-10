<?php

namespace App\Core\Contracts;

interface EnumTranslateContract
{
    //get translate of values. every value on 3 languages with key
    public static function getTranslateValues(): array;

    public static function getTranslateValuesByKey(string $key);

    public static function getTranslate(string $key, string $lang = null): string;
}
