<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Views
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

HTMLHelper::_('behavior.tooltip');
//HTMLHelper::_('formbehavior.chosen', 'select');

$app      = Factory::getApplication();
$document = Factory::getDocument();

$listOrder = $this->escape($this->filter_order);
$listDirn  = $this->escape($this->filter_order_Dir);

$title = $document->getTitle();
$route = Route::_('index.php?option=com_tripblog&view=tripblogs');

$isLoggedIn   = TripblogHelperPermission::isLoggedIn();
$isSuper      = TripblogHelperPermission::isSuper();
$canEditOwn   = TripblogHelperPermission::canEditOwn();
$canEditState = TripblogHelperPermission::canEditState();

$user   = Factory::getUser();
$userId = $user->get('id');

?>

<h1><?php echo $title; ?></h1>

<?php echo $this->getToolbar(); ?>

<form action="<?php echo $route; ?>" method="post" name="adminForm" id="adminForm">

    <div class="row search_filter">
		<?php
			$searchLayoutData = array('view' => $this);
			echo LayoutHelper::render('joomla.searchtools.default', $searchLayoutData);
		?>
    </div>

    <div id="ajaxMessages"></div>

    <table class="table table-striped table-hover">
        <thead>
			<?php
				$headerData = array(
					'listOrder' => $listOrder,
					'listDirn'  => $listDirn
				);

				echo LayoutHelper::render('tripblog.header_row', $headerData);
			?>
        </thead>
        <tbody>
			<?php
				if ($this->items !== false)
				{
					foreach($this->items as $i => $item)
					{
						$data = array(
							'item'         => $item,
							'index'        => $i,
							'isLoggedIn'   => $isLoggedIn,
							'isSuper'      => $isSuper,
							'canEditOwn'   => $canEditOwn,
							'canEditState' => $canEditState,
							'user'         => $user,
							'userId'       => $userId
						);

						echo LayoutHelper::render('tripblog.row', $data);
					}
				}
			?>
        </tbody>
    </table>

	<?php //echo $this->pagination->total; ?>

    <div>
		<?php echo $this->pagination->getListFooter(); ?>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>

		<?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>

<div class="modal fade" id="like_detail" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                	<?php echo Text::_('COM_TRIPBLOG_LIKES_TALLY_DETAILS'); ?>
                </h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                	<?php echo Text::_('COM_TRIPBLOG_MODAL_CLOSE_LBL'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
