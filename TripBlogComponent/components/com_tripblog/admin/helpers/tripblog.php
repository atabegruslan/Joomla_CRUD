<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Helpers
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Helper\ContentHelper;

/**
 * HelloWorld component helper.
 *
 * @package     TripBlog.Backend
 * @subpackage  Helpers
 */
class TripblogHelper extends ContentHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string   $submenu   Name of the side-menu item
	 *
	 * @return  void
	 */
	public static function addSubmenu($submenu) 
	{
		JHtmlSidebar::addEntry(
			Text::_('COM_TRIPBLOG_SUBMENU_TRIPBLOGS'),
			'index.php?option=com_tripblog&view=tripblogs',
			$submenu == 'tripblogs'
		);

		JHtmlSidebar::addEntry(
			Text::_('COM_TRIPBLOG_SUBMENU_CONTINENTS'),
			'index.php?option=com_categories&view=categories&extension=com_tripblog',
			$submenu == 'categories'
		);

		$document = Factory::getDocument();

		if ($submenu == 'categories') 
		{
			$document->setTitle(Text::_('COM_TRIPBLOG_SUBMENU_CONTINENTS'));
		}
	}
}