<?php

namespace AngryBytes\Hash;

/**
 * HMAC creator
 *
 * This class will generate hashes to be used as HMAC
 */
class HMAC
{
    /**
     * Algorithm to use
     */
    private string $algorithm;

    public function __construct(string $algorithm)
    {
        $this->setAlgorithm($algorithm);
    }

    /**
     * Does this platform support an algorithm?
     */
    public static function platformSupportsAlgorithm(string $algorithm): bool
    {
        return in_array($algorithm, hash_algos(), true);
    }

    /**
     * Create an HMAC
     *
     * This method accepts multiple variables as input, but is restricted to
     * strings. All inputs will be concatenated before hashing.
     *
     * @param string[] $args
     */
    public function hmac(string $sharedSecret, ...$args): string
    {
        // Get the data concatenated
        $data = '';
        /** @var string $arg */
        foreach ($args as $arg) {
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
     */
    public function validHmac(string $message, string $hmac, string $sharedSecret): bool
    {
        // Compare HMAC with a received message
        return Hash::compare(
            $hmac,
            // The HMAC as it should be for our shared secret
            $this->hmac($sharedSecret, $message)
        );
    }

    /**
     * Set the algorithm to use
     */
    protected function setAlgorithm(string $algorithm): HMAC
    {
        // Sanity check
        if (!self::platformSupportsAlgorithm($algorithm)) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" is not a supported hash algorithm on this platform',
                $algorithm
            ));
        }

        $this->algorithm = $algorithm;

        return $this;
    }
}
