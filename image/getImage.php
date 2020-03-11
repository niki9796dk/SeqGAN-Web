<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 03-03-2020
 * Time: 14:18
 */

if (!isset($_REQUEST["experiment_id"], $_REQUEST["epoch"])) {
    echo "404";
}

$experiment_id = intval($_REQUEST["experiment_id"]);
$epoch = intval($_REQUEST["epoch"]);

$_sql = new Sql();

$image_blob = $_sql->SELECT_image($experiment_id, $epoch);

header('Content-type: image/png');
echo $image_blob;