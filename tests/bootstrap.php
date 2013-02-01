<?php

// Simple "AngryBytes" Namespace autoloader
spl_autoload_register(function($className) {

    // Not-AngryBytes
    if (substr($className, 0, 11) !== 'AngryBytes\\') {
        return false;
    }

    // Transliterate and load directly
    if (substr($className, 0, 21) === 'AngryBytes\Hash\Test\\') {
        $fileName = __DIR__ . '/' . str_replace(
            '\\',
            DIRECTORY_SEPARATOR,
            $className
        ) . '.php';
    } else {
        $fileName = __DIR__ . '/../src/' . str_replace(
            '\\',
            DIRECTORY_SEPARATOR,
            $className
        ) . '.php';
    }


    if (!file_exists($fileName)) {
        return false;
    }

    require $fileName;
});
