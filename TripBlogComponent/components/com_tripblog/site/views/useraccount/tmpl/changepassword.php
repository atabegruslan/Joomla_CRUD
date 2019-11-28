<?php
/**
 * @package     TripBlog.Frontend
 * @subpackage  Views
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
use Joomla\CMS\User\User;

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');

$document = Factory::getDocument();
$document->addScript(Uri::root() . 'media/system/js/core.js');

$route = Route::_('index.php?option=com_tripblog&task=useraccount.emailPasswordChange');

echo LayoutHelper::render('user.tab-btns', array());

?>

<form method="post" action="<?php echo $route; ?>" name="changePasswordForm" id="changePasswordForm" class="form-validate">

	<?php foreach ($this->form->getFieldset('change_password') as $field): ?>
        <div class="form-group">
            <div class="control-label">
				<?php echo $field->label; ?>
            </div>
            <div class="controls">
				<?php echo $field->input; ?>
            </div>
        </div>
	<?php endforeach; ?>

	<?php echo HTMLHelper::_('form.token'); ?>

    <div class="btn-toolbar">
        <div class="btn-group">
            <button type="submit" class="btn btn-primary validate">
				<?php echo Text::_('COM_TRIPBLOG_USER_CHANGE_PASSWORD_SEND_BTN_LBL'); ?>
            </button>
        </div>
    </div>

</form>
