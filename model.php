<?php
function GetConnection() {

	$conn = new mysqli('localhost', 'root', 'brian123', 'imdb');

	return $conn;

}

if (!empty($_POST)) {
	var_dump($_POST);

	$conn = GetConnection();
	$sql = ("SELECT * FROM `top250` WHERE Year=" . $year);
	$result = mysqli_query($conn, $sql);
	echo "<table class='table' border='1'><th>Rank</th><th>Title</th><th>Year</th>";

	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td>" . $row['Rank'] . "</td><td>" . $row['Title'] . "</td><td>" . $row['Year'] . "</td></tr>";

		mysqli_close($conn);
	}
} else {
	$conn = GetConnection();
	$sql = ("SELECT * FROM `top250` WHERE Rank <=10");
	$result = mysqli_query($conn, $sql);
	echo "<table class='table' border='1'><th>Rank</th><th>Title</th><th>Year</th><th>Number of votes</th><th>Rating</th>";

	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td>" . $row['Rank'] . "</td><td>" . $row['Title'] . "</td><td>" . $row['Year'] . "</td><td>" . $row['number_of_votes'] . "</td><td>" . $row['Rating'] . "</td></tr>";

	}

	mysqli_close($conn);
}
header("Location: index.php");
?>