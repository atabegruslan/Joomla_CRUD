<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Layouts
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

?>

<tr>
    <th width="5%"></th>
    <th width="25%">
		<?php echo Text::_('COM_TRIPBLOG_IMAGE_LBL'); ?>
    </th>
    <th width="25%">
        <?php echo Text::_('COM_TRIPBLOG_NAME_LBL'); ?>
    </th>
    <th width="20%">
		<?php echo Text::_('COM_TRIPBLOG_USERNAME_LBL'); ?>
    </th>
    <th width="25%">
		<?php echo Text::_('COM_TRIPBLOG_EMAIL_LBL'); ?>
    </th>
</tr>