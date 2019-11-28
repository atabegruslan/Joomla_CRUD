<?php
/**
 * @package   TripBlog.Site
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;

JLoader::setup();

// Loads main file of a library. libraries/tripblog/library.php
JLoader::import('tripblog.library');

// Adds to autoload all classes inside of components/site and adds a specific prefix related to them.
// All files have to have this prefix, this is a Joomla requirement.
// If not, then when u call for instance TripBlogControllerTrip it returns fatal error class not found.
JLoader::registerPrefix('Tripblog', __DIR__);

PluginHelper::importPlugin('content');
PluginHelper::importPlugin('extension');
PluginHelper::importPlugin('system');
PluginHelper::importPlugin('tripblog');

$controller = BaseController::getInstance('TripBlog');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
