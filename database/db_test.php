<html>
   <head>
      <title>Connect to a MariaDB Database</title>
   </head>

   <body>
     <?php
        include_once '../database/dbConnector.php';
        include_once '../database/dbUtils.php';

        // $host = "localhost";
        // $user = "aw007";
        // $password = "aw007";
        // $database = "aw007";

        $connector = DbConnector::defaultConnection();
        $link = $connector->connect();

        if (!$link) {
           echo "Error: Unable to connect to MySQL." . PHP_EOL;
           echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
           echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
           exit;
        }

        echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
        echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;

        echo '<hr>';

        echo '<h2>Test Select * </h2>';

        $data = $connector->selectAll('Author');

        printDataFromTable($data, 'Author');

        echo '<hr>';

        echo '<h2>Test Select * with Limit </h2>';

        $data = $connector->selectAll('Author',3);

        printDataFromTable($data, 'Author');

        echo '<hr>';

        echo '<h2>Test Select WHERE </h2>';

        $data = $connector->selectWhere('Author','id','>',8,'int');

        printDataFromTable($data, 'Author');

        echo '<hr>';

        echo '<h2>Test Select WHERE with Limit</h2>';

        $data = $connector->selectWhere('Author','id','>',5,'int',3);

        printDataFromTable($data, 'Author');

        echo '<hr>';

        echo '<h2>Test Insert</h2>';

        $value1 = array(
          array("val"=>"testname", "type"=>"char"),
          array("val"=>"testinstitution", "type"=>"char"),
          array("val"=>"testcontact", "type"=>"char"),
          array("val"=>"2033-02-08 15:48:00", "type"=>"char"),
          array("val"=>"2033-02-08 15:48:00", "type"=>"char")
        );

        $data = $connector->insertInto('Author', $value1);

        $connector->disconnect();
        ?>
   </body>
</html>
