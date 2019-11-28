<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Models
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;

/**
 * Trip Model
 *
 * @package     TripBlog.Backend
 * @subpackage  Models
 */
class TripBlogModelTrip extends AdminModel
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
	public function getTable($type = 'Trip', $prefix = 'TripBlogTable', $config = array())
	{
		/** @var TripBlogTableTrip $table */
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

		$form = $this->loadForm('com_tripblog.trip', 'trip', $options);

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
		$data = $app->getUserState('com_tripblog.edit.trip.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$data->created = Factory::getUser($data->created)->name;

		return $data;
	}

	/**
	 * Save the likes info of a TripBlog post
	 *
	 * @param   Array    $data              The likes info of a TripBlog post.
	 *
	 * @return  int      $numberOfLikes     How many thumbs ups is given by the current user.
	 */
	public function saveLikes($data)
	{
		/** @var TripBlogTableLike $likeTable */
		$likeTable = Table::getInstance('Like', 'TripBlogTable');

		$likeTable->load(
			array(
				'tripblog_id'      => $data['tripblog_id'],
				'tripblog_user_id' => $data['tripblog_user_id'],
			)
		);

		if ($likeTable->id)
		{
			$data['id'] = $likeTable->id;
		}

		$numberOfLikes = $likeTable->save($data) ? $likeTable->likes : 0;

		return $numberOfLikes;
	}

	/**
	 * Retrieve the likes info of a TripBlog post
	 *
	 * @param   Array    $data              The likes info of a TripBlog post.
	 *
	 * @return  int      $numberOfLikes     How many thumbs ups was given by the current user.
	 */
	public function getLikes($data)
	{
		/** @var TripBlogTableLike $likeTable */
		$likeTable = Table::getInstance('Like', 'TripBlogTable');

		$likeTable->load(
			array(
				'tripblog_id'      => $data['tripblog_id'],
				'tripblog_user_id' => $data['tripblog_user_id'],
			)
		);

		if (!isset($likeTable->likes))
		{
			return 0;
		}

		$numberOfLikes = (int) $likeTable->likes;

		return $numberOfLikes;
	}
}
