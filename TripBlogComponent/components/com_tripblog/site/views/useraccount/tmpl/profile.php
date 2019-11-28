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

$joomlaUser = Factory::getUser();
$data       = array('joomla_id' => $joomlaUser->id);
$user       = TripblogHelperUser::getTripblogUser($data);

?>

<table>
	<tr>
		<th><?php echo Text::_('COM_TRIPBLOG_NAME_LBL'); ?></th>
		<th><?php echo Text::_('COM_TRIPBLOG_USERNAME_LBL'); ?></th>
		<th><?php echo Text::_('COM_TRIPBLOG_EMAIL_LBL'); ?></th>
	</tr>
	<tr>
		<td><?php echo $user->name; ?></td>
		<td><?php echo $user->username; ?></td>
		<td><?php echo $user->email; ?></td>
	</tr>
</table>
