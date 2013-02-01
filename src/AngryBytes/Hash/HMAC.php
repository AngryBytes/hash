<?php
/**
 * HMAC.php
 *
 * @category        AngryBytes
 * @package         Hash
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

/**
 * HMAC
 *
 * HMAC creator
 *
 * @category        AngryBytes
 * @package         Hash
 */
class HMAC
{
    /**
     * Method to use
     *
     * @var string
     **/
    private $method;

    /**
     * Constructor
     *
     * @param string $algorithm
     * @return void
     **/
    public function __construct($algorithm)
    {
        $this->setAlgorithm($algorithm);
    }

    /**
     * Get the method to use
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set the method to use
     *
     * @param string $method
     * @return HMAC
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Create an HMAC
     *
     * @param string $sharedSecret
     * @param mixed $what1
     * @param mixed $what2
     * ...
     * @param mixed $whatN
     * @return string
     **/
    public function hmac($sharedSecret)
    {
        // Get data as a string of passed args
        $args = func_get_args();
        array_shift($args);
        $data = serialize($args);

        return hash_hmac('md5', $data, $sharedSecret);
    }
}
