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
$metrics =  $_sql->SELECT_allMetricsForExperimentById($experimentId);
$metricDefault = "?";

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
            <img src="/image/getHistogramPlot.php?experiment_id=<?=$experimentId?>">
            <img src="/image/getLossPlot.php?experiment_id=<?=$experimentId?>">
            <pre class="bg-white ml-5"><?=file_get_contents("http://seqgan.primen.dk/api/hyperParameters.php?experiment_id={$experimentId}")?></pre>
        </div>
    </div>

    <div class="row my-5">
        <div class="col-12">
            <table class="w-100 border-dark table table-hover">
                <thead>
                    <tr>
                        <th>Epoch</th>
                        <th>Accuracy</th>
                        <th># Correct</th>
                        <th># Wrong</th>
                        <th># Unique</th>
                        <th># Unique Correct</th>
                        <th># Unique Wrong</th>
                        <th>Mean edit distance all</th>
                        <th>Mean edit distance wrong</th>
                        <th>Mean edit distance unique wrong</th>
                        <th>Entropy correct</th>
                        <th>Entropy wrong</th>
                        <th>Entropy all</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($metrics as $metric): ?>
                    <tr>
                        <td><?=$metric->epoch_nr ?? $metricDefault?></td>
                        <td><?=$metric->accuracy ?? $metricDefault?></td>
                        <td><?=$metric->correct_sequences ?? $metricDefault?></td>
                        <td><?=$metric->wrong_sequences ?? $metricDefault?></td>
                        <td><?=!is_null($metric->unique_correct_sequences) ? ($metric->unique_correct_sequences + $metric->unique_wrong_sequences) : $metricDefault?></td>
                        <td><?=$metric->unique_correct_sequences ?? $metricDefault?></td>
                        <td><?=$metric->unique_wrong_sequences ?? $metricDefault?></td>
                        <td><?=$metric->edit_distance_all ?? $metricDefault?></td>
                        <td><?=$metric->edit_distance_wrong ?? $metricDefault?></td>
                        <td><?=$metric->edit_distance_unique_wrong ?? $metricDefault?></td>
                        <td><?=$metric->correct_entropy ?? $metricDefault?></td>
                        <td><?=$metric->wrong_entropy ?? $metricDefault?></td>
                        <td><?=$metric->sequence_entropy ?? $metricDefault?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
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