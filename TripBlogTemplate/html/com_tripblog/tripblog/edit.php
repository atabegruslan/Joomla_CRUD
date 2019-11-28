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
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$app        = Factory::getApplication();
$input      = $app->input;
$tripblogId = $input->get('id', 0);
$route      = Route::_('index.php?option=com_tripblog&view=tripblogs');

$isLoggedIn   = TripblogHelperPermission::isLoggedIn();
$isSuper      = TripblogHelperPermission::isSuper();

echo $this->getToolbar();

?>

<form action="<?php echo $route; ?>" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-validate">

	<?php foreach($this->form->getFieldset() as $field): ?>

		<?php if ($field->fieldname === 'likes' && $isLoggedIn): ?>

            <?php if (!$isSuper): ?>
                <div class="form-group likes_outer_container field-<?php echo $field->fieldname; ?>" data-tripblog-id="<?php echo $tripblogId; ?>">
                    <div class="control-label">
    					<?php echo $field->label; ?>
                    </div>
                    <div class="controls">
    					<?php echo $field->input; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php continue; ?>
            <?php endif; ?>

		<?php else: ?>

            <div class="form-group field-<?php echo $field->fieldname; ?>">

                <div class="control-label">
					<?php echo $field->label; ?>
                </div>

                <div class="controls">
					<?php echo $field->input; ?>

					<?php if ($field->fieldname === 'image'): ?>

                        <div id="crop_preview">
                            <div class="row">
                                <button id="preview">
                                    <?php echo Text::_('COM_TRIPBLOG_PREVIEW_IMAGE_LBL'); ?>
                                </button>
                            </div>
                            <div class="row">
                                <div class="span6" id="edit_image"></div>
                                <div class="span6" id="preview_image"></div>
                            </div>
                        </div>

                        <input type="hidden" id="edited_image" name="edited_image">

                        <img class="img trip_img" src="<?php echo $field->value; ?>">

					<?php endif; ?>

                </div>

            </div>

		<?php endif; ?>

	<?php endforeach; ?>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />

	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<div class="likes_box_outer">
    <?php
        $data = TripblogHelperTripblog::getLikeTally($this->item->id);
        echo LayoutHelper::render('tripblog.like.tally', array('data' => $data));
    ?>
</div>
