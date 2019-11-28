<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Views
 */

defined('_JEXEC') or die('Restricted access');

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
use Joomla\CMS\MVC\Model\AdminModel;

/**
 * Useraccount View
 *
 * @package     TripBlog.Frontend
 * @subpackage  Views
 */
class TripBlogViewUseraccount extends HtmlView
{
	/**
	 * Display method
	 *
	 * @param   string  $tpl  The template name
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		/** @var TripBlogModelUseraccount $model */
		$model = AdminModel::getInstance('Useraccount', 'TripBlogModel');
		$app   = Factory::getApplication();

		$form = $model->getForm();
		//$item = $model->getItem();

		$this->form = $form;
		//$this->item = $item;

		$layout = $this->getLayout();

		switch ($layout)
		{
			case 'default':

        		break;
			case 'changepassword':

				break;
			case 'profile':

				if (!TripblogHelperPermission::isLoggedIn())
				{
					$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
				}

				break;
			case 'community':

				if (!TripblogHelperPermission::isLoggedIn())
				{
					$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
				}

				break;
			case 'setpassword':

				$this->token = $app->input->get('token');

				break;
			default:

		}

		parent::display($tpl);
	}
}
