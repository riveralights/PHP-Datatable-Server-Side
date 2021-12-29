<?php
include('database_connection.php');
include('function.php');
if(isset($_POST["operation"]))
{
	if($_POST["operation"] == "Add")
	{
		$statement = $connection->prepare("
			INSERT INTO movies (title, year, genre) VALUES (:title, :year, :genre)");
		$result = $statement->execute(
			array(
				':title'	=>	$_POST["title"],
				':year'	=>	$_POST["year"],
				':genre'	=>	$_POST["genre"]
			)
		);
	}
	if($_POST["operation"] == "Edit")
	{
		$statement = $connection->prepare(
			"UPDATE movies
			SET title = :title, year = :year, genre = :genre WHERE id = :id");
		$result = $statement->execute(
			array(
				':title'	=>	$_POST["title"],
				':year'	=>	$_POST["year"],
				':genre'	=>	$_POST["genre"],
				':id'			=>	$_POST["movie_id"]
			)
		);
	}
}

?>