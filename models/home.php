<?php
    require_once 'model.php';
    class Home extends Model{

        const Builder_Python_path = '../scripts/annotation/invertedIndexBuilder.py';

        public function __construct(){
            parent::__construct();
        }

        public function recalculateInvertedIndexes() {

          echo getcwd() . "\n";

          $command = escapeshellcmd('python3 ' . self::Builder_Python_path);

          echo $command.PHP_EOL;

          $output = shell_exec($command);

          echo $output.PHP_EOL;

        }
    }

?>
