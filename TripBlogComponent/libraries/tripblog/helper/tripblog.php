<?php
/**
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */

defined('_JEXEC') or die('Restricted access');

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

/**
 * Tripblog helper.
 *
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */
class TripblogHelperTripblog
{
	/**
	 * Method to get a post's likes tally
	 *
	 * @param   int     $blogId		        TripBlog post's ID
	 *
	 * @return  Array   $tally      	 	Tally of likes for a TripBlog post
	 */
	public static function getLikeTally($blogId)
	{
		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select($db->qn('likes'));
		$query->select("COUNT(DISTINCT " . $db->qn('tripblog_user_id') . ") AS users");
		$query->from($db->qn('#__tripblog_likes'));
		$query->where($db->qn('tripblog_id') . ' = ' . $db->q($blogId));
		$query->group($db->qn('likes'));
		$query->order($db->qn('likes') . ' ASC');

		$tally = $db->setQuery($query)->loadObjectList();

		return $tally;
	}

	/**
	 * Generate alias for place name.
	 *
	 * @param   string     $placeName		The pre-treated place name string
	 *
	 * @return  string     $alias      	 	The unique computer-friendly alias
	 */
	public static function generateAlias($placeName)
	{
		$placeName = strtolower($placeName);
		$placeName = preg_replace( '/[^a-z0-9]/i', '_', $placeName); 

		$db    = Factory::getDBO();
		$query = $db->getQuery(true);

		$query->select($db->qn('id'));
		$query->from($db->qn('#__tripblog_trip'));
		$query->where($db->qn('alias') . ' = ' . $db->q($placeName));

		while (($db->setQuery($query, 0, 1)->loadResult()))
		{
			$i++;
			$placeName = $placeName . '_' . $i;

			$query->clear('where');
			$query->where($db->qn('alias') . ' = ' . $db->q($placeName));
		}

		$alias = $placeName;

		return $alias;
	}

	/**
	 * Generate excerpt for full review content.
	 *
	 * @param   string     $fullContent		Full review content
	 *
	 * @return  string     $excerpt      	Excerpt
	 */
	public static function generateExcerpt($fullContent)
	{
		$excerpt = mb_substr($fullContent, 0, 50) . '...';

		return $excerpt;
	}
}

?>
