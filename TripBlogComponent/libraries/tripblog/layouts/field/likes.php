<?php
/**
 * @package     TripBlog.Libraries
 * @subpackage  Layout
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
use Joomla\CMS\Form\FormHelper;

extract($displayData);

/**
 * Extracted
 *
 * @var $id
 * @var $element
 * @var $field
 * @var $name
 * @var $options
 * @var $value
 */

if (isset($element['max']))
{
	$max = (int) ((array) $element['max'])[0];
}
else
{
	$max = 5;
}

?>

<div class="likes_container">
    <?php for ($x = 1; $x <= $max; $x++): ?>
        <span>
            <input type="radio" name="likes" class="likes" value="<?php echo $x; ?>">
            <i class="fa fa-thumbs-up"></i>
        </span>
	<?php endfor; ?>
</div>
