<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Models
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Crypt\Key;
use Joomla\CMS\Crypt\Cipher\CryptoCipher;
use Joomla\CMS\Crypt\Crypt;
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Useraccount Model
 *
 * @package     TripBlog.Frontend
 * @subpackage  Models
 */
class TripBlogModelUseraccount extends AdminModel
{
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
			'load_data' => false
		);

		$form = $this->loadForm('com_tripblog.useraccount', 'useraccount', $options);

		if (empty($form))
		{
			return false;
		}

		return $form;
	}
}
