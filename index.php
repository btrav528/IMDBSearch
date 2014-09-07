<?php
function GetConnection() {

	$conn = new mysqli('localhost', 'root', 'brian123', 'imdb');

	return $conn;

}

if (!empty($_POST['year'])) {
	
	$sql = ("SELECT * FROM `top250` WHERE Year=" . $_POST['year']);
	$filename=md5($sql);
	if(file_exists ( $filename)){
		$result=file_get_contents($filename);
		$out = explode("<!-- E -->", $result);
		//while($count<=9||$result!=false){
		//$result=unserialize($result);
		//var_dump($out);
		$count=0;
		echo "<table class='table' border='1'><th>Rank</th><th>Title</th><th>Year</th><th>Number of votes</th><th>Rating</th>";
		while($count<count($out)-1){
			$row=unserialize($out[$count]);
			echo "<tr><td>" . $row['Rank'] . "</td><td>" . $row['Title'] . "</td><td>" . $row['Year'] . "</td><td>" . $row['number_of_votes'] . "</td><td>" . $row['Rating'] . "</td></tr>";

		$count++;
		}
		}
		
		
	else{

	$conn = GetConnection();
	
	$result = mysqli_query($conn, $sql);

	echo "<table class='table' border='1'><th>Rank</th><th>Title</th><th>Year</th><th>Number of votes</th><th>Rating</th>";
	
	
	$cacheFilename = md5($sql);
	
	
	$data=array();
	while ($row = mysqli_fetch_array($result)) {
		array_merge ($row, $data);
		var_dump($data);
		$serializedData = serialize($row);
		file_put_contents($cacheFilename, $serializedData."<!-- E -->", FILE_APPEND);
		echo "<tr><td>" . $row['Rank'] . "</td><td>" . $row['Title'] . "</td><td>" . $row['Year'] . "</td><td>" . $row['number_of_votes'] . "</td><td>" . $row['Rating'] . "</td></tr>";

	}


		mysqli_close($conn);
		}
	}
else {
	$conn = GetConnection();
	$sql = ("SELECT * FROM `top250` WHERE Rank <=10");
	$result = mysqli_query($conn, $sql);
	echo "<table class='table' border='1'><th>Rank</th><th>Title</th><th>Year</th><th>Number of votes</th><th>Rating</th>";

	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><td>" . $row['Rank'] . "</td><td>" . $row['Title'] . "</td><td>" . $row['Year'] . "</td><td>" . $row['number_of_votes'] . "</td><td>" . $row['Rating'] . "</td></tr>";

	}

	mysqli_close($conn);
}

?>
<html>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<head>
		<title>Movie Searcher</title>
	</head>
	<body>

		<form action="index.php" method="post">
			Year:
			<input type="text" name="year">
			<br>
			<input type="submit">
		</form>
		
	</body>
</html>