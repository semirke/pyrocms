<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Extends the CodeIgniter Config class
 *
 * @author      PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 * @package     PyroCMS\Core\Libraries
 */
class MY_Config extends MX_Config
{
    /**
     * Modified CI_Config::site_url() to stop double extensions eg: .rss.html
     *
     * @param string $uri the URI string
     * @return string
     */
    public function site_url($uri = '')
    {
        if (is_array($uri)) {
            $uri = implode('/', $uri);
        }

        if ($uri == '') {
            return $this->slash_item('base_url').$this->item('index_page');
        } else {
            // -- Original --
            // This would ALWAYS jam the url_suffix on there, and not let you use other file 
            // extensions, which didn't make .rss possible for example
            //$suffix = ($this->item('url_suffix') == false) ? '' : $this->item('url_suffix');

            // -- Hot newness
            if (strpos($uri, '|') > 0) {
                // Split the pipe
                list($uri, $suffix) = explode('|', $uri);

                // Dont forget the period
                $suffix = '.'.$suffix;
            } else {
                $suffix = ($this->item('url_suffix') == false) ? '' : $this->item('url_suffix');
            }
            // -- end host newness

            return $this->slash_item('base_url').$this->slash_item('index_page').preg_replace("|^/*(.+?)/*$|", "\\1", $uri).$suffix;
        }
    }

    /**
     * Set a config file item
     *
     * @param string $item the config item key
     * @param string $value the config item value
     * @param string $index
     */
    public function set_item($item, $value, $index = '')
    {
        if ($index == '') {
            $this->config[$item] = $value;
        } else {
            $this->config[$index][$item] = $value;
        }
    }
}
