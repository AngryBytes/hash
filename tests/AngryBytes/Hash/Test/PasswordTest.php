<?php
/**
 * PasswordTest.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 * @copyright       Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\Hasher\Password as PasswordHasher;

/**
 * PasswordTest
 *
 * Testing password hasher
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 */
class PasswordTest extends TestCase
{
    /**
     * Test password hash creation
     */
    public function testHash()
    {
        $hasher = $this->createHasher();

        // Even though the password are equal the hashes should not be
        $this->assertNotEquals(
            $hasher->hash('foo'),
            $hasher->hash('foo')
        );
    }

    /**
     * Test password hash verification
     */
    public function testStringVerify()
    {
        $hasher = $this->createHasher();

        $this->assertTrue(
            $hasher->verify('foo', '$2y$10$Ch4CoMWaM6KR0ZfC3ZKvc.FAW1U30luCURlyahm/XgzJ4TWDwWPFW')
        );

        $this->assertFalse(
            $hasher->verify('bar', '$2y$10$Ch4CoMWaM6KR0ZfC3ZKvc.FAW1U30luCURlyahm/XgzJ4TWDwWPFW')
        );
    }

    /**
     * Test password hash rehash
     */
    public function testRehash()
    {
        $hasher = $this->createHasher();

        // Create hash
        $hash = $hasher->hash('foo');

        $this->assertFalse(
            $hasher->getHasher()->needsRehash($hash)
        );

        // Adjust the hash cost, this should require a rehash
        $hasher->getHasher()->setCost(15);

        $this->assertTrue(
            $hasher->getHasher()->needsRehash($hash)
        );
    }

    /**
     * Test invalid cost factor
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCostTooLow()
    {
        $hasher = $this->createHasher();

        $hasher->getHasher()->setCost(2);
    }

    /**
     * Test invalid cost factor
     *
     * @expectedException \InvalidArgumentException
     */
    public function testCostTooHigh()
    {
        $hasher = $this->createHasher();

        $hasher->getHasher()->setCost(42);
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
            new PasswordHasher
        );
    }
}

