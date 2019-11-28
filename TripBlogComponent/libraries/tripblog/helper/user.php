<?php
/**
 * @package     TripBlog.Libraries
 * @subpackage  Helper
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

/**
 * User helper.
 *
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */
class TripblogHelperUser
{
	/**
	 * Method to get an TripBlog user
	 *
	 * @param   Array   $keyValuePairs      Key and value for specifying an user
	 *
	 * @return  Object  $tripBlogUser 	 	Retrieved user object
	 */
	public static function getTripblogUser($keyValuePairs)
	{
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select(array('*'));
		$query->from($db->qn('#__tripblog_user'));

		foreach ($keyValuePairs as $key => $value)
		{
			$query->where($db->qn($key) . ' = ' . $db->q($value));
		}

		$tripBlogUser = $db->setQuery($query)->loadObject();

		return $tripBlogUser;
	}

	/**
	 * Method to get all TripBlog extension's users
	 *
	 * @return  Array  $tripBlogUsers   TripBlog users
	 */
	public static function getTripblogAllUsers()
	{
		$db        = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select(array('*'));
		$query->from($db->qn('#__tripblog_user'));

		$tripBlogUsers = $db->setQuery($query)->loadObjectList();

		return $tripBlogUsers;
	}

	/**
	 * Method to get an encrypted password with salt
	 *
	 * @param   string  $password       	Password (password should be already encrypted)
	 *
	 * @return  string  $encryptedPassword 	New generated encrypted password
	 */
	public static function encryptPassword($password)
	{
		$encryptionKey   = self::getSecret();

		$iv    = openssl_random_pseudo_bytes(openssl_cipher_iv_length("AES-256-CBC"));
		$crypt = openssl_encrypt($password, "AES-256-CBC", $encryptionKey, OPENSSL_RAW_DATA, $iv);

		$encryptedPassword = base64_encode($iv . $crypt);

		return $encryptedPassword;
	}

	/**
	 * Method to get an password from encryption with salt
	 *
	 * @param   string  $password       	Password that was previously encrypted with same salt key
	 *
	 * @return  string  $decryptedPassword 	Joomla Password
	 */
	public static function decryptPassword($password)
	{
		$encryptionKey   = self::getSecret();
		$decodedPassword = base64_decode($password);

		$ivlen = openssl_cipher_iv_length("AES-256-CBC");
		$iv    = substr($decodedPassword, 0, $ivlen);
		$crypt = substr($decodedPassword, $ivlen);

		$decryptedPassword = openssl_decrypt($crypt, "AES-256-CBC", $encryptionKey, OPENSSL_RAW_DATA, $iv);
		
		return $decryptedPassword;
	}

	/**
	 * Method to get secret from config file (for use as salt)
	 *
	 * @return  string  $secret   The secret from config file
	 */
	protected static function getSecret()
	{
		$configs = Factory::getConfig();
		$secret  = $configs->get('secret');

		return $secret;
	}
}
