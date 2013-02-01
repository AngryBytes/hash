<?php
/**
 * MD5Test.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Test\TestCase;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\Hasher\MD5 as MD5Hasher;

/**
 * MD5Test
 *
 * Testing md5 hasher
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 */
class MD5Test extends TestCase
{
    /**
     * Test hashing of strings
     *
     * @return void
     **/
    public function testHashString()
    {
        $hasher = $this->createHasher();

        $this->assertEquals(
            '4dc664001bbbbf88d2b59eeda6855a6b',
            $hasher->hash('foo')
        );

        $this->assertEquals(
            'b5ab8f853032ce68de22035736209e75',
            $hasher->hash('bar')
        );
    }

    /**
     * Test hashing of complex data
     *
     * @return void
     **/
    public function testHashObject()
    {
        $hasher = $this->createHasher();

        $obj = new \stdClass;
        $obj->foo = 'bar';

        $this->assertEquals(
            '8ff1610fc9c2607f7d8e9175080f7311',
            $hasher->hash($obj)
        );

        $obj->bar = 'foo';

        $this->assertEquals(
            '458ee16d8b79287fb8cf8700469cc634',
            $hasher->hash($obj)
        );
    }

    /**
     * Create hasher
     *
     * @return Hash
     **/
    private function createHasher()
    {
        // Hasher
        return new Hash(
            new MD5Hasher,
            '909b96914de6866224f70f52a13e9fa6'
        );
    }
}
