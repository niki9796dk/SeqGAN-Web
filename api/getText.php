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
    # Textual changes
    $text = preg_replace("/-LRB- ?/", "(", $text);
    $text = preg_replace("/ ?-RRB-/", ")", $text);
    $text = preg_replace("/ ,/", ",", $text);
    $text = preg_replace("/ \./", ".", $text);
    $text = preg_replace("/ ('\w+)/", "$1", $text);
    $text = preg_replace("/ n't/", "n't", $text);
    $text = preg_replace("/ !/", "!", $text);
    $text = preg_replace("/ \?/", "?", $text);

    // Escape special chars
    $text = htmlspecialchars($text);

    # Coloring
    $text = preg_replace("/ : (\d+\.\d+%)$/m", " <b>: $1</b>", $text); // Percentages in bold black

    $text = preg_replace("/ : (\d+\.\d+)$/m", " <b>:</b> <b style='color: green;'>$1</b>", $text); // Positive scores in green
    $text = preg_replace("/ : (-\d+\.\d+)$/m", " <b>:</b> <b style='color: darkred'>$1</b>", $text); // Negative scores in red


    echo $text;
} else {
    echo "No text found for epoch $epoch";
}
