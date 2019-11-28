<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Controllers
 */

defined('_JEXEC') or die('Restricted access');

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
use Joomla\CMS\MVC\Controller\FormController;

/**
 * Trip Controller
 *
 * @package     TripBlog.Frontend
 * @subpackage  Controllers
 */
class TripBlogControllerTrip extends FormController
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

		$app             = Factory::getApplication();
		$input           = $app->input;
		$postData        = $input->post;
		$files           = $input->files->get('jform', array(), 'array');
		$editedImage     = $postData->get('edited_image', '', 'raw');
		$imgUrl          = TripblogHelperMedia::saveImage($files, 'trip', $editedImage);
		$data            = $postData->get('jform', array(), 'array');
		$data['created'] = Factory::getUser()->id;

		if (!empty($imgUrl))
		{
			$data['image'] = $imgUrl;
		}

		$model = $this->getModel();
		$table = $model->getTable();

		if (empty($data['id']))
		{
			$data['alias'] = TripblogHelperTripblog::generateAlias($data['place']);
		}
		else
		{
			$table->load(
				array(
					'id' => $data['id']
				)
			);

			if (empty($table->alias))
			{
				$data['alias'] = TripblogHelperTripblog::generateAlias($data['place']);
			}
		}

		$model->save($data);

		if (!empty($data['id']))
		{
			TripblogHelperPermission::checkin('trip', $data['id'], false);
		}

		$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs', false));
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

		$app      = Factory::getApplication();
		$url      = Route::_('index.php?option=com_tripblog&view=tripblogs', false);
		$recordId = $app->input->post->get('id');

		if (!empty($recordId))
		{
			TripblogHelperPermission::checkin('trip', $recordId, false);
		}

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
		$url = Route::_('index.php?option=com_tripblog&view=tripblog&layout=edit', false);

		$app->redirect($url);
	}

	/**
	 * AJAX process like.
	 *
	 * @return  void
	 */
	public function ajaxLike()
	{
		$app          = Factory::getApplication();
		$input        = $app->input;
		$likes        = (int) $input->post->get('likes', 0);
		$tripblogId   = (int) $input->post->get('tripblogId', 0);
		$joomlaUser   = Factory::getUser();
		$returnObject = new stdClass;

		if ($joomlaUser->id === 0)
		{
			$returnObject->message      = Text::_("COM_TRIPBLOG_LIKE_NOT_LOGGED_IN");
			$returnObject->hasSucceeded = 0;

			header('HTTP/1.1 500 Internal Server Error');
			echo json_encode($returnObject);

			$app->close();
		}

		$keyValueIdentifier = array('joomla_id' => $joomlaUser->id);
		$tripBlogUser       = TripblogHelperUser::getTripblogUser($keyValueIdentifier);
		$tripBlogUserId     = $tripBlogUser->id;

		if (!$tripBlogUserId)
		{
			$isSuper                    = $joomlaUser->authorise('core.admin');
			$returnObject->message      = $isSuper ? Text::_("COM_TRIPBLOG_LIKE_SUPERUSER_READONLY") : Text::_("COM_TRIPBLOG_LIKE_TRIPBLOG_USER_ID_NOT_FOUND");
			$returnObject->hasSucceeded = 0;

			header('HTTP/1.1 500 Internal Server Error');
			echo json_encode($returnObject);

			$app->close();
		}

		$model = $this->getModel();
		$data  = array(
			'tripblog_id'      => $tripblogId,
			'tripblog_user_id' => $tripBlogUserId,
			'likes'            => $likes,
		);

		$result = $model->saveLikes($data);

		if ($result === 0)
		{
			$returnObject->message      = Text::_("COM_TRIPBLOG_LIKE_SAVE_FAIL");
			$returnObject->hasSucceeded = 0;

			header('HTTP/1.1 500 Internal Server Error');
			echo json_encode($returnObject);

			$app->close();
		}

		$likeTally    = TripblogHelperTripblog::getLikeTally($tripblogId);
		$numberPeople = 0.0;
		$numberLikes  = 0.0;

		foreach ($likeTally as $likeEntry)
		{
			$numberPeople += (float) $likeEntry->users;
			$numberLikes  += (float) $likeEntry->likes;
		}

		$averageLikes    = $numberLikes / $numberPeople;
		$tallyLayoutData = array('data' => $likeTally);

		$returnObject->message      = Text::_("COM_TRIPBLOG_LIKE_SAVE_SUCCESS");
		$returnObject->newValue     = $result;
		$returnObject->hasSucceeded = 1;
		$returnObject->averageLikes = Text::sprintf('COM_TRIPBLOG_AVERAGE_LIKES', $averageLikes);
		$returnObject->likesTable   = LayoutHelper::render('tripblog.like.tally', $tallyLayoutData);

		echo json_encode($returnObject);

		$app->close();
	}

	/**
	 * AJAX set initial like value to the previously set value, if exist.
	 *
	 * @return  void
	 */
	public function ajaxInitLike()
	{
		$app          = Factory::getApplication();
		$input        = $app->input;
		$tripblogId   = (int) $input->post->get('tripblogId', 0);
		$joomlaUser   = Factory::getUser();
		$returnObject = new stdClass;

		if ($joomlaUser->id === 0)
		{
			$returnObject->message      = Text::_("COM_TRIPBLOG_LIKE_NOT_LOGGED_IN");
			$returnObject->hasSucceeded = 0;

			header('HTTP/1.1 500 Internal Server Error');
			echo json_encode($returnObject);

			$app->close();
		}

		$keyValueIdentifier = array('joomla_id' => $joomlaUser->id);
		$tripBlogUser       = TripblogHelperUser::getTripblogUser($keyValueIdentifier);
		$tripBlogUserId     = $tripBlogUser->id;

		if (!$tripBlogUserId)
		{
			$isSuper                    = $joomlaUser->authorise('core.admin');
			$returnObject->message      = $isSuper ? Text::_("COM_TRIPBLOG_LIKE_SUPERUSER_READONLY") : Text::_("COM_TRIPBLOG_LIKE_TRIPBLOG_USER_ID_NOT_FOUND");
			$returnObject->hasSucceeded = 0;

			header('HTTP/1.1 500 Internal Server Error');
			echo json_encode($returnObject);

			$app->close();
		}

		$model = $this->getModel();
		$data  = array(
			'tripblog_id'      => $tripblogId,
			'tripblog_user_id' => $tripBlogUserId,
		);

		$likes = $model->getLikes($data);

		$returnObject->message      = Text::_("COM_TRIPBLOG_LIKE_SAVE_SUCCESS");
		$returnObject->likes        = $likes;
		$returnObject->hasSucceeded = 1;

		echo json_encode($returnObject);

		$app->close();
	}

	/**
	 * Get the likes tally table.
	 *
	 * @return  void
	 */
	public function ajaxGetTally()
	{
		$app                 = Factory::getApplication();
		$input               = $app->input;
		$tripblogId          = (int) $input->post->get('tripblogId', 0);
		$returnObject        = new stdClass;
		$data                = TripblogHelperTripblog::getLikeTally($tripblogId);
		$returnObject->tally = LayoutHelper::render('tripblog.like.tally', array('data' => $data));

		echo json_encode($returnObject);

		$app->close();
	}
}
