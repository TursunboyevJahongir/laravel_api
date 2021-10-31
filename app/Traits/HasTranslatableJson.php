<?php

namespace App\Traits;

trait HasTranslatableJson
{
    function getTranslation($column, $locale)
    {
        return json_decode($this->attributes[$column], true)[$locale];
    }

    function setTranslation($column, $locale, $translation)
    {
        $translations = json_decode($this->attributes[$column], true);
        $translations[$locale] = $translation;
        $this->attributes[$column] = json_encode($translations);
    }

    function setTranslations($column, $translations)
    {
        $this->attributes[$column] = json_encode($translations);
    }
}
