<?php declare(strict_types=1);
/**
 * Initially Created By:
 * User: Niki Ewald Zakariassen
 * Date: 01-10-2019
 * Time: 13:22
 */

$experimentId = intval($_REQUEST["id"]);
$_sql = new Sql();

$experiment = $_sql->SELECT_experiment($experimentId);

Layout::echoHead();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <hr>
            <h3>Experiment: [<?=$experiment->experiment_id?>] <?=$experiment->name?></h3>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12 justify-content-center d-flex">
            <img src="/image/getLossPlot.php?experiment_id=<?=$experimentId?>">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered dataTable">
                <thead>
                    <tr>
                        <th>Epoch</th>
                        <th>Image</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($_sql->SELECT_allEpochsForImagesByExperimentId($experimentId) as $imageEpoch):
                    $href = "/image/getImage.php?experiment_id={$experiment->experiment_id}&epoch={$imageEpoch->epoch_nr}";
                ?>
                    <tr>
                        <td data-sort="<?=-$imageEpoch->epoch_nr?>"><?=$imageEpoch->epoch_nr?></td>
                        <td class="w-75"><a href="<?=$href?>"><img alt="" src="<?=$href?>" style="height: 575px; max-width: 100%"></a></td>
                        <td><?=$imageEpoch->timestamp?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
Layout::echoFooter();
?>