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
     * @param string $algorithm
     * @return bool
     **/
    public static function platformSupportsAlgorithm($algorithm)
    {
        return in_array($algorithm, hash_algos());
    }

    /**
     * Create an HMAC
     *
     * This method accepts multiple variables as input, but is restricted to
     * strings. All input will be concatenated before hashing.
     *
     * @param  string $sharedSecret
     * @param array $args
     * @return string
     */
    public function hmac($sharedSecret, ...$args)
    {
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
            $this->algorithm,
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
        // Compare HMAC with received message
        return Hash::compare(
            $hmac,
            // The HMAC as it should be for our shared secret
            $this->hmac($sharedSecret, $message)
        );
    }

    /**
     * Set the algorithm to use
     *
     * @param  string $algorithm
     * @return HMAC
     */
    protected function setAlgorithm($algorithm)
    {
        // Sanity check
        if (!self::platformSupportsAlgorithm($algorithm)) {
            throw new InvalidArgumentException(sprintf(
                '"%s" is not a supported hash algorithm on this platform'
            ));
        }

        $this->algorithm = $algorithm;

        return $this;
    }
}
