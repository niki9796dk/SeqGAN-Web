<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 01-10-2019
 * Time: 13:22
 */

$experimentIds = explode("_", $_REQUEST["ids"]);
$cols = isset($_REQUEST["cols"]) ? $_REQUEST["cols"] : null;
$_sql = new Sql();

$latestEpochs = $_sql->SELECT_latestImageEpochsFromExperiments($experimentIds);

$maxPrRow = $cols ? min($cols, 4) : 4;
$experiments = sizeof($experimentIds);
$colSize = $experiments >= $maxPrRow ? 12 / $maxPrRow : 12 / $experiments;
$rows = ceil($experiments / $maxPrRow);

Layout::echoHead();
?>

<div class="container-fluid">
    <?php for ($row = 0; $row < $rows; $row++): ?>
    <hr>
    <div class="row">
        <div class="col-12 text-center d-flex">
            <?php for($i = $row * $maxPrRow; $i < min($experiments, ($row * $maxPrRow) + $maxPrRow); $i++):
                $experimentId = intval($experimentIds[$i]);
                $epoch = intval($latestEpochs[$i]);
                $experiment = $_sql->SELECT_experiment($experimentId);

                $href = "/image/getImage.php?experiment_id={$experimentId}&epoch={$epoch}";
            ?>
            <div class="col-<?=$colSize?> mt-3">
                <a href="/views/pages/experiment.php?id=<?=$experiment->experiment_id?>" class="h3 mt-3 align-text-bottom d-block" style="height: 135px; color: black">[<?=$experiment->experiment_id?>] <?=$experiment->name?></a>
                <a href="<?=$href?>"><img class="border" alt="" src="<?=$href?>" style="height: 575px; max-width: 100%"></a>
                <div class="align-content-center">
                    <span>Epoch: <?=$epoch?></span>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
    <?php endfor; ?>
</div>

<?php
Layout::echoFooter();
?>