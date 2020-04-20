<?php declare(strict_types=1);
/**
 * Created by: Niki Ewald Zakariassen
 * Date: 20-04-2020 - 13:36
 */

if (!isset($_REQUEST["experiment_id"], $_REQUEST["epoch"])) {
    echo "404";
}

$experiment_id = intval($_REQUEST["experiment_id"]);
$epoch = intval($_REQUEST["epoch"]);
$_sql = new Sql();
$text = $_sql->SELECT_text($experiment_id, $epoch);

if ($text) {
    echo $text;
} else {
    echo "No text found for epoch $epoch";
}
