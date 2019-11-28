<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Layouts
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

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$document = Factory::getDocument();
$document->addScript(Uri::root() . 'media/system/js/core.js');

$action = $displayData['action'];

?>

<form method="post" enctype="multipart/form-data" name="<?php echo $action; ?>" id="<?php echo $action; ?>" class="form-validate">

	<?php foreach ($displayData['fieldData'] as $field) : ?>

        <div class="form-group">
            <div class="control-label">
				<?php echo $field->label; ?>
            </div>
            <div class="controls">
				<?php echo $field->input; ?>
            </div>
        </div>

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

            <img class="img user_img" src="<?php echo $field->value; ?>">

		<?php endif; ?>

	<?php endforeach; ?>

    <input type="hidden" name="task" class="task" value="useraccount.<?php echo $action; ?>" />

	<?php echo HTMLHelper::_('form.token'); ?>

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="submit" class="btn btn-primary validate">
				<?php echo ucfirst($action); ?>
            </button>
        </div>
    </div>

</form>
