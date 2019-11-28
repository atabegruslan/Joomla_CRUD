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
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * TripBlogs View
 *
 * @package     TripBlog.Backend
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

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		TripblogHelper::addSubmenu('tripblogs');

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
		$title = Text::_('COM_TRIPBLOG_MANAGER_TRIPBLOGS');

		if (is_object($this->pagination) && $this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'tripblog');
		JToolBarHelper::addNew('trip.add', 'JTOOLBAR_NEW');
		JToolBarHelper::deleteList('', 'trips.delete', 'JTOOLBAR_DELETE');
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_tripblog');
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
