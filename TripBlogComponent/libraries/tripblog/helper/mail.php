<?php
/**
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Mail\Mail;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\User\UserHelper;

defined('_JEXEC') or die('Restricted access');

/**
 * Mail helper.
 *
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */
class TripblogHelperMail
{
	/**
	 * Send mail
	 *
	 * @param   Array     $recipients  	Email's recipients
	 * @param   string    $subject  	Email title
	 * @param   string    $body  	    Email content
	 *
	 * @return  boolean   $result 		Whether email was sent successfully or not
	 */
	public static function sendEmail($recipients, $subject, $body)
	{
		$app = Factory::getApplication();

		$mailer  = Mail::getInstance();
		$configs = Factory::getConfig();

		$mailer->setSender(array($configs->get('mailfrom')));
		$mailer->addRecipient($recipients);
		$mailer->setSubject($subject);
		$mailer->setBody($body);

		if ($configs->get('mailer') !== 'smtp')
		{
			$app->enqueueMessage(Text::sprintf('COM_TRIPBLOG_SETUP_SMTP', Uri::root() . 'administrator/index.php?option=com_config'), 'warning');

			return false;
		}

		$mailer->useSmtp(
			$configs->get('smtpauth'),
			$configs->get('smtphost'),
			$configs->get('smtpuser'),
			$configs->get('smtppass'),
			$configs->get('smtpsecure'),
			$configs->get('smtpport')
		);

		$result = $mailer->Send();

		return $result;
	}
}
