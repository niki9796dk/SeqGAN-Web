<?php declare(strict_types = 1);

/**
 * This file is always executed before ANY other .php code.
 * This is done though the INI setting auto_prepend in the /opt/lampp/etc/php.ini file
 *
 * This also means that this file will also be executed when running another other .php file outside this project,
 * such PhpMyAdmin.
 * This is usually not a problem.
 */

// Define working directory
$workingDir = __DIR__;

// Require expected files
require_once $workingDir . "/constants.php";
require_once DATABASE . "/Sql.php";
require_once LAYOUT . "/Layout.php";            // Always include the Layout class, since this will be used often
