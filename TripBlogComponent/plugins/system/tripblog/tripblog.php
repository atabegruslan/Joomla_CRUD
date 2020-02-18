<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.TripBlog
 */

defined('JPATH_BASE') or die;

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
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Application\SiteApplication;

/**
 * System plugin for TripBlog
 *
 * @package     Joomla.Plugin
 * @subpackage  System.TripBlog
 */
class PlgSystemTripblog extends CMSPlugin
{
    /**
     * This event is triggered before the framework creates the Head section of the Document.
     *
     * @return  void
     */
    public function onBeforeCompileHead()
    {
        $app      = Factory::getApplication();
        $document = Factory::getDocument();

        // Front only
        if($app instanceof SiteApplication)
        {
            // unset($document->_scripts[Uri::root(true) . '/media/system/js/mootools-core-uncompressed.js']);
            // unset($document->_scripts[Uri::root(true) . '/media/system/js/core-uncompressed.js']);
            // unset($document->_scripts[Uri::root(true) . '/media/system/js/mootools-more-uncompressed.js']);

            unset($document->_scripts[Uri::root(true) . '/media/jui/js/jquery.js']);
            unset($document->_scripts[Uri::root(true) . '/media/jui/js/jquery.min.js']);
            unset($document->_scripts[Uri::root(true) . '/media/jui/js/bootstrap.js']);
            unset($document->_scripts[Uri::root(true) . '/media/jui/js/bootstrap.min.js']);

            // unset($document->_scripts[Uri::root(true) . '/media/jui/js/jquery-noconflict.js']);
            // unset($document->_scripts[Uri::root(true) . '/media/jui/js/jquery-migrate.js']);
            // unset($document->_scripts[Uri::root(true) . '/media/jui/js/jquery.searchtools.js']);
            // unset($document->_scripts[Uri::root(true) . '/media/system/js/html5fallback-uncompressed.js']);
            //unset($document->_scripts[Uri::root(true) . '/media/jui/js/html5-uncompressed.js']);

            // $document->addScript(Uri::root(true) . '/media/jui/js/jquery.js');
            // $document->addScript(Uri::root(true) . '/media/jui/js/bootstrap.js');
        }

        $document->addStyleSheet(Uri::root() . 'media/com_tripblog/css/tripblog.css');
        $document->addScript(Uri::root() . 'media/com_tripblog/js/tripblog.js');

        $document->addStyleSheet('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');

        // $document->addStyleSheet(Uri::root() . 'media/com_tripblog/css/field/vanillaSelectBox.css');
        // $document->addScript(Uri::root() . 'media/com_tripblog/js/field/vanillaSelectBox.js');
        // $document->addScript(Uri::root() . 'media/com_tripblog/js/field/continent.js');

        $document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css');
        $document->addScript('https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js');

        if($app instanceof SiteApplication)
        {
            $document->addStyleSheet(Uri::root() . 'media/com_tripblog/css/field/likes.css');
            $document->addScript(Uri::root() . 'media/com_tripblog/js/field/likes.js');
        }
    }
}