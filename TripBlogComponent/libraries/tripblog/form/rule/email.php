<?php
/**
 * @package     TripBlog
 * @subpackage  Rule
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormRule;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;
use Joomla\CMS\Form\Form;

defined('JPATH_PLATFORM') or die;

/**
 * Form Rule class for the TripBlog Extension.
 *
 * @package     TripBlog
 * @subpackage  Rules
 */
class JFormRuleEmail extends FormRule
{
	/**
	 * Method to validate email.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 * @param   Registry         $input    An optional JRegistry object with the entire data set to validate against the entire form.
	 * @param   Form             $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		$app = Factory::getApplication();

		if (!filter_var($value, FILTER_VALIDATE_EMAIL))
		{
			$app->enqueueMessage(Text::_(((array) $element['message'])[0]), 'error');

			return false;
		}

		return true;
	}
}
