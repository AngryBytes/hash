<?php

namespace AngryBytes\Hash;

/**
 * Common Contract For Hashers
 */
interface HasherInterface
{
    /**
     * Hash a data string
     *
     * @param mixed[] $options Additional hasher options
     */
    public function hash(string $string, array $options = []): string;

    /**
     * Verify is the data string matches the given hash
     *
     * @param mixed[] $options Additional hasher options
     */
    public function verify(string $string, string $hash, array $options = []): bool;
}
