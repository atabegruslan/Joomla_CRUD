<?php
/**
 * @package     TripBlog.Backend
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

$route = Route::_('index.php?option=com_tripblog&view=users');

?>

<form action="<?php echo $route; ?>" enctype="multipart/form-data" method="post" name="adminForm" id="adminForm" class="form-validate">

	<?php foreach($this->form->getFieldset() as $field): ?>

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

                    <img class="img user_img" src="<?php echo $field->value; ?>">

				<?php endif; ?>

            </div>

        </div>

	<?php endforeach; ?>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />

	<?php echo HTMLHelper::_('form.token'); ?>

</form>
