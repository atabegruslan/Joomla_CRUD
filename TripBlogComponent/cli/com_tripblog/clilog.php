<?php
/**
 * @package     TripBlog
 * @subpackage  CliLog
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/com_tripblog/joomla_framework.php';

jimport('tripblog.helper.mpdf');
jimport('tripblog.helper.media');
jimport('tripblog.helper.tripblog');

use Joomla\CMS\Factory;
use Joomla\CMS\Application\CliApplication;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * @package     TripBlog
 * @subpackage  CliLog
 */
class CliLogApplicationCli extends CliApplication
{
	/**
	 * A method to start the cli script
	 *
	 * @return void
	 */
	public function execute()
	{
		$this->app = Factory::getApplication('site');

		$args = getopt(null, ["type:"]);

		if (!isset($args['type']))
		{
			return;
		}

		$type = $args['type'];

		if ($type !== 'trips' && $type !== 'users')
		{
			return;
		}

		BaseDatabaseModel::addIncludePath(JPATH_SITE . '/components/com_tripblog/models', 'TripBlogModel');
		$model = BaseDatabaseModel::getInstance(ucfirst($type), 'TripBlogModel');
		$items = $model->getItems();

		$lang = Factory::getLanguage();
		// @todo Don't hard code en-GB
		$lang->load('com_tripblog', JPATH_SITE . '/components/com_tripblog', 'en-GB');

		$mPDF = TripblogHelperMpdf::getInstance();

		$mPDF->WriteHTML(file_get_contents(JPATH_SITE . '/media/com_tripblog/css/tripblog.css'), 1);

		$mPDF->WriteHTML('<table>');
		$mPDF->WriteHTML('<thead>');

		$headerData = array(
			'listOrder' => null,
			'listDirn'  => null
		);

		$layoutPath = JPATH_SITE . '/components/com_tripblog/layouts';

		$layoutFolder = array(
			'trips' => 'tripblog',
			'users' => 'user'
		);

		$header = LayoutHelper::render($layoutFolder[$type] . '.header_row', $headerData, $layoutPath);
		$mPDF->WriteHTML($header);

		$mPDF->WriteHTML('</thead>');
		$mPDF->WriteHTML('<tbody>');

		for ($i = 0; $i < count($items); $i++)
		{
			$data = array(
				'item'         => $items[$i],
				'index'        => $i,
				'isLoggedIn'   => false,
				'isSuper'      => false,
				'canEditOwn'   => false,
				'canEditState' => false,
				'user'         => null,
				'userId'       => null
			);

			$row = LayoutHelper::render($layoutFolder[$type] . '.row', $data, $layoutPath);
			$mPDF->WriteHTML($row);

			// @TODO: Shouldn't hardcode 6
			if ($i % 6 === 0 && $i !== 0)
			{
				$mPDF->AddPage();
			}
		}

		$mPDF->WriteHTML('</tbody>');
		$mPDF->WriteHTML('</table>');

		$mPDF->Output(__DIR__ . "/$type.pdf"/*, 'D'*/);
	}
}

// php clilog.php --type=trips
// php clilog.php --type=users
// @todo Make this script able to be ran like: cli/com_tripblog/clilog.php?type=trips
try
{
	$instance = CliApplication::getInstance('CliLogApplicationCli');
	$instance->execute();
}
catch (Throwable $e)
{
	print_r($e);
}
