<?php

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\HMAC;

/**
 * Test the HMAC hasher
 */
class HMACTest extends TestCase
{
    /**
     * The HMAC secret
     */
    private string $secret = '909b96914de6866224f70f52a13e9fa6t';

    /**
     * Test creation of HMAC hashes
     */
    public function testHmac(): void
    {
        $hasher = $this->createHasher();

        $this->assertEquals(
            '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812' .
            'f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
            $hasher->hmac($this->secret, 'my message')
        );

        $this->assertNotEquals(
            '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812' .
            'f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
            $hasher->hmac('foo', 'my message')
        );

        $this->assertNotEquals(
            '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812' .
            'f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
            $hasher->hmac($this->secret, 'some other message')
        );
    }

    /**
     * Test validation of HMAC hashes
     */
    public function testValidateHmac(): void
    {
        $hasher = $this->createHasher();

        $this->assertTrue(
            $hasher->validHmac(
                'my message',
                '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812' .
                'f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
                $this->secret
            )
        );

        $this->assertFalse(
            $hasher->validHmac(
                'my message',
                '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812' .
                'f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
                'foo'
            )
        );

        $this->assertFalse(
            $hasher->validHmac(
                'some other message',
                '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812' .
                'f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
                $this->secret
            )
        );
    }

    /**
     * Create hasher
     */
    private function createHasher(): HMAC
    {
        return new HMAC('sha512');
    }
}
