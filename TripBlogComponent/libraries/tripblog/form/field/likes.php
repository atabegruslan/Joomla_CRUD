<?php
/**
 * @package     TripBlog
 * @subpackage  Field
 */

defined('JPATH_PLATFORM') or die;

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

jimport('joomla.form.helper');
FormHelper::loadFieldClass('radio');

/**
 * Likes Field
 *
 * @package     TripBlog
 * @subpackage  Field
 */
class JFormFieldLikes extends JFormFieldRadio
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'likes';

	/**
	 * The form field layout.
	 *
	 * @var    string
	 */
	protected $layout = 'field.likes';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 */
	protected function getInput()
	{
		$layout = trim($this->layout);
		$data   = array(
			'id'      => $this->id,
			'element' => $this->element,
			'field'   => $this,
			'name'    => $this->name,
			'options' => array(),
		);

		return LayoutHelper::render($layout, $data, JPATH_ROOT . '/libraries/tripblog/layouts');
	}
}
