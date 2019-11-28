<?php
/**
 * @package     TripBlog.Backend
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
 * @package     TripBlog.Backend
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

		/** @var TripBlogModelUsers $model */
		$model = ListModel::getInstance('Users', 'TripBlogModel');

		$items = $model->getItems();
		$pagination = $model->get('Pagination');

		$this->items = $items;
		$this->pagination = $pagination;

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

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
		$title = Text::_('COM_TRIPBLOG_MANAGER_USERS');

		if (is_object($this->pagination) && $this->pagination->total)
		{
			$title .= "<span style='font-size: 0.5em; vertical-align: middle;'>(" . $this->pagination->total . ")</span>";
		}

		JToolBarHelper::title($title, 'tripblog');
		JToolBarHelper::addNew('user.add', 'JTOOLBAR_NEW');
		JToolBarHelper::deleteList('', 'users.delete', 'JTOOLBAR_DELETE');
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
