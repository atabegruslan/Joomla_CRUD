<?php
/**
 * @package     TripBlog
 * @subpackage  Field
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Form\FormHelper;

jimport('tripblog.helper.country');
jimport('joomla.form.helper');
FormHelper::loadFieldClass('list');

/**
 * Country Field
 *
 * @package     TripBlog
 * @subpackage  Field
 */
class JFormFieldCountry extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	public $type = 'Country';

	/**
	 * Method to get the field options.
	 *
	 * @return  array   $options   The field option objects.
	 */
	protected function getOptions()
	{
		$options = array();
		$items   = TripblogHelperCountry::getCountries();

		// Build the field options.
		if (!empty($items))
		{
			foreach ($items as $item)
			{
				$options[] = HTMLHelper::_('select.option', $item->id, Text::_($item->name));
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
