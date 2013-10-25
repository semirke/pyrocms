<?php namespace Pyro\FieldType;

use Pyro\Module\Streams_core\Core\Field\AbstractField;

/**
 * PyroStreams Integer Field Type
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Field Types
 * @author		Parse19
 * @copyright	Copyright © 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Integer extends AbstractField
{
	public $field_type_slug = 'integer';

	public $db_col_type = 'integer';

	public $custom_parameters = array(
		'max_length',
		'default_value',
		'placeholder',
		);

	public $extra_validation = 'integer';

	public $version = '1.0.0';

	public $author = array(
		'name'=>'Ryan Thompson - PyroCMS',
		'url'=>'http://pyrocms.com/'
		);

	// --------------------------------------------------------------------------

	/**
	 * Output form input
	 *
	 * @param	array
	 * @param	array
	 * @return	string
	 */
	public function formOutput()
	{
		$options['name'] 	= $this->form_slug;
		$options['id']		= $this->form_slug;
		$options['value']	= $this->value;
		$options['placeholder']	= $this->getParameter('placeholder');
		
		// Max length
		if ($max_length = $this->getParameter('max_length'))
		{
			$options['maxlength'] = $max_length;
		}

		return form_input($options);
	}

}
