<?php
namespace App\Utils;

use Exception;

/**
 * Class Strings
 *
 * @package App\Utils
 */
final class Strings
{
    /**
     * @throws Exception
     * @return string
     */
    public static function generateToken(): string
    {
        // TODO: implement something more serious
        return  rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
