<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Controllers
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Users Controller
 *
 * @package     TripBlog.Backend
 * @subpackage  Controllers
 */
class TripBlogControllerUsers extends AdminController
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
	public function getModel($name = 'Users', $prefix = 'TripBlogModel', $config = array())
	{
		/* @var TripBlogModelUsers $model */
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to remove a record.
	 *
	 * @return  void
	 */
	public function delete()
	{
		$app    = Factory::getApplication();
		$cids   = $app->input->getInt('cid');
		$model  = $this->getModel();
		$images = TripblogHelperMedia::getImagesForDeletion('#__tripblog_user', $cids);

		if ($model->delete($cids))
		{
			TripblogHelperMedia::deleteImages($images);

			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_DELETE_SUCCESS'), 'success');
		}
		else
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_DELETE_ERROR'), 'error');
		}

		$this->setRedirect(Route::_('index.php?option=com_tripblog&view=users', false));
	}
}
