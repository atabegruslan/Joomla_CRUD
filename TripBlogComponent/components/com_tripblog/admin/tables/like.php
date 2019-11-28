<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Tables
 */

defined('_JEXEC') or die;

jimport('joomla.database.table');

use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use Joomla\CMS\Table\Table;

/**
 * Like table.
 *
 * @package     TripBlog.Backend
 * @subpackage  Tables
 */
class TripBlogTableLike extends Table
{
    /**
     * Like ID.
     *
     * @var  int
     */
    var $id = null;

    /**
     * TripBlog post ID.
     *
     * @var  int
     */
    var $tripblog_id = null;

    /**
     * TripBlog creator ID.
     *
     * @var  int
     */
    var $tripblog_user_id = null;

    /**
     * Number of likes.
     *
     * @var  int
     */
    var $likes = null;

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  $db  Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__tripblog_likes', 'id', $db);
    }

    /**
     * Method to provide a shortcut to binding, checking and storing a FOFTable
     * instance to the database table.  The method will check a row in once the
     * data has been stored and if an ordering filter is present will attempt to
     * reorder the table rows based on the filter.  The ordering filter is an instance
     * property name.  The rows that will be reordered are those whose value matches
     * the FOFTable instance for the property specified.
     *
     * @param   mixed   $src             An associative array or object to bind to the FOFTable instance.
     * @param   string  $orderingFilter  Filter for the order updating
     * @param   mixed   $ignore          An optional array or space separated list of properties
     *                                   to ignore while binding.
     *
     * @return  boolean  True on success.
     */
    public function save($src, $orderingFilter = '', $ignore = '')
    {
        return parent::save($src, $orderingFilter, $ignore);
    }
}

?>