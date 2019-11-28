<?php
/**
 * TripBlog Library file.
 * Including this file into your application will make TripBlog available to use.
 *
 * @package    TripBlog
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Language\Text;

$composerAutoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($composerAutoload))
{
	require_once $composerAutoload;
}

// Ensure that autoloaders are set
JLoader::setup();

// Global libraries autoloader
JLoader::registerPrefix('Tripblog', dirname(__FILE__));

// Make available fields
FormHelper::addFieldPath(dirname(__FILE__) . '/form/field');

// Make available form rules
FormHelper::addRulePath(dirname(__FILE__) . '/form/rule');

$lang = Factory::getLanguage();
// @todo Don't hard code en-GB
$lang->load('com_tripblog', JPATH_SITE . '/components/com_tripblog', 'en-GB');
