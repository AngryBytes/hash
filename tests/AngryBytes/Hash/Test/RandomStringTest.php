<?php
/**
 * RandomStringTest.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash\Test;

use AngryBytes\Hash\Test\TestCase;

use AngryBytes\Hash\RandomString;

/**
 * RandomStringTest
 *
 * Test the random string generator
 *
 * @category        AngryBytes
 * @package         Hash
 * @subpackage      Test
 */
class RandomStringTest extends TestCase
{
    /**
     * Test the length of bytes returned
     *
     * @return void
     **/
    public function testNumberOfBytes()
    {
        foreach(array(1, 2, 5, 9, 12, 20) as $numberOfBytes) {
            $bytes = RandomString::generateBytes($numberOfBytes);

            $this->assertEquals(
                strlen($bytes),
                $numberOfBytes,
                'Number of bytes returned should be ' . $numberOfBytes
            );
        }
    }
}
