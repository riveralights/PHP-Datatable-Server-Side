<?php

include('database_connection.php');
include('function.php');

if( isset($_POST['movie_id']) ) {
    $output = array();
    $statement = $connection->prepare(
        "SELECT * FROM movies WHERE id = '".$_POST["movie_id"]."' LIMIT 1"
    );
    $statement->execute();
    $result = $statement->fetchAll();

    foreach($result as $row) {
        $output['id'] = $row['id'];
        $output['title'] = $row['title'];
        $output['year'] = $row['year'];
        $output['genre'] = $row['genre'];
    }
    echo json_encode($output);
}