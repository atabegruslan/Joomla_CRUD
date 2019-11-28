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

?>

<div id="user_tab_btns">
    <a href="<?php echo Route::_('index.php?option=com_tripblog&view=useraccount'); ?>" class="button">
		<?php echo Text::_("COM_TRIPBLOG_LOGIN_LBL"); ?>
    </a>
    <a href="<?php echo Route::_('index.php?option=com_tripblog&view=useraccount&layout=changepassword'); ?>" class="button">
		<?php echo Text::_("COM_TRIPBLOG_CHANGE_PASSWORD_LBL"); ?>
    </a>
	<?php if (TripblogHelperPermission::isLoggedIn()): ?>
        <a href="<?php echo Route::_('index.php?option=com_tripblog&view=useraccount&layout=profile'); ?>" class="button">
			<?php echo Text::_("COM_TRIPBLOG_PROFILE_LBL"); ?>
        </a>
        <a href="<?php echo Route::_('index.php?option=com_tripblog&view=useraccount&layout=community'); ?>" class="button">
			<?php echo Text::_("COM_TRIPBLOG_COMMUNITY_LBL"); ?>
        </a>
	<?php endif; ?>
</div>
