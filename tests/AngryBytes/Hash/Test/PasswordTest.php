<?php

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Hash;
use AngryBytes\Hash\Hasher\Password as PasswordHasher;

/**
 * Test the password hasher
 */
class PasswordTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test password hash creation
     */
    public function testHash(): void
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
    public function testStringVerify(): void
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
    public function testRehash(): void
    {
        $hasher = $this->createHasher();
        $impl = $hasher->getHasher();
        assert($impl instanceof PasswordHasher);

        // Create hash
        $hash = $hasher->hash('foo');

        $this->assertFalse(
            $impl->needsRehash($hash)
        );

        // Adjust the hash cost, this should require a rehash
        $impl->setCost(15);

        $this->assertTrue(
            $impl->needsRehash($hash)
        );
    }

    /**
     * Test invalid cost factor
     */
    public function testCostTooLow(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $hasher = $this->createHasher();
        $impl = $hasher->getHasher();
        assert($impl instanceof PasswordHasher);

        $impl->setCost(2);
    }

    /**
     * Test invalid cost factor
     */
    public function testCostTooHigh(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $hasher = $this->createHasher();
        $impl = $hasher->getHasher();
        assert($impl instanceof PasswordHasher);

        $impl->setCost(42);
    }

    /**
     * Create hasher
     */
    private function createHasher(): Hash
    {
        return new Hash(
            new PasswordHasher()
        );
    }
}
