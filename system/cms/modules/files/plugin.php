<?php defined('BASEPATH') or exit('No direct script access allowed');

use Pyro\Module\Files\Model\Folder;
use Pyro\Module\Files\Model\File;

/**
 * Files Plugin
 *
 * Create a list of files
 *
 * @author      Marcos Coelho
 * @author      PyroCMS Dev Team
 * @package     PyroCMS\Core\Modules\Files\Plugins
 */
class Plugin_Files extends Plugin
{
    public $version = '1.0.0';

    public $name = array(
        'en' => 'Files',
            'fa' => 'فایل ها',
    );

    public $description = array(
        'en' => 'List files in specified folders and output images with cropping.',
             'fa' => 'لیست فایل های موجود در پوشه ی مشخص شده و خروجی تصاویر',
    );

    /**
     * @var string Optional CDN domain to use as a "pull zone" for file URLs
     */
    protected $cdn_domain;

    /**
     * Returns a PluginDoc array that PyroCMS uses
     * to build the reference in the admin panel
     *
     * All options are listed here but refer
     * to the Blog plugin for a larger example
     *
     * @todo fill the  array with details about this plugin, then uncomment the return value.
     *
     * @return array
     */
    public function _self_doc()
    {
        $info = array(
            'listing' => array(// the name of the method you are documenting
                'description' => array(// a single sentence to explain the purpose of this method
                    'en' => 'Iterate through files contained in the specified folder or which have the specified tags.'
                ),
                'single' => false,// will it work as a single tag?
                'double' => true,// how about as a double tag?
                'variables' => 'id|folder_id|folder_name|folder_slug|user_id|type|name|filename|description|extension|mimetype|width|height|filesize|date_added',
                'attributes' => array(
                    'folder' => array(// this is the order-dir="asc" attribute
                        'type' => 'number|slug',// Can be: slug, number, flag, text, array, any.
                        'flags' => '',// flags are predefined values like this.
                        'default' => '',// attribute defaults to this if no value is given
                        'required' => false,// is this attribute required?
                    ),
                    'tagged' => array(
                        'type' => 'text',
                        'flags' => '',
                        'default' => '',
                        'required' => false,
                    ),
                    'limit' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => false,
                    ),
                    'offset' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '0',
                        'required' => false,
                    ),
                    'type' => array(
                        'type' => 'flag',
                        'flags' => 'a|v|d|i|o',
                        'default' => '',
                        'required' => false,
                    ),
                    'order-by' => array(
                        'type' => 'flag',
                        'flags' => 'folder_id|user_id|type|name|extension|width|height|filesize|download_count|date_added|sort',
                        'default' => 'sort',
                        'required' => false,
                    ),
                    'order-dir' => array(
                        'type' => 'flag',
                        'flags' => 'asc|desc|random',
                        'default' => 'asc',
                        'required' => false,
                    ),
                ),
            ),// end listing method
            'folders' => array(
                'description' => array(
                    'en' => 'List folders and files (optional) from a specified folder'
                ),
                'single' => false,
                'double' => true,
                'variables' => 'folders|files|parent_id',
                'attributes' => array(
                    'folder' => array(// this is the order-dir="asc" attribute
                        'type' => 'number|slug',// Can be: slug, number, flag, text, array, any.
                        'flags' => '',// flags are predefined values like this.
                        'default' => '0',// attribute defaults to this if no value is given
                        'required' => false, // is this attribute required?
                    ),
                ),
            ),// end folders method
            'folder_exists' => array(
                'description' => array(
                    'en' => 'Check if a folder exists in the database.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'slug' => array(
                        'type' => 'slug',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                ),
            ),// end folder_exists method
            'exists' => array(
                'description' => array(
                    'en' => 'Check if a file exists in the database.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                ),
            ),// end exists method
            'image' => array(
                'description' => array(
                    'en' => 'Output an image tag while resizing the image.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                    'width' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '100',
                        'required' => false,
                    ),
                    'height' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '100',
                        'required' => false,
                    ),
                    'size' => array(
                        'type' => 'text',
                        'flags' => '',
                        'default' => '100/100',
                        'required' => false,
                    ),
                    'mode' => array(
                        'type' => 'flag',
                        'flags' => 'fit|fill',
                        'default' => '',
                        'required' => false,
                    ),
                ),
            ),// end image method
            'image' => array(
                'description' => array(
                    'en' => 'Output an image tag while resizing the image.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                    'width' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '100',
                        'required' => false,
                    ),
                    'height' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '100',
                        'required' => false,
                    ),
                    'size' => array(
                        'type' => 'text',
                        'flags' => '',
                        'default' => '100/100',
                        'required' => false,
                    ),
                    'mode' => array(
                        'type' => 'flag',
                        'flags' => 'fit|fill',
                        'default' => '',
                        'required' => false,
                    ),
                ),
            ),// end image method
            'image_url' => array(
                'description' => array(
                    'en' => 'Output a url to the specified image.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                ),
            ),// end image url method
            'image_path' => array(
                'description' => array(
                    'en' => 'Output a filesystem path to the specified image.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                ),
            ),// end image path method
            'url' => array(
                'description' => array(
                    'en' => 'Output a url to the specified file.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                ),
            ),// end file url method
            'path' => array(
                'description' => array(
                    'en' => 'Output a filesystem path to the specified file.'
                ),
                'single' => true,
                'double' => false,
                'variables' => '',
                'attributes' => array(
                    'id' => array(
                        'type' => 'number',
                        'flags' => '',
                        'default' => '',
                        'required' => true,
                    ),
                ),
            ),// end file path method
        );

        return $info;
    }

    private $_files = array();

    public function __construct()
    {
        $this->load->library('files/files');

        $this->cdn_domain = Settings::get('cdn_domain');
    }

    /**
     * Files listing
     *
     * Creates a list of files
     *
     * Usage:
     *
     * {{ files:listing folder="home-slider" type="i" fetch="subfolder|root" }}
     *  // your html logic
     * {{ /files:listing }}
     *
     *
     * Alternate Usage:
     *
     * {{ files:listing folder="home-slider" tagged="sunset|hiking|mountain" }}
     *  // your html logic
     * {{ /files:listing }}
     *
     * The tags that are available to use from this method are listed below
     *
     * {{ id }}
     * {{ folder_id }}
     * {{ user_id }}
     * {{ type }}
     * {{ name }}
     * {{ filename }}
     * {{ description }}
     * {{ extension }}
     * {{ mimetype }}
     * {{ width }}
     * {{ height }}
     * {{ filesize }}
     * {{ date_added }}
     *
     * @return  array
     */
    public function listing()
    {
        if ( ! $this->content()) {
            return '';
        }

        $folder_id = $this->attribute('folder', ''); // Id or Path
        $tags      = $this->attribute('tagged', false);
        $limit     = $this->attribute('limit', null);
        $offset    = $this->attribute('offset', '');
        $type      = $this->attribute('type', '');
        $fetch     = $this->attribute('fetch');

        $order_by  = $this->attribute('order-by', 'sort');
        $order_ord  = $this->attribute('order-ord', 'asc');

        $files = Files::getListing($folder_id, $tags, $limit, $offset, $type, $fetch, $order_by, $order_ord);

        $files and array_merge($this->_files, (array) $files);

        return $files;
    }

    /**
     * Return a file
     * @return array/null
     *
     * * Usage:
     *
     * {{ files:file id="9517fd0bf8faa65" }}
     *  // your html logic
     * {{ /files:file }}
     *
     */
    public function find()
    {
        $file = File::find($this->getAttribute('id'));

        return $file ? ci()->parser->parse_string($this->content(), $file->toArray(), true) : false;
    }

    /**
     * Folder contents
     *
     * Creates a list of folders
     *
     * Usage:
     *
     * {{ files:folders folder="home-slider" include_files="no|yes" }}
     *  {{ folders }}
     *      // Your html logic
     *  {{ /folders }}
     *
     *  {{ files }}
     *      // your html logic
     *  {{ /files }}
     * {{ /files:folders }}
     *
     * The tags that are available to use from this method are listed below
     *
     * {{ folders }}
     * {{ files }}
     * {{ parent_id }}
     *
     * @return  array
     */
    public function folders()
    {
        $parent = $this->attribute('folder', 0); // Id or Path
        $include_files = $this->attribute('include_files', 'no');

        $data = array();

        if ( ! is_numeric($parent)) {
            $segment = explode('/', trim($parent, '/#'));
            $result = $this->file_folders_m->get_by('slug', array_pop($segment));

            $parent = ($result ? $result->id : 0);
        }

        $folders = ci()->file_folders_m->where('parent_id', $parent)
            ->where('hidden', 0)
            ->order_by('sort')
            ->get_all();

        $files = ($include_files == 'yes')
            ? File::findByFolderIdBySort($parent)
            : false;

        // let's be nice and add a date in that's formatted like the rest of the CMS
        if ($folders) {
            foreach ($folders as &$folder) {
                $folder->formatted_date = format_date($folder->date_added);

                $folder->file_count = File::countByFolderId($folder->id);
            }
            $data['folders'] = $folders;
        }

        if ($files) {
            ci()->load->library('keywords/keywords');

            foreach ($files as &$file) {
                $file->keywords_hash = $file->keywords;
                $file->keywords = ci()->keywords->get_string($file->keywords);
                $file->formatted_date = format_date($file->date_added);
            }
            $data['files'] = $files;
        }

        $data['parent_id'] = $parent;

        return array($data);
    }

    public function file($return = '', $type = '')
    {
        // nothing to do
        if ($return && ! in_array($return, array('url', 'path'))) {
            return '';
        }

        // prepare file params
        $id   = $this->attribute('id');
        $type = $type and in_array($type, array('a','v','d','i','o')) ? $type : '';

        // get file
        if (isset($this->_files[$id])) {
            $file = $this->_files[$id];
        } else {
            $type and File::where('type', $type);

            $file = File::find($id);
        }

        // file not found
        if (! $file or ($type && $file->type !== $type)) {
            return '';

        // return file fields array
        } elseif (! $return && $this->content()) {
            return (array) $file;
        }

        // make uri
        if ($type === 'i') {
            if ($size = $this->attribute('size', '')) {
                (strpos($size, 'x') === false) and ($size .= 'x');

                list($width, $height) = explode('/', strtr($size, 'x', '/'));
            } else {
                $width  = $this->attribute('width', '');
                $height = $this->attribute('height', '');
            }

            is_numeric($width) or $width = 'auto';
            is_numeric($height) or $height = 'auto';

            if ($width === 'auto' && $height === 'auto') {
                $dimension = '';
            } else {
                $mode = $this->attribute('mode', '');
                $mode = in_array($mode, array('fill', 'fit')) ? $mode : '';

                $dimension = trim($width . '/' . $height . '/' . $mode, '/');
            }

            if ($file->folder->location === 'local' and $dimension) {
                $uri = sprintf('files/thumb/%s/%s', $file->filename, $dimension);
            } elseif ($file->folder->location === 'local') {
                // we can't just return the path on this because they may not want an absolute url
                $uri = 'files/large/' . $file->filename;
            } else {
                $uri = $file->path;
            }
        } else {
            $uri = ($file->folder->location === 'local') ? 'files/download/' . $file->id : $file->path;
        }

        // return string
        if ($return) {
            // if it isn't local then they are getting a url regardless what they ask for
            if ($file->folder->location !== 'local') {
                return $file->path;
            }

            // If they want a URL, give them a URL, and maybe shove a CDN domain on it
            if ($return === 'url') {
                return $this->prependDomain($uri);
            }

            // Didnt specify a URL, so its a path, which is going to be CDN-less sadly
            return BASE_URI.$uri;
        }

        $attributes = $this->attributes();

        foreach (array('base', 'size', 'id', 'title', 'type', 'mode', 'width', 'height') as $key) {
            if (isset($attributes[$key]) && ($type !== 'i' or ! in_array($key, array('width', 'height')))) {
                unset($attributes[$key]);
            }

            if (isset($attributes['tag-' . $key])) {
                $attributes[$key] = $attributes['tag-' . $key];

                unset($attributes['tag-' . $key]);
            }
        }

        $base = $this->attribute('base', 'url');

        // alt tag is named differently in db to prevent confusion with "alternative", so need to do check for it manually
        if (!isset($attributes['alt'])) {
            $attributes['alt'] =  $file->alt_attribute;
        }

        // return an image tag html
        if ($type === 'i') {
            $this->load->helper('html');

            if (strpos($size, 'x') !== false && ! isset($attributes['width'], $attributes['height'])) {
                list($attributes['width'], $attributes['height']) = explode('x', $size);
            }

            return $this->{'_build_tag_location_' . $base}($type, $uri, array(
                'attributes' => $attributes,
                'index_page' => true
            ));
        }

        // return an file anchor tag html
        $title = $this->attribute('title');

        return $this->buildHtmlTag($type, $uri, compact('title', 'attributes'));
    }

    public function image()
    {
        return $this->file('', 'i');
    }

    public function image_url()
    {
        return $this->file_url('i');
    }

    public function image_path()
    {
        return $this->file_path('i');
    }

    public function file_url($type = '')
    {
        return $this->file('url', $type);
    }

    public function file_path($type = '')
    {
        return $this->file('path', $type);
    }

    public function exists()
    {
        $id = $this->attribute('id');

        $exists = (bool) (isset($this->_files[$id]) ? true : !(File::find($id)->isEmpty()));

        return ($exists && $this->content()) ?: $exists;
    }

    public function folder_exists()
    {
        $exists = (bool) !(Folder::findBySlug($this->attribute('slug'))->isEmpty());

        return ($exists && $this->content()) ?: $exists;
    }

    /**
     * Prepend Domain
     *
     * The user will probably want to use the domain the file is on, OR a CDN.
     * CDN support is basic and assumes it is a "Pull Zone". It will also allow
     * content expiration whenever the CDN sees fit to expire it. Pyro wont do 
     * that for the user.
     *
     * @param string $uri The path string (URI) which needs a domain shoved on 
     *   on the front 
     *
     * @return string
     */
    protected function prependDomain($uri)
    {
        $urlString = site_url($uri);

        // No CDN, so just shove this out immediately
        if (! $this->cdn_domain) {
            return $urlString;
        }
        
        // Strip the URL apart so we can manipulate it
        $urlParts = parse_url($urlString);

        // We have to replace the domain with a CDN domain
        $urlParts['host'] = $this->cdn_domain;

        // Make a new URL string
        return http_build_url($urlParts);
    }

    /**
     * Build HTML Tag
     *
     * Take a URL and a type ('i' or other) and get a HTML tag as a response.
     *
     * @param string $type The type of file we are working with: 'i' is an image, 
     *   anything else is going to be linked to directly
     * @param string $url The location (uri, local URL, CDN URL or third-party URL) of 
     *   a file
     * @param array $extras The location (uri, local URL, CDN URL or third-party URL) of 
     *   a file
     *
     * @return string
     */
    protected function buildHtmlTag($type, $url, $extras = array())
    {
        $attributes = isset($extras['attributes']) ? $extras['attributes'] : array();

        if ($type === 'i') {
            $attributes['src'] = $url;

            $index_page = isset($extras['index_page']) ? $extras['index_page'] : null;

            return img($attributes, $index_page);
        }

        // Just basics then, as urls might have links
        $title = isset($extras['title']) ? $extras['title'] : null;

        // Link to it so the browser can deal with what is going on
        return anchor($url, $title, $attributes);
    }
}
