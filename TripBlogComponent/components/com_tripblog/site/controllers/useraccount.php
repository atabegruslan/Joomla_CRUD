<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Controllers
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\User\UserHelper;
use Joomla\CMS\MVC\View\HtmlView;
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
use Joomla\CMS\User\User;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Useraccount Controller
 *
 * @package     TripBlog.Frontend
 * @subpackage  Controllers
 */
class TripBlogControllerUseraccount extends FormController
{
	/**
	 * User login.
	 *
	 * @return  void.
	 */
	public function login()
	{
		// Check form token
		Session::checkToken('post') or jexit(Text::_('JINVALID_TOKEN'));

		$app     = Factory::getApplication();
		$input   = $app->input;
		$method  = $input->getMethod();

		// Get the log in options.
		$options = array(
			'remember' => $this->input->getBool('remember', false)
		);

		$data = $input->{$method}->get('jform', array(), 'array');

		// Get the log in credentials.
		$credentials = array(
			'username' => $data['username'],
			'password' => $data['password']
		);

		// Perform the log in.
		if ($app->login($credentials, $options) === true)
		{
			$this->setRedirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
		}

		$this->setRedirect(Route::_('index.php?option=com_tripblog&view=useraccount'));
	}

	/**
	 * User register.
	 *
	 * @return  void.
	 */
	public function register()
	{
		$app              = Factory::getApplication();
		$input            = $app->input;
		$postData         = $input->post;
		$files            = $input->files->get('jform', array(), 'array');
		$editedImage      = $postData->get('edited_image', '', 'raw');
		$imgUrl           = TripblogHelperMedia::saveImage($files, 'user', $editedImage);
		$data             = $postData->get('jform', array(), 'array');
		$extConfigParams  = $app->getParams('com_tripblog');
		$sendRegEmail     = (boolean) $extConfigParams->get('send_registration_email');
		$token            = ApplicationHelper::getHash(UserHelper::genRandomPassword());
		/* @var TripBlogModelUser $userModel */
		$userModel        = AdminModel::getInstance('User', 'TripBlogModel');
		/* @var TripBlogModelUseraccount $useraccountModel */
		$useraccountModel = AdminModel::getInstance('Useraccount', 'TripBlogModel');

		if (!empty($imgUrl))
		{
			$data['image'] = $imgUrl;
		}

		if ($sendRegEmail)
		{
			$data['activation'] = $token;
			$data['block']      = 1;
			$data['sendEmail']  = 1;
		}
		else
		{
			$data['activation'] = '';
			$data['block']      = 0;
			$data['sendEmail']  = 0;
		}

		if (!$useraccountModel->getForm()->validate($data) || !$userModel->save($data))
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_REGISTRATION_SAVE_FAILURE'), 'error');
			$app->redirect(Route::_('index.php?option=com_tripblog&view=useraccount'));
		}

