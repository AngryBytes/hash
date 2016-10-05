<?php
/**
 * HMACTest.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 * @copyright       Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\HMAC;

/**
 * Test the HMAC hasher
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 */
class HMACTest extends TestCase
{
    /**
     * The HMAC secret
     *
     * @var string
     */
    private $secret = '909b96914de6866224f70f52a13e9fa6t';

    /**
     * Test creation of HMAC hashes
     */
    public function testHmac()
    {
        $hasher = $this->createHasher();

        $this->assertEquals(
            '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
            $hasher->hmac($this->secret, 'my message')

        );

        $this->assertNotEquals(
            '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
            $hasher->hmac('foo', 'my message')
        );

        $this->assertNotEquals(
            '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
            $hasher->hmac($this->secret, 'some other message')
        );
    }

    /**
     * Test validation of HMAC hashes
     */
    public function testValidateHmac()
    {
        $hasher = $this->createHasher();

        $this->assertTrue(
            $hasher->validHmac(
                'my message',
                '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
                $this->secret
            )
        );

        $this->assertFalse(
            $hasher->validHmac(
                'my message',
                '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
                'foo'
            )
        );

        $this->assertFalse(
            $hasher->validHmac(
                'some other message',
                '304692bc0cd231dc337107a7964752858a78140e9696b1ffc2e9a646961ed812f890395480b7c2e8650d55c7d0b0bfe4551b5ce52fae0d73ee5e2c0b0609e164',
                $this->secret
            )
        );
    }

    /**
     * Create hasher
     *
     * @return HMAC
     */
    private function createHasher()
    {
        return new HMAC('sha512');
    }
}

