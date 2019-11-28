<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Layouts
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

$item    = $displayData['item'];
$i       = $displayData['index'];
$itemUrl = Route::_('index.php?option=com_tripblog&view=user&layout=edit&id=' . $item->id);

?>

<tr>
    <td width="5%">
        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
    </td>
    <td width="25%">
        <?php $item->image = TripblogHelperMedia::getImageWithFiller($item->image, 'user'); ?>
        <img class="user_img" src="<?php echo $item->image; ?>" />
    </td>
    <td width="25%">
        <a href="<?php echo $itemUrl; ?>">
            <?php echo $item->name; ?>
        </a>
    </td>
    <td width="20%">
        <?php echo $item->username; ?>
    </td>
    <td width="25%">
		<?php echo $item->email; ?>
    </td>
</tr>