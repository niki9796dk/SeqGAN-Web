<?php declare(strict_types = 1);

$_sql = new Sql();

Layout::echoHead();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-center">
            <hr>
            <h1>SeqGan Data</h1>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered dataTable table-hover">
                <thead>
                <tr>
                    <th>Experiment ID</th>
                    <th>Name</th>
                    <th>Model</th>
                  	<th>Hyperparameters</th>
                    <th>Time</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($_sql->SELECT_allExperiments() as $experiments): ?>
                    <tr class="clickable" onclick="window.location.href = '/views/pages/experiment.php?id=<?=$experiments->experiment_id?>';">
                        <td data-sort="<?=-$experiments->experiment_id?>"><?=$experiments->experiment_id?></td>
                        <td><?=$experiments->name?> <?=$experiments->good ? "<span style='font-size: 1.5em; color: orange'>&#9733;</span>" : ""?></td>
                        <td><?=$experiments->model?></td>
                        <td><pre><?=prettyPrint($experiments->locals)?></pre></td>
                        <td>
                            <?=$experiments->timestamp?>
                            <br>
                            <span style="font-weight: bold"><?=$experiments->running ? "<span style='color: green'>RUNNING</span>" : "DONE"?></span>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
Layout::echoFooter();

function prettyPrint( $json )
{
    $result = '';
    $level = -1;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $new_line_level = max(0, $new_line_level);
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

  	if ($result[0] == "{" and $result[strlen($result)-1] == "}") {
      $result[0] = " ";
      $result[strlen($result)-1] = " ";
      
      $result = trim($result);
    }
  
  	// Make keys bold purple
  	$result = preg_replace("/('.+?'):/", "<span style='color: purple; font-weight: bold'>$1</span>:", $result);
  
  	// Make strings green
  	$result = preg_replace("/: ('[^']+?')/", ": <span style='color: green'>$1</span>", $result);
  
  	// Make None red
  	$result = preg_replace("/: (None)/", ": <span style='color: red'>$1</span>", $result);
  
  	// Make numbers Blue
  	$result = preg_replace("/: (\d+(.\d+)?)/", ": <span style='color: blue'>$1</span>", $result);
  
  	// Make true/false orange
  	$result = preg_replace("/: (True|False)/", ": <span style='color: #e8640c'>$1</span>", $result);
  
    return $result;
}
?>