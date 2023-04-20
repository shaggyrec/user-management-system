<?php

namespace Unit\Utils;

use App\Utils\Strings;
use PHPUnit\Framework\TestCase;

class StringsTest extends TestCase
{
    public function testGenerateToken(): void
    {
        $token = Strings::generateToken();

        // Check if the token is a string
        $this->assertIsString($token);

        // Check if the token has the correct length
        $this->assertEquals(43, strlen($token));

        // Check if the token has a valid format
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9\-_]+$/', $token);
    }
}
