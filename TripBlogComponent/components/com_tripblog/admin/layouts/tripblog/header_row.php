<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Layouts
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$listOrder = $displayData['listOrder'];
$listDirn  = $displayData['listDirn'];

?>

<tr>
	<th width="2%"></th>
	<th width="2%"></th>
	<th width="20%">
		<?php echo Text::_('COM_TRIPBLOG_IMAGE_LBL'); ?>
	</th>
	<th width="20%">
		<?php
		if (!is_null($listDirn) && !is_null($listDirn))
		{
			echo HTMLHelper::_('grid.sort', 'COM_TRIPBLOG_PLACE_LBL', 't.place', $listDirn, $listOrder);
		}
		?>
	</th>
	<th width="20%">
		<?php echo Text::_('COM_TRIPBLOG_COUNTRY_LBL'); ?>
	</th>
	<th width="15%">
		<?php echo Text::_('COM_TRIPBLOG_REVIEW_LBL'); ?>
	</th>
	<th width="10%">
		<?php echo Text::_('COM_TRIPBLOG_AGREES_LBL'); ?>
	</th>
	<th width="10%">
		<?php echo Text::_('COM_TRIPBLOG_CREATED_LBL'); ?>
	</th>
</tr>
