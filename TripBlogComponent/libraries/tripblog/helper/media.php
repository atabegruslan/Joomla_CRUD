<?php
/**
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */

defined('_JEXEC') or die;

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
 * Media helper.
 *
 * @package     TripBlog.Libraries
 * @subpackage  Helper
 */
class TripblogHelperMedia
{
	/**
	 * Get images for deletion
	 *
	 * @param   string   $table				Database table's name
	 * @param   Array    $cids				Table IDs
	 *
	 * @return  Array    $images 	        Array of image paths
	 */
	public static function getImagesForDeletion($table, $cids)
	{
		$db    = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('image'))
			->from($db->qn('#__tripblog_trip'))
			->where($db->qn('id') . ' IN (' . implode(',', $cids) . ')');

		$images = $db->setQuery($query)->loadColumn();

		foreach ($images as &$image)
		{
			$image = str_replace(Uri::root(), JPATH_SITE . '/', $image);
		}

		return $images;
	}

	/**
	 * Delete images
	 *
	 * @param   Array    $images		Images to be deleted
	 *
	 * @return  void
	 */
	public static function deleteImages($images)
	{
		if (!is_array($images))
		{
			return;
		}

		foreach ($images as $image)
		{
			if (JFile::exists($image))
			{
				JFile::delete($image);
			}
		}
	}

	/**
	 * Save Image
	 *
	 * @param   Array    $files				POSTed files
	 * @param   string   $part				Which feature is the image in question for, 'trip' or 'user'
	 * @param   string   $modifiedImage 	Cropped image (if exists)
	 *
	 * @return  string   $imgUrl 	        URL to the image in its final saved path
	 */
	public static function saveImage($files, $part, $modifiedImage = '')
	{
		$fileName = $files['image']['name'];

		if (empty($fileName))
		{
			return '';
		}

		$fileName   = self::makeUniqueName($fileName);
		$path       = 'media/com_tripblog/images/' . $part . '/';
		$destFolder = JPATH_SITE . '/' . $path;
		$destPath   = $destFolder . $fileName;
		$tmpName    = !empty($modifiedImage) ? $modifiedImage : $files['image']['tmp_name'];

		if (!JFolder::exists($destFolder))
		{
			JFolder::create($destFolder, 0755);
		}

		$dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onTripBlogBeforeImageUpload', array($tmpName));

		if (empty($modifiedImage))
		{
			JFile::copy($tmpName, $destPath);
		}
		else
		{
			list($type, $image) = explode(';', $tmpName);
			list(, $image)      = explode(',', $image);
			$image              = base64_decode($image);

			file_put_contents($destPath, $image);
		}

		$imgUrl = Uri::root() . $path . $fileName;

		return $imgUrl;
	}

	/**
	 * Make the image file name unique
	 *
	 * @param   string   $name  	Previous image file name
	 *
	 * @return  string   $newName 	Modified image file name
	 */
	public static function makeUniqueName($name)
	{
		$nameArr  = explode('.', $name);
		$ext      = array_pop($nameArr);
		$newName  = implode('-', $nameArr);
		$newName  = str_replace(' ', '-', $newName);
		$datetime = date("yFd_H-i-s");
		$random   = rand(10000, 99999);
		$newName  = $newName . '-' . $datetime . '-' . $random . '.' . $ext;

		return $newName;
	}

	/**
	 * Get no-image filler image path
	 *
	 * @param   string   $imageUrl		    The image's URL.
	 * @param   string   $feature		    For example 'trip' or 'user'.
	 *
	 * @return  string   $fillerImagePath 	Filler image path
	 */
	public static function getImageWithFiller($imageUrl, $feature)
	{
		if (!is_null($imageUrl))
		{
			return $imageUrl;
		}

		switch ($feature)
		{
		    case 'trip':
		        $fillerImagePath = Uri::root() . 'media/com_tripblog/images/no_image.png';
		        break;
		    case 'user':
		        $fillerImagePath = Uri::root() . 'media/com_tripblog/images/no_avatar.png';
		        break;
		    default:
		        $fillerImagePath = '';
		}

		return $fillerImagePath;
	}
}
