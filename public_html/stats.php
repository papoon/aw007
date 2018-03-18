<html>
   <head>
      <title>Aplicações na Web 2017/2018 - Grupo 7</title>
      <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
      <style>
      body {
        font-family: "Open Sans", sans-serif;
      }
      table, td, th {
        border: 1px solid black;
        border-collapse: collapse;
      }
      td, th {
        padding: 5px;
        text-align: center;
      }
      th {
        background-color: #e6e6ff;
      }
      </style>
   </head>

   <body>
     <h1>Aplicações na Web 2017/2018 - Grupo 7</h1>
     <h2>Estatísticas dos dados recolhidos</h2>
     <?php
        include_once '../database/dbConnector.php';
        include_once '../database/dbUtils.php';

        $connector = DbConnector::defaultConnection();
        $link = $connector->connect();

        if (!$link) {
           echo "Error: Unable to connect to MySQL." . PHP_EOL;
           echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
           echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
           exit;
        }

        //table with totals
        $numberDiseases = getCountResult($connector->selectCountAll(TABLE_DISEASE));
        $numberArticles = getCountResult($connector->selectCountAll(TABLE_ARTICLE));
        $numberPhotos = getCountResult($connector->selectCountAll(TABLE_PHOTOS));
        $numberTweets = getCountResult($connector->selectCountAll(TABLE_TWEETS));

        echo "<h3>Totais das tabelas</h3>" . PHP_EOL;

        echo "<table style='width:800px'>" . PHP_EOL;
        echo "<tr>". PHP_EOL;
        echo "<th>".Diseases."</th>". PHP_EOL;
        echo "<th>".Articles."</th>". PHP_EOL;
        echo "<th>".Photos."</th>". PHP_EOL;
        echo "<th>".Tweets."</th>". PHP_EOL;
        echo "</tr>". PHP_EOL;
        echo "<tr>". PHP_EOL;
        echo "<td>".$numberDiseases."</td>". PHP_EOL;
        echo "<td>".$numberArticles."</td>". PHP_EOL;
        echo "<td>".$numberPhotos."</td>". PHP_EOL;
        echo "<td>".$numberTweets."</td>". PHP_EOL;
        echo "</tr>". PHP_EOL;
        echo "</table>" . PHP_EOL;

        echo "<br>".PHP_EOL;

        //get all diseases from the database
        $diseases = $connector->selectAll(TABLE_DISEASE);
        $diseases = convertDatasetToArray($diseases);

        echo "<h3>Totais por doença</h3>" . PHP_EOL;

        foreach($diseases as $disease){

          $diseaseArticles = getCountResult($connector->selectCountWhere(TABLE_ARTICLE, 'did','=',$disease['id'],'int'));
          $diseasePhotos = getCountResult($connector->selectCountWhere(TABLE_PHOTOS, 'did','=',$disease['id'],'int'));
          $diseaseTweets = getCountResult($connector->selectCountWhere(TABLE_TWEETS, 'did','=',$disease['id'],'int'));

          echo "<h4>".$disease['name']."</h4>" . PHP_EOL;

          echo "<table style='width:600px'>" . PHP_EOL;
          echo "<tr>". PHP_EOL;
          echo "<th>".Articles."</th>". PHP_EOL;
          echo "<th>".Photos."</th>". PHP_EOL;
          echo "<th>".Tweets."</th>". PHP_EOL;
          echo "</tr>". PHP_EOL;
          echo "<tr>". PHP_EOL;
          echo "<td>".$diseaseArticles."</td>". PHP_EOL;
          echo "<td>".$diseasePhotos."</td>". PHP_EOL;
          echo "<td>".$diseaseTweets."</td>". PHP_EOL;
          echo "</tr>". PHP_EOL;
          echo "</table>" . PHP_EOL;

          echo "<br>".PHP_EOL;

        }

        $connector->disconnect();
        ?>
   </body>
   <footer>
     <h3>Grupo 7</h3>
     <p>Fábio Martins nº 48393</p>
     <p>Sara Gonçalves nº 50254</p>
     <p>Joana Matos nº 50074</p>
     <p>Rodrigo Matos nº 33451</p>
   </footer>
</html>
