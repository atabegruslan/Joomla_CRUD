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
use Joomla\CMS\Toolbar\Toolbar;

/**
 * User View
 *
 * @package     TripBlog.Frontend
 * @subpackage  Views
 */
class TripBlogViewUser extends HtmlView
{
	/**
	 * Display method
	 *
	 * @param   string  $tpl  The template name
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		$app = Factory::getApplication();
		
		if (!TripblogHelperPermission::isSuper())
		{
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
		}

		/** @var TripBlogModelUser $model */
		$model = AdminModel::getInstance('User', 'TripBlogModel');

		$form = $model->getForm();
		$item = $model->getItem();

		$this->form = $form;
		$this->item = $item;

		parent::display($tpl);
	}

	/**
	 * Get the toolbar to render.
	 *
	 * @return  string   $barHtml   HTML string of toolbar.
	 */
	protected function getToolbar()
	{
		$input = Factory::getApplication()->input;
		$input->set('hidemainmenu', true);
		$isNew = ($this->item->id == 0);

		$bar = Toolbar::getInstance('toolbar');

		$bar->appendButton('Standard', 'save', 'Save', 'user.save', false);
		$bar->appendButton('Separator');
		$bar->appendButton('Standard', 'cancel', $isNew ? 'Close' : 'Cancel', 'user.cancel', false);

		$barHtml = $bar->render();

		return $barHtml;
	}
}
