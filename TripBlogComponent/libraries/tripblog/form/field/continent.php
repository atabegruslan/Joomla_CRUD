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

jimport('tripblog.helper.country');
jimport('joomla.form.helper');
FormHelper::loadFieldClass('list');

/**
 * Continent Field
 *
 * @package     TripBlog
 * @subpackage  Field
 */
class JFormFieldContinent extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	public $type = 'Continent';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string 	$field   The field input markup.
	 */
	public function getInput()
	{
		//$attrStr = 'class="continent selectpicker" multiple="true"';
		$attrArr = array(
			'list.attr' => array(
				'class'    => 'continent selectpicker',
				'multiple' => 'true'
			)
		);

		$options  = $this->getOptions();
		$selected = $this->getSelected();
		$html[]   = HTMLHelper::_('select.genericlist', $options, 'continent[]', $attrArr, 'value', 'text', $selected);
		$field    = implode($html);

		return $field;
	}

	/**
	 * Method to get the options to populate list
	 *
	 * @return  array   $options   The field option objects.
	 */
	protected function getOptions()
	{
		$countries = TripblogHelperCountry::getCountries();
		$options   = array();

		if (!empty($countries))
		{
			foreach ($countries as $country)
			{
				$options[] = HTMLHelper::_('select.option', $country->id, Text::_($country->name));
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}

	/**
	 * Method to get the previously selected countries, if exists.
	 *
	 * @return  Array  $currentCountryIds   List of country IDs
	 */
	protected function getSelected()
	{
		$currentContinentId = (int) $this->getLayoutData()['field']->form->getData()->get('id');

		if ($currentContinentId === 0)
		{
			return array();
		}

		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('country_id'))
			->from($db->qn('#__tripblog_country_continent_xref'))
			->where($db->qn('continent_id') . ' = ' . $db->q($currentContinentId));

		$currentCountryIds = $db->setQuery($query)->loadColumn();

		return $currentCountryIds;
	}
}