		if ($sendRegEmail)
		{
			$recipients = array($data['email']);
			$subject    = Text::_('COM_TRIPBLOG_USER_REGISTRATION_EMAIL');
			$body       = Uri::root() . 'index.php?option=com_tripblog&task=useraccount.activate&token=' . $token;

			if (!TripblogHelperMail::sendEmail($recipients, $subject, $body))
			{
				$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_REGISTRATION_ERROR_COULD_NOT_SEND_EMAIL'), 'error');
				$app->redirect(Route::_('index.php?option=com_tripblog&view=useraccount'));
			}
			else
			{
				$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_REGISTRATION_ADMIN_ACTIVATE_SUCCESS'), 'success');
				$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
			}
		}
		else
		{
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
		}
	}

	/**
	 * Activate user.
	 *
	 * @return  boolean.
	 */
	public function activate()
	{
		$app   = Factory::getApplication();
		$input = $app->input;
		$token = $input->getAlnum('token');
		$db    = Factory::getDBO();

		$query = $db->getQuery(true)
			->select($db->qn('id'))
			->from($db->qn('#__users'))
			->where($db->qn('block') . ' = 1')
			->where($db->qn('activation') . ' = ' . $db->q($token));

		$userId = (int) $db->setQuery($query)->loadResult();

		if (!$userId)
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_ACTIVATION_TOKEN_NOT_FOUND'), 'error');

			return false;
		}

		// Activate the user.
		$user = Factory::getUser($userId);
		$user->set('activation', '');
		$user->set('block', '0');

		if (!$user->save())
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_REGISTRATION_ACTIVATION_SAVE_FAILED'), 'error');

			return false;
		}

		$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_REGISTRATION_ACTIVATE_SUCCESS'), 'success');
		$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

		return true;
	}

	/**
	 * Send password change email.
	 *
	 * @return  boolean.
	 */
	public function emailPasswordChange()
	{
		// Check form token
		Session::checkToken('post') or jexit(Text::_('JINVALID_TOKEN'));

		$app   = Factory::getApplication();
		$input = $app->input;
		$data  = $input->post->get('jform', array(), 'array');
		$email = $data['change_password']['email'];

		/* @var TripBlogModelUseraccount $useraccountModel */
		$useraccountModel = AdminModel::getInstance('Useraccount', 'TripBlogModel');

		// Check if email exists in system
		$user = TripblogHelperUser::getTripblogUser(array('email' => trim($email)));

		if (is_null($user) || !isset($user->joomla_id) || !$useraccountModel->getForm()->validate($data, 'change_password'))
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_INVALID_EMAIL'), 'error');
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

			return false;
		}

		$token      = ApplicationHelper::getHash(UserHelper::genRandomPassword());
		$joomlaUser = User::getInstance($user->joomla_id);
		$userData   = array('activation' => $token);
		$joomlaUser->bind($userData);

		if (!$joomlaUser->save())
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_SAVE_TOKEN_ERROR'), 'error');
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

			return false;
		}

		$recipients = array($email);
		$subject    = Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_EMAIL');
		$body       = Uri::root() . 'index.php?option=com_tripblog&task=useraccount.setPassword&token=' . $token;

		if (!TripblogHelperMail::sendEmail($recipients, $subject, $body))
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_EMAILING_ERROR'), 'error');
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

			return false;
		}

		$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_EMAILING_SUCCESS'), 'success');
		$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

		return true;
	}

	/**
	 * Display the reset password page.
	 *
	 * @return  boolean.
	 */
	public function setPassword()
	{
		$app   = Factory::getApplication();
		$input = $app->input;
		$token = $input->get('token');

		// check token
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select(array('*'));
		$query->from($db->qn('#__users'));
		$query->where($db->qn('activation') . ' = ' . $db->q($token));

		$joomlaUser = $db->setQuery($query)->loadObject();

		if (is_null($joomlaUser))
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_USER_NOT_FOUND_BY_TOKEN'), 'error');
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

			return false;
		}

		$app->redirect(Route::_('index.php?option=com_tripblog&view=useraccount&layout=setpassword&token=' . $token));

		return true;
	}

	/**
	 * Reset password.
	 *
	 * @return  boolean.
	 */
	public function changePassword()
	{
		$app   = Factory::getApplication();
		$input = $app->input;
		$data  = $input->post->get('jform', array(), 'array');

		/* @var TripBlogModelUseraccount $useraccountModel */
		$useraccountModel = AdminModel::getInstance('Useraccount', 'TripBlogModel');

		if (!$useraccountModel->getForm()->validate($data, 'set_password'))
		{
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

			return false;
		}

		$username  = $data['set_password']['username'];
		$password  = $data['set_password']['password'];
		$password2 = $data['set_password']['password2'];
		$token     = $input->post->get('token');

		// db change pass
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select($db->qn('id'));
		$query->from($db->qn('#__users'));
		$query->where($db->qn('activation') . ' = ' . $db->q($token));
		$query->where($db->qn('username') . ' = ' . $db->q($username));

		$joomlaUserId = (int) $db->setQuery($query)->loadResult();

		if (!$joomlaUserId)
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_USER_NOT_FOUND_SO_CANNOT_CHANGE'), 'error');
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

			return false;
		}

		$joomlaUser = User::getInstance($joomlaUserId);
		$userData   = array(
			'password'   => $password,
			'password2'  => $password2,
			'activation' => ''
		);

		$joomlaUser->bind($userData);

		if (!$joomlaUser->save())
		{
			$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_UPDATE_FAIL'), 'error');
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

			return false;
		}

		$app->enqueueMessage(Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_UPDATE_SUCCESS'), 'success');
		$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));

		return true;
	}
}
