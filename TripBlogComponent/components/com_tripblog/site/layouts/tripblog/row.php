<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Layouts
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\MVC\Model\AdminModel;

$item         = $displayData['item'];
$i            = $displayData['index'];
$isLoggedIn   = $displayData['isLoggedIn'];
$isSuper      = $displayData['isSuper'];
$canEditOwn   = $displayData['canEditOwn'];
$canEditState = $displayData['canEditState'];
$user         = $displayData['user'];
$userId       = (int) $displayData['userId'];
$itemUrl      = Route::_('index.php?option=com_tripblog&view=tripblog&layout=edit&id=' . $item->id);

?>

<tr data-tripblog-id="<?php echo $item->id; ?>">
	<td width="2%">
		<?php //echo $this->pagination->getRowOffset($i); ?>
		<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
	</td>
	<td width="2%">
		<?php
			if ($canEditState)
			{
				echo HTMLHelper::_('jgrid.published', $item->published, $i, 'trips.', true, 'cb', null, null, 'adminForm');
			}

			$modifierId = (int) $item->checked_out;
			$canCheckin = is_null($user) ? false : $user->authorise('core.manage', 'com_checkin') || $modifierId === $userId;

			if ($modifierId > 0)
			{
				echo HTMLHelper::_('jgrid.checkedout', $i, $item->checked_out, $item->checked_out_time, 'trips.', $canCheckin);
			}
		?>
	</td>
	<td width="20%">
		<?php $item->image = TripblogHelperMedia::getImageWithFiller($item->image, 'trip'); ?>
		<img class="trip_img" src="<?php echo $item->image; ?>" />
	</td>
	<td width="20%">
		<?php if ($isSuper || ($canEditOwn && ($modifierId === 0 || $modifierId === $userId))): ?><a href="<?php echo $itemUrl; ?>"><?php endif; ?>
			<b><?php echo $item->place; ?></b>
		<?php if ($isSuper || ($canEditOwn && ($modifierId === 0 || $modifierId === $userId))): ?></a><?php endif; ?>
	</td>
	<td width="20%">
		<b><?php echo Text::_($item->country); ?></b>
		<br>
		<?php echo $item->continents; ?>
	</td>
	<td width="15%">
		<?php echo TripblogHelperTripblog::generateExcerpt($item->review); ?>
	</td>
	<td width="10%">
		<?php if ($isLoggedIn && !$isSuper): ?>
			<div class="likes_outer_container" data-tripblog-id="<?php echo $item->id; ?>">
				<?php
					/** @var TripBlogModelTrip $tripModel */
					$tripModel = AdminModel::getInstance('Trip', 'TripBlogModel');
					$formModel = $tripModel->getForm();

					echo $formModel->getField('likes')->input;
				?>
			</div>
		<?php endif; ?>

		<p data-toggle="modal" data-target="#like_detail" class="button_like like_button" data-blog_id="<?php echo $item->id; ?>">
			<?php
				if (intval($item->number_like) === 0)
				{
					$avgLikes = 0;
				}
				else
				{
					$avgLikes = floatval($item->total_like) / floatval($item->number_like);
				}
			?>
			<span id="avg_like_text">
                <?php echo Text::sprintf('COM_TRIPBLOG_AVERAGE_LIKES', $avgLikes); ?>
            </span>
		</p>
	</td>
	<td width="10%">
		<?php echo $item->created; ?>
	</td>
</tr>
