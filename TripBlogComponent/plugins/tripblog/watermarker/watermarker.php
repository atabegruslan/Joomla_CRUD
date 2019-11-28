<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  TripBlog.Watermarker
 */

defined('JPATH_BASE') or die;

use Joomla\CMS\Plugin\CMSPlugin;

/**
 * Watermarker Plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  TripBlog.Watermarker
 */
class PlgTripblogWatermarker extends CMSPlugin
{
    /**
     * Watermarking the uploaded image
     *
     * @param   Array  $image  Image's tmp path
     *
     * @return  void
     */
    public function onTripBlogBeforeImageUpload($image)
    {
        $binaryData = file_get_contents($image);
        $stamped    = imagecreatefromstring($binaryData);
    	$stamp      = imagecreatefrompng(JPATH_SITE . '/media/plg_tripblog_watermarker/image/fav.png');
    	imagecopymerge($stamped, $stamp, 0, 0, 0, 0, 32, 32, 50);
    	
    	ob_start();
		imagepng($stamped);
		$stamped = ob_get_contents();
		ob_end_flush();

    	file_put_contents($image, $stamped);
    }
}