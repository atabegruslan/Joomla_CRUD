<?php
/**
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */

defined('_JEXEC') or die;

$composerAutoload = __DIR__ . '/../vendor/autoload.php';

if (file_exists($composerAutoload))
{
	require_once $composerAutoload;
}

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Mpdf\Mpdf;

/**
 * Mpdf helper.
 *
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */
class TripblogHelperMpdf
{
	/**
	 * Returns a mPDF object, with inits done
	 *
	 * @return Mpdf   $mPDF    
	 */
	public static function getInstance()
	{
		$mPDF = new Mpdf();

		return $mPDF;
	}
}