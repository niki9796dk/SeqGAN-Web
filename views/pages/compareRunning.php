<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 10-03-2020
 * Time: 11:24
 */

$_sql = new Sql();

$running = $_sql->SELECT_allRunningExperimentIds();
$running = array_column($running, "experiment_id");

$_REQUEST["ids"] = implode("_", $running);

include "compare.php";