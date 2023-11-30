<?php

namespace AngryBytes\Hash;

/**
 * A collection of hash generation and comparison methods.
 *
 * This Hash library must receive a \AngryBytes\HasherInterface compatible
 * instance to work with.
 *
 * Providing a salt is strictly optional, and should not be provided when the
 * hasher provides better salt generation methods.
 */
class Hash
{
    /** Hasher to use */
    private HasherInterface $hasher;
    /** Salt for hashing */
    private ?string $salt;

    /**
     * @param HasherInterface $hasher The hasher to be used
     * @param ?string         $salt   (optional) Omit if the hasher creates its own (better) salt
     */
    public function __construct(HasherInterface $hasher, ?string $salt = null)
    {
        $this->hasher = $hasher;

        $this->setSalt($salt);
    }

    /**
     * Dynamically pass methods to the active hasher
     *
     * @param  mixed[] $parameters
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->hasher->$method(...$parameters);
    }

    /**
     * Get the hasher to use for the actual hashing
     */
    public function getHasher(): HasherInterface
    {
        return $this->hasher;
    }

    /**
     * Generate a hash
     *
     * @param mixed[] $options Additional hasher options (the actual options depend on the registered Hasher)
     */
    public function hash(mixed $data, array $options = []): string
    {
        return $this->hasher->hash(
            self::getDataString($data),
            $this->parseHashOptions($options)
        );
    }

    /**
     * Verify if the data matches the hash
     *
     * @param mixed[] $options
     */
    public function verify(mixed $data, string $hash, array $options = []): bool
    {
        return $this->hasher->verify(
            self::getDataString($data),
            $hash,
            $this->parseHashOptions($options)
        );
    }

    /**
     * Hash something into a short hash
     *
     * This is simply a shortened version of hash(), returning a 7 character hash, which should be good
     * enough for identification purposes. Therefore, it MUST NOT be used for cryptographic purposes or
     * password storage but only to create a fast, short string to compare or identify.
     *
     * It is RECOMMENDED to only use this method with the Hasher\MD5 hasher as hashes
     * created by bcrypt/crypt() have a common beginning.
     *
     * @see Hash::hash()
     *
     * @param mixed[] $options
     */
    public function shortHash(mixed $data, array $options = []): string
    {
        return substr($this->hash($data, $options), 0, 7);
    }

    /**
     * Verify if the data matches the shortHash
     *
     * @see Hash::shortHash()
     *
     * @param mixed[] $options
     */
    public function verifyShortHash(mixed $data, string $shortHash, array $options = []): bool
    {
        return self::compare(
            $this->shortHash($data, $options),
            $shortHash
        );
    }

    /**
     * Compare two hashes
     *
     * Uses the time-save `hash_equals()` function to compare 2 hashes.
     */
    public static function compare(string $hashA, string $hashB): bool
    {
        return hash_equals($hashA, $hashB);
    }

    /**
     * Set the salt
     *
     * @return $this
     */
    protected function setSalt(?string $salt = null): self
    {
        if (is_string($salt) && (strlen($salt) < 20 || strlen($salt) > CRYPT_SALT_LENGTH)) {
            // Make sure it's of sufficient length
            throw new \InvalidArgumentException(sprintf(
                'Provided salt "%s" does not match the length requirements. ' .
                'A length between 20 en %d characters is required.',
                $salt,
                CRYPT_SALT_LENGTH
            ));
        }

        $this->salt = $salt;

        return $this;
    }

    /**
     * Get the data as a string
     *
     * Will serialize non-scalar values
     */
    private static function getDataString(mixed $data): string
    {
        if (is_scalar($data)) {
            return (string) $data;
        }

        return serialize($data);
    }

    /**
     * Merge the default and provided hash options
     *
     * Automatically sets the salt as an option when set in this
     * component.
     *
     * @param  mixed[] $options
     * @return mixed[]
     */
    private function parseHashOptions(array $options = []): array
    {
        $defaultOptions = [];

        // Pass the salt if set
        if (!is_null($this->salt)) {
            $defaultOptions['salt'] = $this->salt;
        }

        return array_merge($defaultOptions, $options);
    }
}
