<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * PyroCMS URL Helpers
 *
 * This overrides Codeigniter's helpers/url_helper.php file.
 *
 * @package   PyroCMS\Core\Helpers
 * @author    PyroCMS Dev Team
 * @copyright Copyright (c) 2012, PyroCMS LLC
 */

if (!function_exists('index_uri')) {

    /**
     * Return the index URI of the current route
     * @return string
     */
    function index_uri()
    {
        if (strpos(uri_string(), ci()->router->method) !== false) {
            return trim(substr(uri_string(), 0, strpos(uri_string(), ci()->router->method)), '/');
        }

        return uri_string();
    }
}

if (!function_exists('referer')) {

    /**
     * Return the HTTP_REFERER
     * @return string
     */
    function referer($fallback = null)
    {
        if (!$fallback) $fallback = index_uri();

        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $fallback;
    }
}
