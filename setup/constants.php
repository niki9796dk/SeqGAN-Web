<?php declare(strict_types = 1);

/**
 * This files contains all include constants.
 * The constants are all absolute paths, which helps making imports easier from a certain folder such as VIEWS.
 */

if (!isset($_SERVER, $_SERVER["DOCUMENT_ROOT"])) {
    $_SERVER["DOCUMENT_ROOT"] = __DIR__ . "/.."; // Set ROOT to the parent dir of this script
}

define("ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("VIEWS", ROOT . "/views");
define("DATABASE", ROOT . "/database");
define("LAYOUT", VIEWS . "/layout");
