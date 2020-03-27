<?php declare(strict_types=1);
/**
 * Created by: Niki Ewald Zakariassen
 * Date: 27-03-2020 - 08:57
 */

if (!isset($_REQUEST["experiment_id"])) {
    echo -1;
    die();
}

$experiment_id = $_REQUEST["experiment_id"];
$_sql = new Sql();

if ($_sql->UPDATE_flipGoodValue($experiment_id)) {
    echo 1;
} else {
    echo -1;
}
