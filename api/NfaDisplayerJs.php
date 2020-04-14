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
$_sql = new Sql();

$automaton = $_sql->SELECT_nfaDescriptionByExperimentId($experiment_id);
?>

<div class="svg-container"></div>

<script src="/libs/jQuery/jquery-3.4.1.min.js"></script>
<script src="http://izuzak.github.com/noam/lib/browser/noam.min.js"></script>
<script src="http://mdaines.github.io/viz.js/bower_components/viz.js/viz.js"></script>
<script>
    let string = `<?=$automaton?>`;
    let automaton = noam.fsm.parseFsmFromString(string);
    let dot_format = noam.fsm.printDotFormat(automaton);
    let svg = Viz(dot_format, 'svg');

    $(".svg-container").html(svg);
</script>
