<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 10-03-2020
 * Time: 11:24
 */

$_sql = new Sql();

$lastWednesday = new DateTime("last wednesday");
$thisWednesday = new DateTime("this wednesday");

$recentExperiments = [];
foreach ($_sql->SELECT_allExperimentsFromPeriod() as $run) {
    $start = new DateTime($run->timestamp);

    if ($lastWednesday <= $start && $start <= $thisWednesday) {
        $recentExperiments[] = $run;
    }
}

$recentExperimentIds = array_column($recentExperiments, "experiment_id");
$_REQUEST["ids"] = implode("_", $recentExperimentIds);

include "compare.php";