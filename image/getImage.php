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
$resize = isset($_REQUEST["small"]) && strtolower($_REQUEST["small"]) == "true";

$_sql = new Sql();

$image_blob = $_sql->SELECT_image($experiment_id, $epoch);

if ($resize) {
    $image = imagecreatefromstring($image_blob);
    $original_image_size = getimagesizefromstring($image_blob);
    $image = imagescale($image, intval(round($original_image_size[0]/4)), intval(round($original_image_size[1]/4)));

    header('Content-type: image/png');
    imagejpeg($image);
} else {
    header('Content-type: image/png');
    echo $image_blob;
}


