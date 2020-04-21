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
$table = "texts";

// Table's primary key
$primaryKey = '*';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'epoch_nr', 'dt' => 0 ),
    array(
        'db'        => 'experiment_id',
        'dt'        => 1,
        'formatter' => function( $d, $row ) {
            $experiment_id = $d;
            $epoch = $row["epoch_nr"];

            return "<pre>".file_get_contents("http://seqgan.primen.dk/api/getText.php?experiment_id=$experiment_id&epoch=$epoch")."</pre>";
        }
    ),
    array(
        'db'        => 'timestamp',
        'dt'        => 2,
        'formatter' => function( $d, $row ) {
            return date( 'Y-m-d H:i:s', strtotime($d));
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

$experiment_id = (isset($_REQUEST["experiment_id"]) and is_numeric($_REQUEST["experiment_id"])) ? $_REQUEST["experiment_id"] : NULL;

echo json_encode(
    SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, NULL, "experiment_id = $experiment_id")
);