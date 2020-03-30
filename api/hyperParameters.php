<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 11-03-2020
 * Time: 12:22
 */

if (!isset($_REQUEST["experiment_id"])) {
    echo "404";
    die();
}

require_once ROOT . "/api/helpers/prettyPrintJson.php";

$_sql = new Sql();
$experiment_id = intval($_REQUEST["experiment_id"]);

$experiment = $_sql->SELECT_experiment($experiment_id);

echo prettyPrint($experiment->locals);