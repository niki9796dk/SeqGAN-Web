<?php declare(strict_types=1);

require_once ROOT . "/database/DBSettings.php";

class Sql
{
    private $_db;

    //Initiates database connection
    public function __construct()
    {
        $user = DBSettings::$user;
        $pass = DBSettings::$pass;
        $host = DBSettings::$host;
        $db   = DBSettings::$db;
        $dsn  = DBSettings::$dsn;
        $dsn = "mysql:host=$host;dbname=$db;$dsn";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
        $this->_db = $pdo;
    }

    public function SELECT_latestPeriod(): int {
        $query = $this->_db->prepare('SELECT max(period) as latest_period FROM experiments');

        $query->execute([]);
        return intval($query->fetch()->latest_period);
    }

    public function SELECT_allMetricsForExperimentById($id) {
        $query = $this->_db->prepare('
                                                SELECT metrics.*, 
                                                       accuracy=best_accuracy as best_accuracy,
                                                       (unique_correct_sequences+unique_wrong_sequences)=best_unique_sequences as best_unique_sequences,
                                                       unique_correct_sequences=best_unique_correct_sequences as best_unique_correct_sequences,
                                                       unique_wrong_sequences=best_unique_wrong_sequences as best_unique_wrong_sequences,
                                                       edit_distance_all=best_edit_distance_all as best_edit_distance_all,
                                                       edit_distance_wrong=best_edit_distance_wrong as best_edit_distance_wrong,
                                                       edit_distance_unique_wrong=best_edit_distance_unique_wrong as best_edit_distance_unique_wrong,
                                                       sequence_entropy=best_sequence_entropy as best_sequence_entropy,
                                                       correct_entropy=best_correct_entropy as best_correct_entropy,
                                                       wrong_entropy=best_wrong_entropy as best_wrong_entropy
                                                FROM metrics, (
                                                    SELECT 
                                                           MAX(accuracy) as best_accuracy,
                                                           MAX(unique_correct_sequences + unique_wrong_sequences) as best_unique_sequences,
                                                           MAX(unique_correct_sequences) as best_unique_correct_sequences,
                                                           MIN(unique_wrong_sequences) as best_unique_wrong_sequences,
                                                           MIN(edit_distance_all) as best_edit_distance_all,
                                                           MIN(edit_distance_wrong) as best_edit_distance_wrong,
                                                           MIN(edit_distance_unique_wrong) as best_edit_distance_unique_wrong,
                                                           MIN(sequence_entropy) as best_sequence_entropy,
                                                           MIN(correct_entropy) as best_correct_entropy,
                                                           MIN(wrong_entropy) as best_wrong_entropy
                                                    from metrics 
                                                    WHERE experiment_id = :id_1
                                                    ) as best
                                                WHERE  metrics.experiment_id = :id_2
                                                ORDER BY epoch_nr DESC
                                                ');

        $query->execute([
            ":id_1" => $id,
            ":id_2" => $id,
            ]);

        return $query->fetchAll();
    }

    public function SELECT_allExperimentsFromPeriod($period = NULL): array {
        if (!isset($period)) {
            $period = $this->SELECT_latestPeriod();
        }

        $query = $this->_db->prepare('
                                                SELECT experiments.*, running FROM experiments
                                                 
                                                LEFT JOIN experiment_export_rates
                                                ON experiments.experiment_id = experiment_export_rates.experiment_id
                                                
                                                WHERE period=:period
                                                
                                                ORDER BY experiment_id DESC
                                                ');

        $query->execute([":period" => $period]);
        return $query->fetchAll();
    }

    public function SELECT_experiment(int $id): object {
        $query = $this->_db->prepare('
                                                SELECT experiments.*, running FROM experiments
                                                
                                                LEFT JOIN experiment_export_rates
                                                ON experiments.experiment_id = experiment_export_rates.experiment_id
                                                
                                                WHERE experiments.experiment_id = :id
                                                ');

        $query->execute([":id" => $id]);

        return $query->fetch();
    }

    public function SELECT_allRunningExperimentIds(): array {
        $query = $this->_db->prepare('
                                                SELECT experiments.experiment_id FROM experiments
                                                 
                                                LEFT JOIN experiment_export_rates
                                                ON experiments.experiment_id = experiment_export_rates.experiment_id
                                                
                                                WHERE running = 1
                                                
                                                ORDER BY experiment_id DESC
                                                ');

        $query->execute([]);
        return $query->fetchAll();
    }

    public function SELECT_allGoodExperimentIds(): array {
        $query = $this->_db->prepare('
                                                SELECT experiment_id, timestamp FROM experiments
                                                
                                                WHERE good = 1
                                                
                                                ORDER BY experiment_id DESC
                                                ');

        $query->execute([]);
        return $query->fetchAll();
    }

    public function SELECT_latestImageEpochsFromExperiments(array $ids) {
        $epochs = [];

        foreach ($ids as $id) {
            $epochs[] = $this->SELECT_latestImageEpochFromExperiment(intval($id));
        }

        return $epochs;
    }

    public function SELECT_latestImageEpochFromExperiment(int $id): int {
        $query = $this->_db->prepare('SELECT epoch_nr FROM images WHERE experiment_id = :id ORDER BY epoch_nr DESC LIMIT 1');

        $query->execute([":id" => $id]);

        return intval($query->fetch()->epoch_nr);
    }

    public function SELECT_image(int $experiment_id, int $epoch): string {
        $query = $this->_db->prepare('SELECT image from images WHERE experiment_id=:experiment_id AND epoch_nr=:epoch');

        $query->execute([
            ":experiment_id" => $experiment_id,
            ":epoch" => $epoch,
        ]);

        return $query->fetch()->image;
    }

    public function SELECT_lossPlot(int $experiment_id): ?string {
        $query = $this->_db->prepare('SELECT loss_plot from loss_plots WHERE experiment_id=:experiment_id');

        $query->execute([
            ":experiment_id" => $experiment_id,
        ]);

        return $query->fetch()->loss_plot;
    }

    public function SELECT_histogramPlot(int $experiment_id): ?string {
        $query = $this->_db->prepare('SELECT histogram from histograms WHERE experiment_id=:experiment_id ORDER BY epoch_nr LIMIT 1');

        $query->execute([
            ":experiment_id" => $experiment_id,
        ]);

        return $query->fetch()->histogram;
    }

    public function SELECT_allEpochsForImagesByExperimentId(int $experiment_id): array {
        $query = $this->_db->prepare('SELECT epoch_nr, timestamp from images WHERE experiment_id=:experiment_id ORDER BY epoch_nr DESC');

        $query->execute([
            ":experiment_id" => $experiment_id,
        ]);

        return $query->fetchAll();
    }

    /********************************************************************** */

    public function SELECT_SqlPurposeHereSingle(int $par): object {
        $query = $this->_db->prepare('/* SQL here */');
        $query->execute([":par" => $par]);
        return $query->fetch();
    }

    public function SELECT_SqlPurposeHereMultiple(int $par): array {
        $query = $this->_db->prepare('/* SQL here */');

        $query->execute([":par" => $par]);
        return $query->fetchAll();
    }

}