<?php
/**
 * @package     TripBlog.Backend
 * @subpackage  Tables
 */

defined('_JEXEC') or die;

jimport('joomla.database.table');

use Joomla\CMS\Table\Table;

/**
 * Trip table.
 *
 * @package     TripBlog.Backend
 * @subpackage  Tables
 */
class TripBlogTableTrip extends Table
{

    /**
     * TripBlog post ID.
     *
     * @var  int
     */
    var $id = null;

    /**
     * Place name.
     *
     * @var  string
     */
    var $place = null;

    /**
     * Place name's alias.
     *
     * @var  string
     */
    var $alias = null;

    /**
     * Country ID.
     *
     * @var  int
     */
    var $country = null;

    /**
     * Review.
     *
     * @var  string
     */
    var $review = null;

    /**
     * URL to image.
     *
     * @var  string
     */
    var $image = null;

    /**
     * Is TripBlog post published or not.
     *
     * @var  boolean
     */
	var $published = 1;

    /**
     * Creation date.
     *
     * @var  string
     */
	var $created = null;

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  $db  Database connector object
     */
    function __construct(&$db)
    {
        parent::__construct('#__tripblog_trip', 'id', $db);
    }
}

?>