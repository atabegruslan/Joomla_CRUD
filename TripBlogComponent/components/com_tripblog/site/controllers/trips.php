<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Controllers
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Trips Controller
 *
 * @package     TripBlog.Frontend
 * @subpackage  Controllers
 */
class TripBlogControllerTrips extends AdminController
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   Array   $config  Configuration array for model. Optional.
	 *
	 * @return  Object  The model.
	 */
	public function getModel($name = 'Trips', $prefix = 'TripBlogModel', $config = array())
	{
		/* @var TripBlogModelTrips $model */
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Check in of one or more records.
	 *
	 * @return  boolean  True on success
	 */
	public function checkin()
	{
		// Check for request forgeries.
		$this->checkToken();

		$ids       = $this->input->post->get('cid', array(), 'array');
		/* @var TripBlogModelTrip $tripModel */
		$tripModel = AdminModel::getInstance('Trip', 'TripBlogModel');
		$return    = $tripModel->checkin($ids);
		$url       = Route::_('index.php?option=com_tripblog&view=tripblogs', false);
		$message   = $return
			? Text::plural($this->text_prefix . '_N_ITEMS_CHECKED_IN', count($ids))
			: Text::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $tripModel->getError());

		$this->setRedirect($url, $message);

		return $return;
	}

	/**
	 * Method to remove a record.
	 *
	 * @return  void
	 */
	public function delete()
	{
		$app   = Factory::getApplication();
		$cids  = $app->input->getInt('cid');
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->delete($db->qn('#__tripblog_trip'))
			->where($db->qn('id') . ' IN (' . implode(',', $cids) . ')');

		$images = TripblogHelperMedia::getImagesForDeletion('#__tripblog_trip', $cids);

		if ($db->setQuery($query)->execute())
		{
			TripblogHelperMedia::deleteImages($images);

			$app->enqueueMessage(Text::_('COM_TRIPBLOG_DELETE_SUCCESS'), 'success');
		}
		else
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_DELETE_ERROR'), 'error');
		}

		$this->setRedirect(Route::_('index.php?option=com_tripblog&view=tripblogs', false));
	}
}
