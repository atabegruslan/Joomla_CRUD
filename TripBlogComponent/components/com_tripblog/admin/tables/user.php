<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Tables
 */

defined('_JEXEC') or die;

jimport('joomla.database.table');

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
use Joomla\CMS\User\User;
use Joomla\CMS\Table\Table;

/**
 * User table.
 *
 * @package     TripBlog.Backend
 * @subpackage  Tables
 */
class TripBlogTableUser extends Table
{

    /**
     * TripBlog user ID.
     *
     * @var  int
     */
	var $id = null;

    /**
     * Joomla user ID.
     *
     * @var  int
     */
	var $joomla_id = null;

    /**
     * User's name.
     *
     * @var  string
     */
	var $name = null;

    /**
     * Username.
     *
     * @var  string
     */
	var $username = null;

    /**
     * User's email.
     *
     * @var  string
     */
	var $email = null;

    /**
     * User's password.
     *
     * @var  string
     */
	var $password = null;

    /**
     * URL to user's photo.
     *
     * @var  string
     */
	var $image = null;

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  $db  Database connector object
     */
	function __construct(&$db)
	{
		parent::__construct('#__tripblog_user', 'id', $db);
	}

    /**
     * Method to provide a shortcut to binding, checking and storing a FOFTable
     * instance to the database table.  The method will check a row in once the
     * data has been stored and if an ordering filter is present will attempt to
     * reorder the table rows based on the filter.  The ordering filter is an instance
     * property name.  The rows that will be reordered are those whose value matches
     * the FOFTable instance for the property specified.
     *
     * @param   mixed   $src             An associative array or object to bind to the FOFTable instance.
     * @param   string  $orderingFilter  Filter for the order updating
     * @param   mixed   $ignore          An optional array or space separated list of properties
     *                                   to ignore while binding.
     *
     * @return  boolean  True on success.
     */
	public function save($src, $orderingFilter = '', $ignore = '')
	{
		$joomlaId = $this->saveJoomlaUser($src);

		if ($joomlaId)
		{
			$src['joomla_id'] = $joomlaId;
			// @TODO: Use encryptPassword properly
			$src['password']  = TripblogHelperUser::encryptPassword($src['password']);

			return parent::save($src, $orderingFilter, $ignore);
		}
		else
		{
			return false;
		}
	}

    /**
     * Save Joomla User
     *
     * @param   mixed   $src             An associative array or object of info about user
     *
     * @return  int     $insertId        Previously inserted user's ID, or 0 if save failed.
     */
	protected function saveJoomlaUser($src)
	{
		$user     = User::getInstance(isset($src['joomla_id']) ? $src['joomla_id'] : 0);
		$app      = Factory::getApplication();
		$allUgIds = array();

		if (!isset($src['usergroup']) && empty(($src['usergroup'])))
		{
			$menu           = $app->getMenu();
			// $itemId         = $app->input->get('Itemid');
			// $activeMenuItem = $menu->getItem($itemId);
			$activeMenuItem = $menu->getActive();
			$menuItemConfig = $activeMenuItem->params;
			$usergroupIds   = $menuItemConfig->get('default_register_usergroup');

			if (!is_array($usergroupIds) || empty($usergroupIds))
			{
				$usergroupIds = array(3);
			}
		}
		else
		{
			$usergroupIds = $src['usergroup'];
		}

		foreach ($usergroupIds as $ugId)
		{
			$ugIds    = TripblogHelperPermission::getUsergroupIds($ugId);
			$allUgIds = array_merge($allUgIds, $ugIds);
		}

		sort($allUgIds);

		$userInfos = array(
			'name'       => $src['name'],
			'username'   => $src['username'],
			'password'   => $src['password'],
			'email'      => $src['email'],
			'groups'     => $allUgIds,
			'block'      => $src['block'],
			'sendEmail'  => $src['sendEmail'],
			'activation' => $src['activation']
		);

		$user->bind($userInfos);

		if (!$user->save())
		{
			return 0;
		}

		$insertId = $user->get('id');

		return $insertId;
	}

	/**
	 * Method to remove TripBlog users.
	 *
	 * @param   Array    $cids  Array of table IDs.
	 *
	 * @return  boolean  True on success.
	 */
	public function remove($cids)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(array('*'));
		$query->from($db->qn('#__tripblog_user'));
		$query->where($db->qn('id') . ' IN ( '. implode(',', $cids) . ')');

		$toDelete = $db->setQuery($query)->loadObjectList('id');

		if (empty($toDelete) || is_null($toDelete))
		{
			return false;
		}

		$deletionResult = true;

		foreach ($toDelete AS $pk => $row)
		{
			if (!parent::delete($pk) || !$this->deleteJoomlaUser($row->joomla_id))
			{
				$deletionResult = false;

				break;
			}
		}

		return $deletionResult;
	}

	/**
	 * Method to remove Joomla user.
	 *
	 * @param   int      $jids             Joomla user ID.
	 *
	 * @return  boolean  $deletionResult   True on success.
	 */
	protected function deleteJoomlaUser($jid)
	{
		$user           = User::getInstance($jid);
		$deletionResult = $user->delete();

		return $deletionResult;
	}
}

?>
