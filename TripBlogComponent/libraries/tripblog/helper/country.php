<?php
/**
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */

defined('_JEXEC') or die;

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

/**
 * Country helper.
 *
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */
class TripblogHelperCountry
{
	/**
	 * Method to get the list of countries.
	 *
	 * @return  Array  $countries   List of countries
	 */
	public static function getCountries()
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select(array('*'))
			->from($db->qn('#__tripblog_country'));

		$countries = $db->setQuery($query)->loadObjectList();

		return $countries;
	}
}
