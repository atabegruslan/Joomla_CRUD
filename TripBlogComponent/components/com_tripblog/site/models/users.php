<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Models
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Users Model
 *
 * @package     TripBlog.Frontend
 * @subpackage  Models
 */
class TripBlogModelUsers extends ListModel
{
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery  $query
	 */
	public function getListQuery()
	{
		$db = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select(array('*'));
		$query->from('#__tripblog_user');

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed   $items   An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}

	/**
	 * Method to delete an user.
	 *
	 * @param   Array    $cids  Table IDs.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete(&$cids)
    {
		/** @var TripBlogTableUser $table */
        $table = Table::getInstance('User', 'TripBlogTable');

        return $table->remove($cids);
    }
}
