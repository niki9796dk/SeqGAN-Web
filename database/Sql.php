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

    public function SELECT_allExperiments(): array {
        $query = $this->_db->prepare('
                                                SELECT experiments.*, running FROM experiments
                                                 
                                                LEFT JOIN experiment_export_rates
                                                ON experiments.experiment_id = experiment_export_rates.experiment_id
                                                
                                                ORDER BY experiment_id DESC
                                                ');

        $query->execute([]);
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

    public function SELECT_lossPlot(int $experiment_id): string {
        $query = $this->_db->prepare('SELECT loss_plot from loss_plots WHERE experiment_id=:experiment_id');

        $query->execute([
            ":experiment_id" => $experiment_id,
        ]);

        return $query->fetch()->loss_plot;
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