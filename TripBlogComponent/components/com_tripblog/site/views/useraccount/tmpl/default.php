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

echo LayoutHelper::render('user.tab-btns', array());

?>

<ul class="nav nav-tabs">

    <li class="active">
        <a data-toggle="tab" href="#login">
			<?php echo Text::_("COM_TRIPBLOG_LOGIN_LBL"); ?>
        </a>
    </li>

	<?php if (!TripblogHelperPermission::isLoggedIn()): ?>
        <li>
            <a data-toggle="tab" href="#register">
				<?php echo Text::_("COM_TRIPBLOG_REGISTER_LBL"); ?>
            </a>
        </li>
	<?php endif; ?>

</ul>

<div class="tab-content">

    <div id="login" class="tab-pane fade in active">

		<?php if (!TripblogHelperPermission::isLoggedIn()): ?>

            <h3><?php echo Text::_("COM_TRIPBLOG_LOGIN_LBL"); ?></h3>

			<?php
                $loginLayoutData = array(
                    'fieldData' => $this->form->getFieldset('login'),
                    'action'    => 'login'
                );

                echo LayoutHelper::render('user.useraccount', $loginLayoutData);
            ?>

		<?php else: ?>

            <?php
                $actionRoute = Route::_('index.php?option=com_users');
                $returnRoute = Route::_('index.php?option=com_tripblog&view=useraccount');
            ?>

            <h3><?php echo Text::_("COM_TRIPBLOG_LOGOUT_LBL"); ?></h3>

            <form action="<?php echo $actionRoute; ?>" method="post" class="form-inline">

                <a href="javascript:void(0);" onclick="jQuery(this).closest('form').submit();">
					<?php echo Text::_('COM_TRIPBLOG_LOGOUT_LBL'); ?>
                </a>

                <input type="hidden" name="option" value="com_users"/>
                <input type="hidden" name="return" value="<?php echo $returnRoute; ?>">
                <input type="hidden" name="task" value="user.logout"/>

				<?php echo HTMLHelper::_('form.token'); ?>

            </form>

		<?php endif; ?>

    </div>

	<?php if (!TripblogHelperPermission::isLoggedIn()): ?>

        <div id="register" class="tab-pane fade">

            <h3><?php echo Text::_("COM_TRIPBLOG_REGISTER_LBL"); ?></h3>

			<?php
                $registerLayoutData = array(
                    'fieldData' => $this->form->getFieldset('register'),
                    'action'    => 'register'
                );

                echo LayoutHelper::render('user.useraccount', $registerLayoutData);
            ?>

        </div>

	<?php endif; ?>

</div>
