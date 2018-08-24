<?php

/**
 * Check PHP prerequisites and load common variables, libraries, classes 
 * and functions necessary for php-login script to work properly.
 */

// Tell PHP that we're using UTF-8 strings until the end of the script
mb_internal_encoding('UTF-8');
 
// Tell PHP that we'll be outputting UTF-8 to the browser
mb_http_output('UTF-8');

// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once('libraries/password_compatibility_library.php');
}
// include the config
require_once('config/config.php');

// include the to-be-used language. feel free to translate your project and include something else.
// detection of the language for the current user/browser
$user_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
// if translation file for the detected language doesn't exist, we use default english file
require_once('translations/' . (file_exists('translations/' . $user_lang . '.php') ? $user_lang : 'en') . '.php');

/**
 * PHPLogin SPL autoloader.
 * This auto-loading function will be called every time a class is used but not yet loaded.
 * Login, Registration or PHPMailer classes are loaded only when they are really needed.
 * Example: The first time that "new Login();" is called, the corresponding php file is loaded.
 * @param string $classname The name of the class to load
 */
function PHPLoginAutoload($classname)
{
    // try to load the file from the "classes" directory
    $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $classname . '.php';
    if (is_readable($filename)) {
        require $filename;
    } else {
        // try to load the file from the "libraries" directory
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . $classname . '.php';
        if (is_readable($filename)) {
            require $filename;
        // file cannot be found
        } else {
            exit('Unable to find the file ' . $classname . '.php');
        }
    }
}

// spl_autoload_register defines the function to be called when a class is not yet loaded.
spl_autoload_register('PHPLoginAutoload');
