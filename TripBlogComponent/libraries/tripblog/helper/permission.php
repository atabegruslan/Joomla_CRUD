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

/**
 * Permission helper.
 *
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */
class TripblogHelperPermission
{
	/**
	 * Is logged in
	 *
	 * @return  boolean
	 */
	public static function isLoggedIn()
	{
		return (boolean) Factory::getUser()->id;
	}

	/**
	 * Is current user super admin user
	 *
	 * @return  boolean
	 */
	public static function isSuper()
	{
		return (boolean) Factory::getUser()->authorise('core.admin');
	}

	/**
	 * Can current user administer
	 *
	 * @return  boolean
	 */
	public static function canAdmin()
	{
		return ( (boolean) Factory::getUser()->id ) && JHelperContent::getActions('com_tripblog')->get('core.admin');
	}

	/**
	 * Can current user manage
	 *
	 * @return  boolean
	 */
	public static function canManage()
	{
		return ( (boolean) Factory::getUser()->id ) && JHelperContent::getActions('com_tripblog')->get('core.manage');
	}

	/**
	 * Can current user create
	 *
	 * @return  boolean
	 */
	public static function canCreate()
	{
		return ( (boolean) Factory::getUser()->id ) && JHelperContent::getActions('com_tripblog')->get('core.create');
	}

	/**
	 * Can current user delete
	 *
	 * @return  boolean
	 */
	public static function canDelete()
	{
		return ( (boolean) Factory::getUser()->id ) && JHelperContent::getActions('com_tripblog')->get('core.delete');
	}

	/**
	 * Can current user edit
	 *
	 * @return  boolean
	 */
	public static function canEdit()
	{
		return ( (boolean) Factory::getUser()->id ) && JHelperContent::getActions('com_tripblog')->get('core.edit');
	}

	/**
	 * Can current user edit state
	 *
	 * @return  boolean
	 */
	public static function canEditState()
	{
		return ( (boolean) Factory::getUser()->id ) && JHelperContent::getActions('com_tripblog')->get('core.edit.state');
	}

	/**
	 * Can current user edit own
	 *
	 * @return  boolean
	 */
	public static function canEditOwn()
	{
		return ( (boolean) Factory::getUser()->id ) && JHelperContent::getActions('com_tripblog')->get('core.edit.own');
	}

	/**
	 * Get Usergroup IDs
	 *
	 * @param   int     $usergroupId		 Usergroup's ID
	 *
	 * @return  Array   $usergroupIds 		 All parent usergroups' IDs
	 */
	public static function getUsergroupIds($usergroupId)
    {
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->qn(array('id', 'title', 'parent_id')));
		$query->from($db->qn('#__usergroups'));
		$query->order('parent_id DESC');

		$usergroupData = $db->setQuery($query)->loadAssocList('id');

		$usergroupIds = array();
		self::getUsergroupParentIds($usergroupData, $usergroupIds, $usergroupId);

		return $usergroupIds;
    }

	/**
	 * Update checkin values of a table record
	 *
	 * @param   string     $feature		For example 'trip' or 'user'.
	 * @param   int        $id 		    Record ID.
	 * @param   boolean    $isCheckIn 	Is checking in or out?
	 *
	 * @return  void
	 */
    public static function checkin($feature, $id, $isCheckIn)
    {
		$user   = Factory::getUser();
		$userId = (int) $user->get('id');

		if ($isCheckIn && (!self::canEdit()/* || !$user->authorise('core.manage', 'com_checkin')*/))
		{
			return;
		}

		$date         = Factory::getDate();
		$modified     = $date->toSql();
		$db           = Factory::getDbo();
		$tableName    = '#__tripblog_' . $feature;
		$checkInValue = $isCheckIn ? $userId : 0;

		$selectQuery = $db->getQuery(true)
			->select($db->qn('checked_out'))
			->from($db->qn($tableName))
			->where($db->qn('id') . ' = ' . $db->q($id));

		$modifierId = (int) $db->setQuery($selectQuery)->loadResult();

		if (!$isCheckIn && !self::isSuper() && ($userId !== $modifierId))
		{
			return;
		}

		$updateQuery = $db->getQuery(true);

		$fields = array(
		    $db->qn('checked_out') . ' = ' . $db->q($checkInValue),
		    $db->qn('checked_out_time') . ' = ' . $db->q($modified)
		);

		$conditions = array(
		    $db->qn('id') . ' = ' . $db->q($id)
		);

		$updateQuery
			->update($db->qn($tableName))
			->set($fields)
			->where($conditions);

		$db->setQuery($updateQuery)->execute();
    }

	/**
	 * Recursively get Parent Usergroup's ID
	 *
	 * @param   Array     $usergroupData		Data about the current usergroup in question
	 * @param   Array     $usergroupIds 		Reference to the array that collects parent usergroups' IDs 
	 * @param   int       $usergroupId 			ID of the current usergroup in question
	 *
	 * @return  void
	 */
    protected static function getUsergroupParentIds($usergroupData, &$usergroupIds, $usergroupId)
    {
        $usergroupId = (int) $usergroupId;

        if ($usergroupId === 1)
        {
            return;
        }

        array_push($usergroupIds, $usergroupId);

        self::getUsergroupParentIds($usergroupData, $usergroupIds, $usergroupData[$usergroupId]['parent_id']);
    }
}
