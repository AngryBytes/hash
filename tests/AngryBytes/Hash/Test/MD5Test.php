<?php

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\Hasher\MD5 as MD5Hasher;

/**
 * Test the md5 hasher
 */
class MD5Test extends \PHPUnit\Framework\TestCase
{
    /**
     * Test hashing of strings
     */
    public function testHashString(): void
    {
        $hasher = $this->createHasher();

        $this->assertEquals(
            'f149d11c9f6d8e899772b855588722f2',
            $hasher->hash('foo')
        );

        $this->assertEquals(
            '7b84dc4faca7b93ea519eade24a5f634',
            $hasher->hash('bar')
        );
    }

    /**
     * Test hashing of complex data
     */
    public function testHashObject(): void
    {
        $hasher = $this->createHasher();

        $obj = new \stdClass();
        $obj->foo = 'bar';

        $this->assertEquals(
            '2f4162ad4b8774272e452efafd25972f',
            $hasher->hash($obj)
        );

        $obj->bar = 'foo';

        $this->assertEquals(
            '0d8c867eedd83575b44c62f8caac142b',
            $hasher->hash($obj)
        );
    }

    /**
     * Test verification of string hashes
     */
    public function testVerifyString(): void
    {
        $hasher = $this->createHasher();

        $this->assertTrue(
            $hasher->verify('foo', 'f149d11c9f6d8e899772b855588722f2')
        );

        $this->assertFalse(
            $hasher->verify('bar', 'f149d11c9f6d8e899772b855588722f2')
        );
    }

    /**
     * Test verification of object hashes
     */
    public function testVerifyHashObject(): void
    {
        $hasher = $this->createHasher();

        $obj = new \stdClass();
        $obj->foo = 'bar';

        $this->assertTrue(
            $hasher->verify($obj, '2f4162ad4b8774272e452efafd25972f')
        );

        $obj->bar = 'foo';

        $this->assertFalse(
            $hasher->verify($obj, '2f4162ad4b8774272e452efafd25972f')
        );
    }

    /**
     * Create hasher
     */
    private function createHasher(): Hash
    {
        return new Hash(
            new MD5Hasher(),
            '909b96914de6866224f70f52a13e9fa6'
        );
    }
}
