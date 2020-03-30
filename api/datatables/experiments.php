<?php declare(strict_types=1);
/**
 * Created by: Niki Ewald Zakariassen
 * Date: 30-03-2020 - 13:06
 */

require_once ROOT . "/libs/datatables/ssp.class.php";
require_once ROOT . "/database/DBSettings.php";
require_once ROOT . "/database/Sql.php";
require_once ROOT . "/api/helpers/prettyPrintJson.php";


// DB table to use
$table = "
    (
        SELECT experiments.*, running FROM experiments
                                                     
        LEFT JOIN experiment_export_rates
        ON experiments.experiment_id = experiment_export_rates.experiment_id
    ) as temp
";

// Table's primary key
$primaryKey = 'experiment_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'experiment_id', 'dt' => 0 ),
    array( 'db' => 'name',          'dt' => 1 ),
    array( 'db' => 'model',         'dt' => 2 ),
    array(
        'db'        => 'locals',
        'dt'        => 3,
        'formatter' => function( $d, $row ) {
            $pretty = prettyPrint($d);
            return "<pre>$pretty</pre>";
        }
    ),
    array(
        'db'        => 'timestamp, running',
        'dt'        => 4,
        'formatter' => function( $d, $row ) {
            $timestamp = date( 'Y-m-d H:i:s', strtotime($d[0]));
            $running = "<span style='font-weight: bold'>".($d[1] ? "<span style='color: green'>RUNNING</span>" : "DONE")."</span>";

            return "$timestamp<br>$running";
        }
    )
);

// SQL server connection information
$sql_details = array(
    'user' => DBSettings::$user,
    'pass' => DBSettings::$pass,
    'db'   => DBSettings::$db,
    'host' => DBSettings::$host
);

$_sql = new Sql();
$period = (isset($_REQUEST["period"]) and is_numeric($_REQUEST["period"])) ? $_REQUEST["period"] : $_sql->SELECT_latestPeriod();

echo json_encode(
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, NULL, "period = $period")
);