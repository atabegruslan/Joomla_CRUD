<?php
/**
 * @package     TripBlog.Backend
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
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Toolbar\Toolbar;

/**
 * TripBlog View
 *
 * @package     TripBlog.Backend
 * @subpackage  Views
 */
class TripBlogViewTripBlog extends HtmlView
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

		if (!TripblogHelperPermission::canEditOwn())
		{
			$app->redirect(Route::_('index.php?option=com_tripblog&view=tripblogs'));
		}

		/** @var TripBlogModelTrip $model */
		$model = AdminModel::getInstance('Trip', 'TripBlogModel');

		$form = $model->getForm();
		$item = $model->getItem();

		TripblogHelperPermission::checkin('trip', $item->id, true);

		$this->form = $form;
		$this->item = $item;

		$this->addToolBar();

		parent::display($tpl);

		$this->setDocument();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 */
	protected function addToolBar()
	{
		$title = Text::_('COM_TRIPBLOG_MANAGER_TRIPBLOG');

		JToolBarHelper::title($title, 'tripblog');
		JToolBarHelper::save('trip.save');
		JToolBarHelper::cancel('trip.cancel');
	}

	/**
	 * Set the document
	 *
	 * @return  void
	 */
	protected function setDocument()
	{
		$document = Factory::getDocument();
		$document->setTitle(Text::_('COM_TRIPBLOG_ADMINISTRATION'));
	}
}
