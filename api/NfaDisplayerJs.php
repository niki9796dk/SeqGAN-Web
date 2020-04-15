<?php declare(strict_types=1);
/**
 * Created by: Niki Ewald Zakariassen
 * Date: 14-04-2020 - 13:28
 */

if (!isset($_REQUEST["experiment_id"])) {
    echo "404";
    die();
}

$experiment_id = $_REQUEST["experiment_id"];
$small_size = $_REQUEST["small_size"] ?: "true";
$_sql = new Sql();

$automaton = $_sql->SELECT_nfaDescriptionByExperimentId($experiment_id);

if (!isset($automaton)) {
    exit();
}

?>

<div class="svg-container ml-3 bg-white">
    <h3>Dataset NFA</h3>
    <div class="svg"></div>
    <a href="/api/NfaDisplayerJs.php?experiment_id=<?=$experiment_id?>&small_size=false" target="_blank">Original size</a>
    <textarea class="svg-fsm mt-3" style="width: 100%" rows="6" readonly><?=$automaton?></textarea>
    <a href="http://ivanzuzak.info/noam/webapps/fsm_simulator/" target="_blank">Simulator</a>
</div>

<script src="/libs/jQuery/jquery-3.4.1.min.js"></script>
<script src="http://izuzak.github.com/noam/lib/browser/noam.min.js"></script>
<script src="http://mdaines.github.io/viz.js/bower_components/viz.js/viz.js"></script>
<script>
    let string = `<?=$automaton?>`;
    let automaton = noam.fsm.parseFsmFromString(string);
    let dot_format = noam.fsm.printDotFormat(automaton);
    let svg = Viz(dot_format, 'svg');

    $(".svg-container .svg").html(svg);

    if (<?=$small_size?>) {
        $(".svg-container .svg svg").attr("width", "400pt");
        $(".svg-container .svg svg").attr("height", "280pt");
    }
</script>
