<?php

namespace App\Helpers;

class RefreshTokenGenerator
{
    public static function tokenGenerate()
    {
        $token = generateRandomString(50);
        !ExistsCheck::check('refresh_tokens', 'refresh_token', $token, false) ?: self::tokenGenerate();

        return $token;
    }
}
