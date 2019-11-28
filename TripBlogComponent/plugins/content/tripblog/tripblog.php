<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.TripBlog
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\User\UserHelper;
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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Content plugin for TripBlog
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.TripBlog
 */
class PlgContentTripblog extends CMSPlugin
{
	/**
	 * The plugin's path
	 *
	 * @var   string
	 */
	protected $pluginPath = JPATH_PLUGINS . '/content/tripblog';

	/**
	 * Plugin that retrieves contact information for contact
	 *
	 * @param   string   $context   The context of the content being passed to the plugin.
	 * @param   mixed    $article   An object with a "text" property
	 * @param   mixed    $params    Additional parameters. See {@see PlgContentContent()}.
	 * @param   integer  $page      Optional page number. Unused. Defaults to zero.
	 *
	 * @return  boolean    True on success.
	 */
	public function onContentPrepare($context, &$article, $params, $page = 0)
	{
		$regex = "#{tripblog}(.*?){/tripblog}#s";

		if (preg_match_all($regex, $article->text, $matches))
		{
			foreach ($matches[1] as $k => $match)
			{
				if ($match === 'header' || $match === 'footer')
				{
					$article->text = str_replace(
						$matches[0][$k],
						LayoutHelper::render(
							$match,
							array(),
							JPATH_SITE . '/plugins/content/tripblog/layouts'
						),
						$article->text
					);
				}
			}
		}
	}

	/**
	 * The form event. Load additional parameters when available into the field form.
	 * Only when the type of the form is of interest.
	 *
	 * @param   Form      $form  The form
	 * @param   stdClass  $data  The data
	 *
	 * @return  void
	 */
	public function onContentPrepareForm($form, $data)
	{
		$app   = Factory::getApplication();
		$input = $app->input;

		if ($input->get('option') === 'com_categories'
			&& $input->get('view') === 'category'
			&& $input->get('layout') === 'edit'
			&& !$app->isClient('site'))
		{
			Form::addFieldPath(JPATH_SITE . '/libraries/tripblog/form/field');
			$form->loadFile($this->pluginPath . '/forms/continent.xml', false);
		}

		return true;
	}

	/**
	 * The save event.
	 *
	 * @param   string   $context  The context
	 * @param   JTable   $item     The article data
	 * @param   boolean  $isNew    Is new item
	 * @param   array    $data     The validated data
	 *
	 * @return  void
	 */
	public function onContentAfterSave($context, $item, $isNew, $data = array())
	{
		$app         = Factory::getApplication();
		$input       = $app->input;
		$continentId = $item->id;
		$countryIds  = $input->get('continent', array(), 'array');

		if ($context === 'com_categories.category'
			&& $item->get('extension') === 'com_tripblog'
			&& !$app->isClient('site')
			&& !empty($countryIds))
		{
			$db    = Factory::getDbo();
			$query = $db->getQuery(true)
				->delete($db->qn('#__tripblog_country_continent_xref'))
				->where('continent_id = ' . $db->q($continentId))
				->where('country_id NOT IN (' . implode($countryIds) . ')');

			$db->setQuery($query)->execute();

			$query = $db->getQuery(true)
				->select($db->qn('country_id'))
				->from($db->qn('#__tripblog_country_continent_xref'))
				->where('continent_id = ' . $db->q($continentId));

			$existCountryIds = $db->setQuery($query)->loadColumn();
			$countryIds      = array_diff($countryIds, $existCountryIds);

			foreach ($countryIds as $countryId)
			{
				$query = $db->getQuery(true)
					->insert($db->qn('#__tripblog_country_continent_xref'))
					->columns($db->qn(array('continent_id', 'country_id')))
					->values(implode(',', array($db->q($continentId), $db->q($countryId))));

				$db->setQuery($query)->execute();
			}
		}
	}
}