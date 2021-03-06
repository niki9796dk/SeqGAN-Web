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
$metricDefault = "?";
$decimals = 0;
$is_text = $experiment->is_text;

Layout::echoHead([], "[$experimentId] $experiment->name");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <hr>
            <h3>Experiment: [<?=$experiment->experiment_id?>] <?=$experiment->name?> <span class="good_star" style='font-size: 1.5em; color: <?=($experiment->good) ? "orange" : "gray";?>'><?=($experiment->good) ? "&#9733;" : "&#9734;"?></span></h3>
            <hr>
        </div>
    </div>

    <div class="row" style="overflow: auto">
        <div class="col-12 d-flex header-stuff" style="overflow: auto">
            <img style="height: fit-content" src="/image/getHistogramPlot.php?experiment_id=<?=$experimentId?>">
            <?=file_get_contents("http://seqgan.primen.dk/api/lossPlot.php?id=$experimentId")?>
<!--            <img src="/image/getLossPlot.php?experiment_id=--><?//=$experimentId?><!--">-->
            <pre class="bg-white ml-3" style="overflow: visible!important;height: fit-content"><?=file_get_contents("http://seqgan.primen.dk/api/hyperParameters.php?experiment_id={$experimentId}")?></pre>
            <?=file_get_contents("http://seqgan.primen.dk/api/NfaDisplayerJs.php?experiment_id=$experimentId")?>
            <div class="spacer pr-3 pr-xl-0"></div>
        </div>
    </div>

    <?php if (!$is_text): ?>
    <div class="row my-5">
        <div class="col-12 justify-content-center d-flex">
            <table class="border-dark table table-hover w-auto">
                <thead class="">
                    <tr class="text-center">
                        <th colspan="2" class="border-top-0"></th>
                        <th colspan="3" class="bg-white text-center border-left border-top">% Unique</th>
                        <th colspan="2" class="bg-white text-center border-left border-top">Mean edit distance</th>
                        <th colspan="3" class="bg-white text-center border-left border-top border-right">Entropy score</th>
                        <th colspan="1" class="border-0"><button class="btn btn-secondary" onclick="$('.prefix-image').toggle()"><small>Show/Hide</small></button></th>
                    </tr>
                    <tr class="border-left border-right text-center bg-white">
                        <th>Epoch</th>
                        <th class="border-left">Accuracy</th>
                        <th class="border-left">All</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th class="border-left">All</th>
                        <th>Wrong</th>
                        <th class="border-left">All</th>
                        <th>Correct</th>
                        <th>Wrong</th>
                        <th>Prefix Accuracy</th>
                    </tr>
                </thead>
                <tbody class="text-center bg-white">
                <?php foreach($_sql->SELECT_allImageMetricsForExperimentById($experimentId) as $metric):
                    $total = $metric->correct_sequences + $metric->wrong_sequences;
                    $unique_total = $metric->unique_correct_sequences + $metric->unique_wrong_sequences;
                    ?>
                    <tr class="border">
                        <td><?=$metric->epoch_nr?></td>
                        <td class="border-left <?=$metric->best_accuracy == 1 ? "font-weight-bold" : ""?>"><?=number_format(($metric->accuracy * 100), $decimals)?>%</td>
                        <td class="border-left <?=$metric->best_unique_sequences == 1 ? "font-weight-bold" : ""?>"><?=number_format(($unique_total / $total) * 100, $decimals)?>%</td>
                        <td class="<?=$metric->best_unique_correct_sequences == 1 ? "font-weight-bold" : ""?>"><?=number_format($metric->unique_correct_sequences * 100 / $total)?>% <small>(<?=number_format($metric->unique_correct_sequences * 100 / $unique_total)?>%)</small></td>
                        <td class="<?=$metric->best_unique_wrong_sequences == 1 ? "font-weight-bold" : ""?>"><?=number_format($metric->unique_wrong_sequences * 100 / $total)?>% <small>(<?=number_format($metric->unique_wrong_sequences * 100 / $unique_total)?>%)</small></td>
                        <td class="border-left <?=$metric->best_edit_distance_all == 1 ? "font-weight-bold" : ""?>"><?=number_format($metric->edit_distance_all, 2)?></td>
                        <td class="<?=$metric->best_edit_distance_wrong == 1 ? "font-weight-bold" : ""?>"><?=number_format($metric->edit_distance_wrong, 2)?></td>
                        <td class="border-left <?=$metric->best_sequence_entropy == 1 ? "font-weight-bold" : ""?>"><?=$metric->sequence_entropy != null ? number_format($metric->sequence_entropy*100, 2) : "Null"?></td>
                        <td class="<?=$metric->best_correct_entropy == 1 ? "font-weight-bold" : ""?>"><?=$metric->correct_entropy != null ? number_format($metric->correct_entropy*100, 2) : "Null"?></td>
                        <td class="<?=$metric->best_wrong_entropy == 1 ? "font-weight-bold" : ""?>"><?=$metric->wrong_entropy != null ? number_format($metric->wrong_entropy*100, 2) : "Null"?></td>
                        <td><img class="w-75 prefix-image" src="/image/getPrefixPlot.php?experiment_id=<?=$experimentId?>&epoch=<?=$metric->epoch_nr?>"></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif;?>

    <?php if ($is_text): ?>
        <div class="row my-5">
            <div class="col-12 justify-content-center d-flex">
                <table class="border-dark table table-hover w-auto">
                    <thead class="">
<!--                    <tr class="text-center">-->
<!--                        <th colspan="1" class="border-top-0"></th>-->
<!--                        <th colspan="1" class="bg-white text-center border-left border-top border-right">BLEU</th>-->
<!--                    </tr>-->
                    <tr class="border-left border-right text-center bg-white">
                        <th>Epoch</th>
                        <th class="border-left">Bleu-2</th>
                        <th class="border-left">Bleu-3</th>
                    </tr>
                    </thead>
                    <tbody class="text-center bg-white">
                    <?php foreach($_sql->SELECT_allTextMetricsForExperimentById($experimentId) as $metric):?>
                        <tr class="border">
                            <td><?=$metric->epoch_nr?></td>
                            <td class="border-left <?=$metric->best_bleu2 == 1 ? "font-weight-bold" : ""?>"><?=isset($metric->bleu2) ? number_format($metric->bleu2, 2) : "NULL"?></td>
                            <td class="border-left <?=$metric->best_bleu3 == 1 ? "font-weight-bold" : ""?>"><?=isset($metric->bleu3) ? number_format($metric->bleu3, 2) : "NULL"?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <table id="experiment_table_<?=$is_text ? "text" : "images"?>" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Epoch</th>
                        <th><?=$is_text ? "Text" : "Image"?></th>
                        <th>Time</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<?php
Layout::echoFooter();
?>