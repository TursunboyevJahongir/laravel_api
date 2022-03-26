<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class SubText
{
    public static function sub($text, $length = 15, $end = '...')
    {
        return $text ? Str::limit($text, $length, $end) : null;
    }
}
