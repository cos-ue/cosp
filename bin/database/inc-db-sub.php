<?php
/**
 * This File includes all needed files for database interaction in correct order for subpath-files
 *
 * @package default
 */

if (!defined('NICE_PROJECT')) {
    die('Permission denied.');
}

require_once '../bin/database/basic-db.php';
require_once '../bin/database/statistics-basic-dbfunctions.php';
require_once '../bin/database/user-db.php';
require_once '../bin/database/role-db.php';
require_once '../bin/database/session-db.php';
require_once '../bin/database/api-db.php';
require_once '../bin/database/point-reason-db.php';
require_once '../bin/database/rank-point-db.php';
require_once '../bin/database/ranks-db.php';
require_once '../bin/database/picture-db.php';
require_once '../bin/database/story-db.php';
require_once '../bin/database/validate-story-db.php';
require_once '../bin/database/validate-picture-db.php';
require_once '../bin/database/logging.php';
require_once '../bin/database/statistics-user.php';
require_once '../bin/database/statistics-pictures-db.php';
require_once '../bin/database/statistics-story-db.php';
require_once '../bin/database/module-rank-db.php';
require_once '../bin/database/source-type-db.php';
require_once '../bin/database/module-rights-db.php';
require_once '../bin/database/statistics-contact.php';
require_once '../bin/database/user_browserinfo-db.php';