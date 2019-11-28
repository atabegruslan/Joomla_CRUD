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
class JFormRuleLogin extends FormRule
{
	/**
	 * Method to test the username for uniqueness.
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
		if ($input->get('id') !== '' && $input->get('joomla_id') !== '')
		{
			return true;
		}

		$app   = Factory::getApplication();
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);

		// Build the query.
		$query->select('COUNT(*)')
			->from('#__users')
			->where('username = ' . $db->q($value));

		$userId = ($form instanceof Form) ? $form->getValue('id') : '';
		$query->where($db->qn('id') . ' <> ' . $db->q($userId));

		$duplicate = (bool) $db->setQuery($query)->loadResult();

		if ($duplicate)
		{
			$app->enqueueMessage(Text::_(((array) $element['message'])[0]), 'error');

			return false;
		}

		return true;
	}
}
