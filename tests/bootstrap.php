<?php

// Simple "Abc" Namespace autoloader
spl_autoload_register(function($className) {

    // Not-ABC
    if (substr($className, 0, 4) !== 'Abc\\') {
        return false;
    }

    // Transliterate and load directly
    if (substr($className, 0, 14) === 'Abc\Hash\Test\\') {
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
