<?php
/**
 * @package   TripBlog.Site
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\Router\RouterInterface;

/**
 * Routing class from com_tripblog
 */
class TripblogRouter implements RouterInterface
{
	/**
	 * Un-SEF to SEF URL.
	 *
	 * @param   Array  $query      An array of URL arguments
	 *
	 * @return  Array  $segments   The URL arguments to use to assemble the subsequent URL.
	 */
	public function build(&$query)
	{
		$segments = array();

		if (isset($query['id'])
			&& isset($query['view'])
			&& isset($query['layout'])
			&& $query['view'] === 'tripblog'
			&& $query['layout'] === 'edit')
		{
			$db  = Factory::getDbo();
			$qry = $db->getQuery(true);

			$qry->select($db->qn('alias'));
			$qry->from($db->qn('#__tripblog_trip'));
			$qry->where($db->qn('id') . ' = ' . $db->q($query['id']));

			$place = $db->setQuery($qry)->loadResult();

			$segments[] = $query['view'];
			$segments[] = $place;

			unset($query['id']);
			unset($query['view']);
			unset($query['layout']);
		}

		if (isset($query['id'])
			&& isset($query['view'])
			&& isset($query['layout'])
			&& $query['view'] === 'user'
			&& $query['layout'] === 'edit')
		{
			$db  = Factory::getDbo();
			$qry = $db->getQuery(true);

			$qry->select($db->qn('username'));
			$qry->from($db->qn('#__tripblog_user'));
			$qry->where($db->qn('id') . ' = ' . $db->q($query['id']));

			$username = $db->setQuery($qry)->loadResult();

			$segments[] = $query['view'];
			$segments[] = $username;

			unset($query['id']);
			unset($query['view']);
			unset($query['layout']);
		}

		return $segments;
	}

	/**
	 * SEF to un-SEF URL.
	 *
	 * @param   Array  $segments  The segments of the URL to parse.
	 *
	 * @return  Array  $vars      The URL attributes to be used by the application.
	 */
	public function parse(&$segments)
	{
		$vars = array();

		if (isset($segments[0]) && isset($segments[1]) && $segments[0] === 'tripblog')
		{
			$db  = Factory::getDbo();
			$qry = $db->getQuery(true);

			$qry->select($db->qn('id'));
			$qry->from($db->qn('#__tripblog_trip'));
			$qry->where($db->qn('alias') . ' = ' . $db->q($segments[1]));

			$id = $db->setQuery($qry)->loadResult();

			if(!empty($id))
			{
				$vars['id']     = $id;
				$vars['view']   = 'tripblog';
				$vars['layout'] = 'edit';
			}
		}

		if (isset($segments[0]) && $segments[0] === 'user')
		{
			$db  = Factory::getDbo();
			$qry = $db->getQuery(true);

			$qry->select($db->qn('id'));
			$qry->from($db->qn('#__tripblog_user'));
			$qry->where($db->qn('username') . ' = ' . $db->q($segments[1]));

			$username = $db->setQuery($qry)->loadResult();

			if(!empty($username))
			{
				$vars['id']     = $username;
				$vars['view']   = 'user';
				$vars['layout'] = 'edit';
			}
		}

		return $vars;
	}

	/**
	 * Generic method to preprocess a URL
	 *
	 * @param   Array  $query   An associative array of URL arguments
	 *
	 * @return  Array  $query   The URL arguments to use to assemble the subsequent URL.
	 */
	public function preprocess($query)
	{
		return $query;
	}
}
