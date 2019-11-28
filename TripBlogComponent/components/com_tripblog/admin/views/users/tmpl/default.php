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

HTMLHelper::_('behavior.tooltip');

?>

<form action="<?php echo Route::_('index.php?option=com_tripblog'); ?>" method="post" name="adminForm" id="adminForm">

    <table class="table">
        <thead>
            <?php echo LayoutHelper::render('user.header_row', array()); ?>
        </thead>
        <tbody>
            <?php
                if ($this->items !== false)
                {
                    foreach($this->items as $i => $item)
                    {
                        $data = array(
                            'item'  => $item,
                            'index' => $i
                        );

                        echo LayoutHelper::render('user.row', $data);
                    }
                }
            ?>
        </tbody>
    </table>

    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo HTMLHelper::_('form.token'); ?>
    </div>
</form>
