<?php
/**
 * @package     TripBlog.Cli
 * @subpackage  Joomla Required
 */

const _JEXEC = 1;

// Load system defines
if (file_exists(dirname(__DIR__) . '/../defines.php'))
{
	require_once dirname(__DIR__) . '/../defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', dirname(__DIR__) . '/..');
	require_once JPATH_BASE . '/includes/defines.php';
}

// Get the framework.
require_once JPATH_LIBRARIES . '/import.legacy.php';

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Import the configuration.
require_once JPATH_CONFIGURATION . '/configuration.php';

define('JDEBUG', 0);
$_SERVER['REQUEST_METHOD'] = 'GET';
