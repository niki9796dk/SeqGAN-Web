<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 03-03-2020
 * Time: 14:18
 */

if (!isset($_REQUEST["experiment_id"])) {
    echo "404";
}

$experiment_id = intval($_REQUEST["experiment_id"]);

$_sql = new Sql();

$loss_plot = $_sql->SELECT_lossPlot($experiment_id);

header('Content-type: image/png');
echo $loss_plot;