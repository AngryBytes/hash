<?php
/**
 * HMAC.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @copyright       Copyright (c) 2007-2016 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\Hash;

use \InvalidArgumentException;

/**
 * HMAC
 *
 * HMAC creator
 *
 * This class will generate hashes to be used as HMAC
 *
 * @category        AngryBytes
 * @package         Hash
 */
class HMAC
{
    /**
     * Algorithm to use
     *
     * @var string
     **/
    private $algorithm;

    /**
     * Constructor
     *
     * @param string $algorithm
     **/
    public function __construct($algorithm)
    {
        $this->setAlgorithm($algorithm);
    }

    /**
     * Does this platform support an algorithm?
     *
     * @return bool
     **/
    public static function platformSupports($algorithm)
    {
        return in_array($algorithm, hash_algos());
    }

    /**
     * Get the algorithm to use
     *
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Set the algorithm to use
     *
     * @param  string $algorithm
     * @return HMAC
     */
    public function setAlgorithm($algorithm)
    {
        // Sanity check
        if (!self::platformSupports($algorithm)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a supported hash algorithm on this platform'
            ));
        }

        $this->algorithm = $algorithm;

        return $this;
    }

    /**
     * Create an HMAC
     *
     * This method accepts multiple variables as input, but is restricted to
     * strings. All input will be concatenated before hashing.
     *
     * @param  string $sharedSecret
     * @param  mixed  $what1
     * @param  mixed  $what2
     * ...
     * @param  mixed  $whatN
     * @return string
     **/
    public function hmac($sharedSecret)
    {
        // Get data as a string of passed args
        $args = func_get_args();

        // Shift the shared secret off
        array_shift($args);

        // Get the data concatenated
        $data = '';
        foreach ($args as $index => $arg) {

            // Sanity check
            if (!is_string($arg)) {
                throw new InvalidArgumentException(sprintf(
                    'Received a non-string argument at "%s"',
                    $index
                ));
            }

            $data .= $arg;
        }

        return hash_hmac(
            $this->getAlgorithm(),
            $data,
            $sharedSecret
        );
    }

    /**
     * Check if a (received) message has a valid HMAC
     *
     * @param  string $message
     * @param  string $hmac
     * @param  string $sharedSecret
     * @return bool
     **/
    public function validHmac($message, $hmac, $sharedSecret)
    {
        // The HMAC as it should be for our shared secret
        $shouldHave = $this->hmac($sharedSecret, $message);

        // Compare
        return Hash::compare($hmac, $shouldHave);
    }
}
