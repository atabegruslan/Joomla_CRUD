<?php
/**
 * @package     TripBlog
 * @subpackage  Install
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
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerAdapter;

/**
 * Custom installation of TripBlog
 *
 * @package     TripBlog
 * @subpackage  Install
 */
class Com_TripBlogInstallerScript
{
	/**
	 * method to uninstall the component
	 *
	 * @param   InstallerAdapter  $parent  class calling this method
	 *
	 * @return  void
	 *
	 * @throws  Exception
	 */
	public function uninstall($parent)
	{

	}

	/**
	 * Method to run before an install/update/uninstall method
	 *
	 * @param   string             $type    Type of change (install, update or discover_install)
	 * @param   InstallerAdapter  $parent  Class calling this method
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	public function preflight($type, $parent)
	{
		$this->installLibraries($parent);
		$this->installPlugins($parent);
		$this->installCli($parent);
		//$this->installLangOverrides();
	}

	/**
	 * Install the package libraries
	 *
	 * @param   InstallerAdapter  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installLibraries($parent)
	{
		$installer = new Installer;
		$manifest  = $parent->get('manifest');

		if (!$manifest)
		{
			return;
		}

		// $src is something like /var/www/html/Joomla/tmp/install_xxxx
		$src   = $parent->getParent()->getPath('source');
		$nodes = $manifest->libraries->library;

		if (empty($nodes))
		{
			return;
		}

		foreach ($nodes as $node)
		{
			$extName = $node->attributes()->name;
			// $extPath is something like /var/www/html/Joomla/tmp/install_xxxx/libraries/tripblog
			$extPath = $src . '/libraries/' . $extName;

			$installer->install($extPath);
		}
	}

	/**
	 * Install the package libraries
	 *
	 * @param   InstallerAdapter  $parent  class calling this method
	 *
	 * @return  void
	 */
	protected function installPlugins($parent)
	{
		$installer = new Installer;
		$manifest  = $parent->get('manifest');

		if (!$manifest)
		{
			return;
		}

		$src   = $parent->getParent()->getPath('source');
		$nodes = $manifest->plugins->plugin;

		if (empty($nodes))
		{
			return;
		}

		foreach ($nodes as $node)
		{
			$extName  = $node->attributes()->name;
			$extGroup = $node->attributes()->group;
			$extPath  = "$src/plugins/$extGroup/$extName";
			$disabled = !empty($node->attributes()->disabled) ? true : false;
			$result   = 0;

			if (is_dir($extPath))
			{
				$installer->setAdapter('plugin');
				$result = $installer->install($extPath);
			}

			if ($result && !$disabled)
			{
				$db    = Factory::getDBO();
				$query = $db->getQuery(true);
				$query->update($db->qn('#__extensions'));
				$query->set($db->qn('enabled') . ' = 1');
				$query->set($db->qn('state') . ' = 1');
				$query->where($db->qn('type') . ' = ' . $db->q('plugin'));
				$query->where($db->qn('element') . ' = ' . $db->q($extName));
				$query->where($db->qn('folder') . ' = ' . $db->q($extGroup));
				$db->setQuery($query)->execute();
			}
		}
	}

	/**
	 * Install the package Cli scripts
	 *
	 * @param   InstallerAdapter  $parent  class calling this method
	 *
	 * @return  void
	 */
	private function installCli($parent)
	{
		$installer = new Installer;
		$manifest  = $parent->get('manifest');

		if (!$manifest)
		{
			return;
		}

		$src = $parent->getParent()->getPath('source');

		$installer->setPath('source', $src);

		$element = $manifest->cli;

		if (!$element || !count($element->children()))
		{
			return;
		}

		$nodes = $element->children();

		foreach ($nodes as $node)
		{
			// Here we set the folder name we are going to copy the files to.
			$name = (string) $node->attributes()->name;

			// Here we set the folder we are going to copy the files to.
			$destination = JPath::clean(JPATH_ROOT . "/cli/$name");

			// Here we set the folder we are going to copy the files from.
			$folder = (string) $node->attributes()->folder;

			if ($folder && file_exists("$src/$folder"))
			{
				$source = "$src/$folder";
			}
			else
			{
				// Cli folder does not exist
				continue;
			}

			$copyFiles = $this->prepareFilesForCopy($element, $source, $destination);

			$installer->copyFiles($copyFiles, true);
		}
	}

	/**
	 * Install the package language overrides
	 *
	 * @return  void
	 */
	private function installLangOverrides()
	{
		$destFolder = JPATH_ADMINISTRATOR . '/language/overrides/';

		if (!JFolder::exists($destFolder))
		{
			JFolder::create($destFolder, 0755);
		}

		// @todo Don't hard code en-GB
		$overrideFile = $destFolder . 'en-GB.override.ini';

		$overrideStrings = array(
			'COM_CATEGORIES_CATEGORIES_BASE_TITLE="Continents"',
			'COM_CATEGORIES_CATEGORY_BASE_ADD_TITLE="Continents: New Continent"'
		);

		if (!JFile::exists($overrideFile))
		{
			JFile::write($overrideFile, implode("\n", $overrideStrings));
		}
		else
		{
			$existingFile = JFile::read($overrideFile);

			foreach ($overrideStrings as $overrideString)
			{
				if (strpos($existingFile, $overrideString) === false)
				{
					JFile::append($overrideFile, "\n" . $overrideString);
				}
			}
		}
	}

	/**
	 * Method to parse through a xml element of the installation manifest and take appropriate action.
	 *
	 * @param   SimpleXMLElement  $element      Element to iterate
	 * @param   string            $source       Source location of the files
	 * @param   string            $destination  Destination location of the files
	 *
	 * @return  array             $copyFiles
	 */
	private function prepareFilesForCopy($element, $source, $destination)
	{
		$copyFiles = array();

		// Process each file in the $files array (children of $tagName).
		foreach ($element->children() as $file)
		{
			$path         = array();
			$path['src']  = "$source/$file";
			$path['dest'] = "$destination/$file";

			// Is this path a file or folder?
			$path['type'] = ($file->getName() === 'folder') ? 'folder' : 'file';

			if (basename($path['dest']) !== $path['dest'])
			{
				$newDir = dirname($path['dest']);

				if (!JFolder::create($newDir))
				{
					JLog::add(Text::sprintf('JLIB_INSTALLER_ERROR_CREATE_DIRECTORY', $newDir), JLog::WARNING, 'jerror');

					return array();
				}
			}

			// Add the file to the copyfiles array
			$copyFiles[] = $path;
		}

		return $copyFiles;
	}
}