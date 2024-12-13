<?php

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\Hasher\Blowfish as BlowfishHasher;

/**
 * Test the blowfish hasher
 */
class BlowfishTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test simple string hashing
     */
    public function testString(): void
    {
        $hasher = $this->createHasher();

        // Simple string
        $this->assertEquals(
            '$2y$15$aa5c57dda7634fc90a92duQSfz3E1u39Z6s63i6l5QpvgJK5tSKri',
            $hasher->hash('foo')
        );
        $this->assertNotEquals(
            '$2y$15$aa5c57dda7634fc90a92duQSfz3E1u39Z6s63i6l5QpvgJK5tSKri',
            $hasher->hash('bar')
        );
    }

    /**
     * Test complex serialized data hashing
     */
    public function testSerialized(): void
    {
        $hasher = $this->createHasher();

        // Complex data
        $data = [
            new \stdClass(),
            ['foo', 'bar'],
            12345,
        ];
        $this->assertEquals(
            '$2y$15$aa5c57dda7634fc90a92duDv2OoNSn8R.p3.GSoaEZd6/vdiiq9lG',
            $hasher->hash($data)
        );

        // Append to data
        $data[] = 'foo';

        // Should no longer match
        $this->assertNotEquals(
            '$2y$15$aa5c57dda7634fc90a92duDv2OoNSn8R.p3.GSoaEZd6/vdiiq9lG',
            $hasher->hash($data)
        );
    }

    /**
     * Test verification of string hashes
     */
    public function testStringVerify(): void
    {
        $hasher = $this->createHasher();

        $this->assertTrue(
            $hasher->verify('foo', '$2y$15$aa5c57dda7634fc90a92duQSfz3E1u39Z6s63i6l5QpvgJK5tSKri')
        );

        $this->assertFalse(
            $hasher->verify('bar', '$2y$15$aa5c57dda7634fc90a92duQSfz3E1u39Z6s63i6l5QpvgJK5tSKri')
        );
    }

    /**
     * Test verification of object hashes
     */
    public function testObjectVerify(): void
    {
        $hasher = $this->createHasher();

        // Complex data
        $data = [
            new \stdClass(),
            ['foo', 'bar'],
            12345,
        ];

        $this->assertTrue(
            $hasher->verify($data, '$2y$15$aa5c57dda7634fc90a92duDv2OoNSn8R.p3.GSoaEZd6/vdiiq9lG')
        );

        // Append to data
        $data[] = 'foo';

        $this->assertFalse(
            $hasher->verify($data, '$2y$15$aa5c57dda7634fc90a92duDv2OoNSn8R.p3.GSoaEZd6/vdiiq9lG')
        );
    }

    /**
     * Test invalid work factor
     */
    public function testWorkFactorTooLow(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $hasher = $this->createHasher();
        $impl = $hasher->getHasher();
        assert($impl instanceof BlowfishHasher);

        $impl->setWorkFactor(3);
    }

    /**
     * Test invalid work factor
     */
    public function testWorkFactorTooHigh(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $hasher = $this->createHasher();
        $impl = $hasher->getHasher();
        assert($impl instanceof BlowfishHasher);

        $impl->setWorkFactor(32);
    }

    /**
     * Test work factor alteration
     */
    public function testWorkFactor(): void
    {
        $hasher = $this->createHasher();
        $impl = $hasher->getHasher();
        assert($impl instanceof BlowfishHasher);

        $impl->setWorkFactor(5);

        // Simple string
        $this->assertEquals(
            '$2y$05$aa5c57dda7634fc90a92duCIqZ6agXYH9mOnF/It6sfh3MAJAkKXe',
            $hasher->hash('foo')
        );

        $impl->setWorkFactor(10);
        $this->assertEquals(
            '$2y$10$aa5c57dda7634fc90a92duoe.XRVTsrN1oW9P.qnaa.W0BGQ9olPy',
            $hasher->hash('foo')
        );
    }

    /**
     * Test salting
     */
    public function testSalt(): void
    {
        $hasher = $this->createHasher();
        $impl = $hasher->getHasher();
        assert($impl instanceof BlowfishHasher);

        $impl->setWorkFactor(5);

        // Test salt with 22 valid characters
        $this->assertEquals(
            // Pre-generated hash outcome for password 'foo' and given salt
            '$2y$05$./A1aaaaaaaaaaaaaaaaaOZW9OJaO6Alj4.ZDbOi6Jrbn.bGZfYRK',
            $impl->hash(
                'foo',
                ['salt' => './A1aaaaaaaaaaaaaaaaaa']
            )
        );

        // Test salt with less invalid characters
        $this->assertEquals(
            // Pre-generated hash outcome for password 'foo' and given salt (md5'ed)
            '$2y$05$ceb20772e0c9d240c75ebugm2AOmnuR5.LsdpDZGAjkE1DupDTPFW',
            $impl->hash(
                'foo',
                ['salt' => 'salt']
            )
        );
    }

    /**
     * Create hasher
     */
    private function createHasher(): Hash
    {
        return new Hash(
            new BlowfishHasher(),
            '909b96914de6866224f70f52a13e9fa6'
        );
    }
}
