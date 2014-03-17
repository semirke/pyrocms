<?php
/**
 * This document tells a sad story of doing gross things to make CodeIgniter 
 * flexible enough for PyroCMS to do what it needs to do. 
 * 
 * Everything in here is deprecated and will hopefully die during or after 3.x.
 */

    
/*
 * Should PyroCMS run as a demo, meaning no destructive actions
 * can be taken such as removing admins or changing passwords?
 */
define('PYRO_DEMO', (file_exists(FCPATH.'DEMO')));

/*
 * Are we running as SSL or not? Laravel knows this, so we should kill this off.
 */
define('IS_SECURE', (string) (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'));

// Base URL (keeps this crazy sh*t out of the config.php
if (isset($_SERVER['HTTP_HOST'])) {
    $base_url = (IS_SECURE ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST']
        . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    // Base URI (It's different to base URL!)
    $base_uri = parse_url($base_url, PHP_URL_PATH);
    if (substr($base_uri, 0, 1) != '/') {
        $base_uri = '/' . $base_uri;
    }
    if (substr($base_uri, -1, 1) != '/') {
        $base_uri .= '/';
    }
} else {
    $base_url = 'http://localhost/';
    $base_uri = '/';
}

// Define these values to be used later on
define('BASE_URL', $base_url);
define('BASE_URI', $base_uri);
define('APPPATH_URI', BASE_URI.APPPATH);

// We dont need these variables any more
unset($base_uri, $base_url);

/*
|--------------------------------------------------------------------------
| PyroCMS Version
|--------------------------------------------------------------------------
|
| Which version of PyroCMS is currently running?
|
*/

define('CMS_VERSION', '3.0-dev');

/*
|--------------------------------------------------------------------------
| PyroCMS Edition
|--------------------------------------------------------------------------
|
| Community or Professional?
|
*/

define('CMS_EDITION', 'Community');

/*
|--------------------------------------------------------------------------
| PyroCMS Release Date
|--------------------------------------------------------------------------
|
| When was the current version of PyroCMS released?
|
*/

define('CMS_DATE', '12/20/2012');
