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

$_sql = new Sql();

$histogram_plot = $_sql->SELECT_histogramPlot($experiment_id);

header('Content-type: image/png');

if ($histogram_plot == null) {
    echo file_get_contents("./histogramDefault.png");
} else {
    echo $histogram_plot;
}
