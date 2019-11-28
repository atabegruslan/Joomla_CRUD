<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Layouts
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

$numberLike = 0;
$totalLike  = 0;

?>

<div class="likes_box">
    <table class="likes_table">
        <thead>
        <tr>
            <th><?php echo Text::_('COM_TRIPBLOG_LIKES_TALLY_NUMBER_OF_PEOPLE_VOTED_LBL'); ?></th>
            <th><?php echo Text::_('COM_TRIPBLOG_LIKES_TALLY_CUMULATIVE_VOTES_LBL'); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($displayData['data'] as $row): ?>
            <tr>
                <td><?php echo $row->likes; ?></td>
                <td><?php echo $row->users; ?></td>
            </tr>
			<?php
    			$numberLike += (float) $row->likes;
    			$totalLike  += (float) $row->users;
			?>
		<?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <p><?php echo Text::sprintf('COM_TRIPBLOG_LIKES_TALLY_NUMBER_OF_PEOPLE_VOTED', $totalLike); ?></p>
    <p><?php echo Text::sprintf('COM_TRIPBLOG_LIKES_TALLY_CUMULATIVE_VOTES', $numberLike); ?></p>
</div>
