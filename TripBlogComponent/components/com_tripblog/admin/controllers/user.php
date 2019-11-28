<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Controllers
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\MVC\Controller\FormController;

/**
 * User Controller
 *
 * @package     TripBlog.Backend
 * @subpackage  Controllers
 */
class TripBlogControllerUser extends FormController
{
	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 */
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$app         = Factory::getApplication();
		$input       = $app->input;
		$postData    = $input->post;
		$files       = $input->files->get('jform', array(), 'array');
		$editedImage = $postData->get('edited_image', '', 'raw');
		$imgUrl      = TripblogHelperMedia::saveImage($files, 'user', $editedImage);
		$data        = $postData->get('jform', array(), 'array');
		$model       = $this->getModel();
		$form        = $model->getForm();

		if (!empty($imgUrl))
		{
			$data['image'] = $imgUrl;
		}

		if (!$form->validate($data))
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_REGISTRATION_SAVE_FAILURE'), 'error');

			return false;
		}

		$saveResult = $model->save($data);

		if (!$saveResult)
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_REGISTRATION_SAVE_FAILURE'), 'error');

			return false;
		}

		$app->redirect(Route::_('index.php?option=com_tripblog&view=users', false));

		return true;
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 */
	public function cancel($key = null)
	{
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		$app = Factory::getApplication();
		$url = Route::_('index.php?option=com_tripblog&view=users', false);

		$app->redirect($url);

		return true;
	}

	/**
	 * Redirect to edit page.
	 *
	 * @return  void.
	 */
	public function add()
	{
		$app = Factory::getApplication();
		$url = Route::_('index.php?option=com_tripblog&view=user&layout=edit', false);

		$app->redirect($url);
	}
}
