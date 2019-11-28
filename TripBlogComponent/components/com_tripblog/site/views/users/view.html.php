<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Views
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

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
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Users View
 *
 * @package     TripBlog.Frontend
 * @subpackage  Views
 */
class TripBlogViewUsers extends HtmlView
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
		$app = Factory::getApplication();
		
		if (!TripblogHelperPermission::isSuper())
		{
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
		}

		/** @var TripBlogModelUsers $model */
		$model = ListModel::getInstance('Users', 'TripBlogModel');

		$items = $model->getItems();
		//$pagination = $model->get('Pagination');

		$this->items = $items;
		//$this->pagination = $pagination;

		parent::display($tpl);
	}

	/**
	 * Get the toolbar to render.
	 *
	 * @return  string   $barHtml   HTML string of toolbar.
	 */
	protected function getToolbar()
	{
		$bar = Toolbar::getInstance('toolbar');

		$bar->appendButton('Standard', 'save', 'New', 'user.add', false);
		$bar->appendButton('Separator');
		$bar->appendButton('Standard', 'cancel', 'Delete', 'users.delete', false);

		$barHtml = $bar->render();

		return $barHtml;
	}
}
