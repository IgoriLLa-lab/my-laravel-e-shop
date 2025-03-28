<?php

namespace App\Enum\Api;

enum ApiErrorCode: string
{
    case TOKEN_EXPIRED = 'token_expired';
    case TOKEN_INVALID = 'token_invalid';
    case TOKEN_REFRESH_FAILED = 'token_refresh_failed';
    case CREDENTIALS_INVALID = 'credentials_invalid';

    public function toString(): string
    {
        return match ($this) {
            self::TOKEN_EXPIRED => 'Token expired',
            self::TOKEN_INVALID => 'Token invalid',
            self::CREDENTIALS_INVALID => 'Credentials invalid',
            self::TOKEN_REFRESH_FAILED => 'Token refresh failed'
        };
    }

//    public function toCode(): string
//    {
//        return match ($this) {
//            self::TOKEN_EXPIRED => 204,
//            self::TOKEN_INVALID => 400,
//            self::CREDENTIALS_INVALID => 401,
//        }
//    }
}
