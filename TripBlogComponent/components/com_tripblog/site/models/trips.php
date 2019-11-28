<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Models
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
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Trips Model
 *
 * @package     TripBlog.Frontend
 * @subpackage  Models
 */
class TripBlogModelTrips extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   Array  $config  An optional associative array of configuration settings.
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				't.place',
				't.published',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery  $query
	 */
	public function getListQuery()
	{
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select($db->qn('t.id'));
		$query->select($db->qn('t.place'));
		$query->select($db->qn('t.review'));
		$query->select($db->qn('t.image'));
		$query->select($db->qn('t.published'));
		$query->select($db->qn('c.name', 'country'));
		$query->select('COALESCE(GROUP_CONCAT(cat.title SEPARATOR ' . $db->q(', ') . '), ' . $db->q('Unspecified') . ') AS continents');
		$query->select('COALESCE(SUM(' . $db->qn('a2.likes') . '), 0) AS total_like');
		$query->select('COALESCE(COUNT(' . $db->qn('a2.likes') . '), 0) AS number_like');
		$query->select(array('t.checked_out AS checked_out', 't.checked_out_time AS checked_out_time', 'ju.username AS editor'));
		$query->select($db->qn('ju2.name', 'created'));
		$query->from($db->qn('#__tripblog_trip', 't'));
		$query->join('left', $db->qn('#__tripblog_country', 'c') . ' ON ' . $db->qn('t.country') . ' = ' . $db->qn('c.id'));
		$query->join('left', $db->qn('#__tripblog_likes', 'a2') . ' ON ' . $db->qn('t.id') . ' = ' . $db->qn('a2.tripblog_id'));
		$query->join('left', $db->qn('#__users', 'ju') . ' ON ju.id = t.checked_out');
		$query->join('left', $db->qn('#__users', 'ju2') . ' ON ju2.id = t.created');
		$query->join('left', $db->qn('#__tripblog_country_continent_xref', 'cont') . ' ON cont.country_id = c.id');
		$query->join('left', $db->qn('#__categories', 'cat') . ' ON cat.id = cont.continent_id AND cat.extension = ' . $db->q('com_tripblog'));
		$query->group($db->qn('t.id'));

		$joomlaUser = Factory::getUser();

		if (( (int) $joomlaUser->id) !== 0 && !TripblogHelperPermission::isSuper())
		{
			$keyValueIdentifier = array('joomla_id' => $joomlaUser->id);
			$tripBlogUser       = TripblogHelperUser::getTripblogUser($keyValueIdentifier);
			$tripBlogUserId     = $tripBlogUser->id;

			if ($tripBlogUserId)
			{
				$query->select($db->qn('a.likes', 'likes'));
				$query->join('left', $db->qn('#__tripblog_likes', 'a') . ' ON (' . $db->qn('t.id') . ' = ' . $db->qn('a.tripblog_id') . ' AND ' . $db->qn('a.tripblog_user_id') . ' = ' . $db->q($tripBlogUserId) . ')');
			}
			else
			{
				$query->select($db->qn(0, 'likes'));
			}
		}
		else
		{
			$query->select($db->qn(0, 'likes'));
		}

		$search = $this->getState('filter.search_tripblogs');

		if (!empty($search))
		{
			$like = $db->q('%' . $search . '%');
			$query->where('t.place LIKE ' . $like);
		}

		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('t.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(t.published IN (0, 1))');
		}

		$orderCol  = $this->state->get('list.ordering', 't.place');
		$orderDirn = $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

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
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   int      $state   The publishing state.
	 * @param   int      $userId  The user id of the user performing the operation.
	 *
	 * @return  void
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$app = Factory::getApplication();

		$this->togglePublished($pks, $state, $userId);

		$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
	}

	// public function unpublish($pks = null, $state = 1, $userId = 0)
	// {
	// 	$app = Factory::getApplication();

	// 	$this->togglePublished($pks, $state, $userId);

	// 	$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
	// }

	/**
	 * Toggle publish and unpublish
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not set the instance property value is used.
	 * @param   int      $state   The publishing state.
	 * @param   int      $userId  The user id of the user performing the operation.
	 *
	 * @return  boolean  $result  Success of publish action
	 */
	protected function togglePublished($pks = null, $state = 1, $userId = 0)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		$fields = array(
			$db->qn('published') . ' = ' . $db->q($state)
		);

		$conditions = array(
			$db->qn('id') . ' IN (' . implode(',', $pks) . ')'
		);

		$query->update($db->qn('#__tripblog_trip'))->set($fields)->where($conditions);

		$result = $db->setQuery($query)->execute();

		return $result;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 */
	protected function populateState($ordering = 't.place', $direction = 'asc')
	{
		parent::populateState($ordering, $direction);
	}
}
