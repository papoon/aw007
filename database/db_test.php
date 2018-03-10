<html>
   <head>
      <title>Connect to a MariaDB Database</title>
   </head>

   <body>
     <?php
        include_once '../database/dbConnector.php';

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

        $connector->disconnect();
        ?>
   </body>
</html>
