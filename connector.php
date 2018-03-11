<?php 
include "../utils/config.php"; 
include_once "../webservices/dbpediaDiseases.php";
include_once "../webservices/pubmedSearch.php";
include_once "../webservices/pubmedFeach.php";
include_once "../webservices/flickr.php";


/** replace this date by function that gets current date **/ 
$curr_date = "2018-03-10 06:00"; 

$conn = start_connection($servername,$username,$password,$dbname); 
updateDiseases($conn);


function start_connection($servername,$username,$password,$dbname) { 

        $conn = new mysqli($servername, $username, $password,$dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        echo "Connected successfully";
    
        return $conn;

} 

function updateDiseases($conn){
    
    /***  Get Diseases from DBpedia ***/ 
    
    $dbPediaDiseases = new DBPediaDiseases(5);
    $response = $dbPediaDiseases->getResponseDiseasesJson();
    $diseases = $dbPediaDiseases->getDiseases();

    /*** Percorre todas as doenças obtidas pela API **/
    
    foreach($diseases as $disease){ 
        
        //$name = $disease['label']['value']; 
        $dbpedia_id = $disease['wikiPageID']['value'];
        $abstract = $disease['abstract']['value'];
        $name="nometanga";
        
        /** Check and update Disease if exists **/
            
        $exists = checkExistingDisease($conn, $dbpedia_id,$name,$abstract);
        
        /** If Disease doesn't exist in database then it will insert **/
        if ($exists == 0) {
            
            /** Inserts disease in Database **/
            insertDisease($conn, $name, $dbpedia_id, $abstract); 
            
            
        }  
    }

}


print("all data was inserted"); 
mysqli_close($conn);



function insertDisease($conn,$name,$id,$abstract) {
    
    /** Inserts disease in database **/ 
    
    $stmt = $conn->prepare("INSERT INTO Disease (name, dbpedia_id, abstract, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssss", $name, $id, $abstract,$curr_date, $curr_date);
        $stmt->execute();
        $stmt->close();
}

function checkExistingDisease($conn, $dbpedia_id,$name,$abstract){
    
    $sql = "SELECT * FROM Disease WHERE dbpedia_id = '".$dbpedia_id."'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
    
        // output data of each row
        while($row = $result->fetch_assoc()) {
            
            if ($name != $row['name']) {
                
                $query = "UPDATE Disease SET name = ".$name."WHERE id =".$dbpedia_id;
                $result = $conn->query($query);
            }
            else if($abstract != $row["abstract"]){
               // tem que fazer update do abstract
            }
        }
    } 
    
    else {
        return 0;
    }    
}


?> 