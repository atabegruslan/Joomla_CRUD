<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Extension.TripBlog
 */

defined('JPATH_BASE') or die;

use Joomla\Registry\Registry;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Table\Table;

/**
 * Extension plugin for TripBlog
 *
 * @package     Joomla.Plugin
 * @subpackage  Extension.TripBlog
 */
class PlgExtensionTripblog extends CMSPlugin
{
	/**
	 * On Saving extensions logging method
	 * Method is called before an extension is being saved
	 *
	 * @param   string   $context  The extension
	 * @param   Table   $table    DataBase Table object
	 * @param   boolean  $isNew    If the extension is new or not
	 *
	 * @return  void
	 */
	public function onExtensionBeforeSave($context, $table, $isNew)
	{

	}

	/**
	 * On Saving extensions logging method
	 * Method is called after an extension is being saved
	 *
	 * @param   string   $context  The extension
	 * @param   Table   $table    DataBase Table object
	 * @param   boolean  $isNew    If the extension is new or not
	 *
	 * @return  void
	 */
	public function onExtensionAfterSave($context, $table, $isNew)
	{
		$app   = Factory::getApplication();
		$input = $app->input;

		if ($context === 'com_plugins.plugin'
			&& $table->type === 'plugin'
			&& $table->folder === 'tripblog'
			&& $table->element === 'watermarker')
		{
			$registry = new Registry;
			$registry->loadString($table->params);
			$params = $registry->toArray();

			// var_dump($input->post->getArray());
			// var_dump(file_get_contents('php://input'));
			// var_dump($_POST);

			$tmp = $params['stamp']; //images/xxx.jpg

			$src  = JPATH_SITE . '/' . $tmp;
			$dest = JPATH_SITE . '/media/plg_tripblog_watermarker/image/fav.png';

			JFile::move($src, $dest);
		}
	}
}