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
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * TripBlogs View
 *
 * @package     TripBlog.Frontend
 * @subpackage  Views
 */
class TripBlogViewTripBlogs extends HtmlView
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
		/** @var TripBlogModelTrips $model */
		$model                  = ListModel::getInstance('Trips', 'TripBlogModel');
		$app                    = Factory::getApplication();
		$context                = "tripblog.list.tripblog";
		$this->items            = $model->getItems();
		$this->pagination       = $model->getPagination();
		$this->state            = $model->getState();
		$this->filterForm       = $model->getFilterForm();
		$this->activeFilters    = $model->getActiveFilters();
		$this->filter_order 	= $app->getUserStateFromRequest($context . '.filter_order', 'filter_order', 't.place', 'cmd');
		$this->filter_order_Dir = $app->getUserStateFromRequest($context . '.filter_order_Dir', 'filter_order_Dir', 'asc', 'cmd');

		$this->stoolsOptions['searchField'] = 'search_tripblogs';

		$this->setDocument();

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

		$bar->appendButton('Standard', 'save', 'New', 'trip.add', false);
		$bar->appendButton('Separator');
		$bar->appendButton('Standard', 'cancel', 'Delete', 'trips.delete', false);

		$barHtml = $bar->render();

		return $barHtml;
	}

	/**
	 * Set the document
	 *
	 * @return  void
	 */
	protected function setDocument()
	{
		$document = Factory::getDocument();
		$document->setTitle(Text::_('COM_TRIPBLOG_TITLE'));
	}
}
