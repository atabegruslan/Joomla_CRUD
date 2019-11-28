<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Models
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

/**
 * User Model
 *
 * @package     TripBlog.Backend
 * @subpackage  Models
 */
class TripBlogModelUser extends AdminModel
{
	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   string  $type     The table type to instantiate. [optional]
	 * @param   string  $prefix   A prefix for the table class name. [optional]
	 * @param   Array   $config   Configuration array for model. [optional]
	 *
	 * @return  Table   $table    A database object
	 */
	public function getTable($type = 'User', $prefix = 'TripBlogTable', $config = array())
	{
		/** @var TripBlogTableUser $table */
		$table = Table::getInstance($type, $prefix, $config);

		return $table;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   Array    $data       An optional array of data for the form to interogate.
	 * @param   boolean  $loadData   True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form     $form       A Form object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		$options = array(
			'control'   => 'jform',
			'load_data' => $loadData
		);

		$form = $this->loadForm('com_tripblog.user', 'user', $options);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed   $data   The data for the form.
	 */
	protected function loadFormData()
	{
		$app  = Factory::getApplication();
		$data = $app->getUserState('com_tripblog.edit.user.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		if (isset($data->joomla_id) && $data->joomla_id > 0)
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->qn('group_id'));
			$query->from($db->qn('#__user_usergroup_map'));
			$query->where($db->qn('user_id') . ' = '. $db->q($data->joomla_id));

			$data->usergroup = $db->setQuery($query)->loadColumn();
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   Array     $data     The form data.
	 *
	 * @return  boolean   $result   True on success.
	 */
	public function save($data)
	{
		$table  = $this->getTable();
		$result = $table->save($data);

		return $result;
	}
}
