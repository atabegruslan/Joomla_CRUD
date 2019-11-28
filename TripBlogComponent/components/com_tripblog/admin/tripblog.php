<?php
/**
 * @package   TripBlog.Admin
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

JLoader::import('tripblog.library');

JLoader::registerPrefix('Tripblog', __DIR__);

$controller = BaseController::getInstance('TripBlog');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
