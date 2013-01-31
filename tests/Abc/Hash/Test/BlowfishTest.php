<?php
/**
 * BlowfishTest.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         Hash
 * @subpackage      Tests
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Abc\Hash\Test;

use Abc\Hash\Test\TestCase;

use Abc\Hash\Hash;
use Abc\Hash\Hasher\Blowfish as BlowfishHasher;

/**
 * BlowfishTest
 *
 * Testing file adapter
 *
 * @category        Abc
 * @package         Hash
 * @subpackage      Tests
 */
class BlowfishTest extends TestCase
{
    /**
     * Test hashing of blowfish
     *
     * @return void
     **/
    public function testString()
    {
        $hasher = $this->createHasher();

        // Simple string
        $this->assertEquals(
            '$2y$15$aa5c57dda7634fc90a92duWE0jEXBsxhrZrjtIDNJxqSVAgleehYW',
            $hasher->hash('foo')
        );
        $this->assertNotEquals(
            '$2y$15$aa5c57dda7634fc90a92duWE0jEXBsxhrZrjtIDNJxqSVAgleehYW',
            $hasher->hash('bar')
        );
    }

    /**
     * Test complex serialized data
     *
     * @return void
     **/
    public function testSerialized()
    {
        $hasher = $this->createHasher();

        // Complex data
        $data = array(
            new \stdClass,
            array('foo', 'bar'),
            12345
        );
        $this->assertEquals(
            '$2y$15$aa5c57dda7634fc90a92duEeKPUIy2mlag3NxVWnd1S.pWl0l6Vkq',
            $hasher->hash($data)
        );

        // Append to data
        $data[] = 'foo';

        // Should no longer match
        $this->assertNotEquals(
            '$2y$15$aa5c57dda7634fc90a92duEeKPUIy2mlag3NxVWnd1S.pWl0l6Vkq',
            $hasher->hash($data)
        );
    }

    /**
     * Test invalid work factor
     *
     * @expectedException \InvalidArgumentException
     * @return void
     **/
    public function testWorkFactorTooLow()
    {
        $hasher = $this->createHasher();

        $hasher->getHasher()->setWorkFactor(3);
    }

    /**
     * Test invalid work factor
     *
     * @expectedException \InvalidArgumentException
     * @return void
     **/
    public function testWorkFactorTooHigh()
    {
        $hasher = $this->createHasher();

        $hasher->getHasher()->setWorkFactor(32);
    }

    /**
     * Test hashing of blowfish
     *
     * @return void
     **/
    public function testWorkFactor()
    {
        $hasher = $this->createHasher();

        $hasher->getHasher()->setWorkFactor(5);

        // Simple string
        $this->assertEquals(
            '$2y$05$aa5c57dda7634fc90a92duqnvmZIAm8fd3YauHfd2Lyt.5Rlz6BsC',
            $hasher->hash('foo')
        );

        $hasher->getHasher()->setWorkFactor(10);
        $this->assertEquals(
            '$2y$10$aa5c57dda7634fc90a92duaIgY20lEZW.nomcy7J7xN3jNAn5pvge',
            $hasher->hash('foo')
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
            new BlowfishHasher,
            '909b96914de6866224f70f52a13e9fa6'
        );
    }
}

