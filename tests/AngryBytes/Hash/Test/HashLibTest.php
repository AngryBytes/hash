<?php

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Hash;

/**
 * Test the Hash Lib
 */
class HashLibTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test new hasher with valid salt
     *
     * @doesNotPerformAssertions
     */
    public function testValidSalt(): void
    {
        new Hash(
            new \AngryBytes\Hash\Hasher\MD5()
        );

        new Hash(
            new \AngryBytes\Hash\Hasher\MD5(),
            str_repeat('a', 20)
        );
    }

    /**
     * Test new hasher with salt set too short
     */
    public function testSaltTooShort(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Hash(
            new \AngryBytes\Hash\Hasher\MD5(),
            str_repeat('a', 19)
        );
    }

    /**
     * Test new hasher with salt set too long
     */
    public function testSaltTooLong(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Hash(
            new \AngryBytes\Hash\Hasher\MD5(),
            str_repeat('a', CRYPT_SALT_LENGTH + 1)
        );
    }
}
