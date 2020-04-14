<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 03-03-2020
 * Time: 14:18
 */

if (!isset($_REQUEST["experiment_id"])) {
    echo "404";
    die();
}

$experiment_id = intval($_REQUEST["experiment_id"]);
$epoch = intval($_REQUEST["epoch"]);

$_sql = new Sql();

$loss_plot = $_sql->SELECT_prefixPlot($experiment_id, $epoch);

header('Content-type: image/png');

if ($loss_plot == null) {
    echo file_get_contents("./prefixDefault.png");
} else {
    echo $loss_plot;
}
