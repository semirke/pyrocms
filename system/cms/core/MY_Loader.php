<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

require APPPATH."libraries/MX/Loader.php";

/**
 * This is the loader class used throughout PyroCMS.
 *
 * @author      PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 * @package 	PyroCMS\Core\Libraries
 */
class MY_Loader extends MX_Loader
{

	/**
	 * Since parent::_ci_view_paths is protected we use this setter to allow
	 * things like plugins to set a view location.
	 *
	 * @param string $path
	 */
	public function set_view_path($path)
	{
		if (is_array($path)) {
			// if we're restoring saved paths we'll do them all
			$this->_ci_view_paths = $path;
		} else {
			// otherwise we'll just add the specified one
			$this->_ci_view_paths = array($path => true);
		}
	}

	/**
	 * Since parent::_ci_view_paths is protected we use this to retrieve them.
	 *
	 * @return array
	 */
	public function get_view_paths()
	{
		// return the full array of paths
		return $this->_ci_view_paths;
	}

	/**
	 * Keep track of which sparks are loaded. This will come in handy for being
	 *  speedy about loading files later.
	 *
	 * @var array
	 * @author      Kenny Katzgrau <katzgrau@gmail.com>
	 */
	public $_ci_loaded_sparks = array();

	/**
	 * To accomodate CI 2.1.0, we override the initialize() method instead of
	 * the ci_autoloader() method. Once sparks is integrated into CI, we can
	 * avoid the awkward version-specific logic.
	 *
	 * @return \MY_Loader
	 */
	public function initialize($controller = null)
	{
		parent::initialize();

		$this->ci_autoloader();

		return $this;
	}

	/**
	 * Specific Autoloader (99% ripped from the parent)
	 *
	 * The config/autoload.php file contains an array that permits sub-systems,
	 * libraries, and helpers to be loaded automatically.
	 *
	 * @param array|null $basepath
	 *
	 * @return void
	 */
	protected function ci_autoloader($basepath = null)
	{
		$autoload_path = (($basepath !== null) ? $basepath : APPPATH).'config/autoload.php';

		if ( ! file_exists($autoload_path)) {
			return false;
		}

		include($autoload_path);

		if ( ! isset($autoload)) {
			return false;
		}

		if ($basepath !== null) {
			// Autoload packages
			if (isset($autoload['packages'])) {
				foreach ($autoload['packages'] as $package_path) {
					$this->add_package_path($package_path);
				}
			}
		}

		if ($basepath !== null) {
			if (isset($autoload['config'])) {
				// Load any custom config file
				if (count($autoload['config']) > 0) {
					$CI =& get_instance();
					foreach ($autoload['config'] as $key => $val) {
						$CI->config->load($val);
					}
				}
			}

			// Autoload helpers and languages
			foreach (array('helper', 'language') as $type) {
				if (isset($autoload[$type]) and count($autoload[$type]) > 0) {
					$this->$type($autoload[$type]);
				}
			}

			// A little tweak to remain backward compatible
			// The $autoload['core'] item was deprecated
			if ( ! isset($autoload['libraries']) and isset($autoload['core'])) {
				$autoload['libraries'] = $autoload['core'];
			}

			// Load libraries
			if (isset($autoload['libraries']) and count($autoload['libraries']) > 0) {
				// Load the database driver.
				if (in_array('database', $autoload['libraries'])) {
					$this->database();
					$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
				}

				// Load all other libraries
				foreach ($autoload['libraries'] as $item) {
					$this->library($item);
				}
			}

			// Autoload models
			if (isset($autoload['model'])) {
				$this->model($autoload['model']);
			}
		}
	}
}
