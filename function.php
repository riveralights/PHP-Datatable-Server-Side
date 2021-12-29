<?php

function get_total_all_records()
{
    include('database_connection.php');

    $statement = $connection->prepare("SELECT * FROM  movies");
    $statement->execute();
    $result = $statement->fetchAll();
    return $statement->rowCount();
}