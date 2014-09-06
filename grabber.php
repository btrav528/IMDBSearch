<?php
function GetConnection() {

	$conn = new mysqli('localhost', 'root', 'brian123', 'imdb');

	return $conn;

}

class imdb {

	//Creates the table, and deletes all previous entries if it was already created//

	function CreateTable() {
		$conn = GetConnection();

		$conn -> query("CREATE TABLE IF NOT EXISTS `imdb`.`top250` (
  `Rank` INT NOT NULL AUTO_INCREMENT,
  `Title` VARCHAR(99) NOT NULL,
  `Year` VARCHAR(45) NOT NULL,
  `number_of_votes` INT NOT NULL,
  `Rating` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`Rank`))
ENGINE = InnoDB");

		$error = $conn -> error;

		if ($error) {
			return array('db_error' => $error);
		} else {
			echo("all good ");
		}

		$conn -> query("TRUNCATE TABLE top250");
		$conn -> close();
	}

	//inserts all data into the table//
	function insertToTable($list) {
		$count = 0;
		$conn = GetConnection();
		while ($count <= 249) {
			$temp = $list[$count];
			$title = $temp['title'];
			$rank = $temp['rank'];
			$rating = $temp['rating'];
			$number_of_votes = $temp['number_of_votes'];
			var_dump($number_of_votes);
			$year = $temp['year'];

			$conn -> query("INSERT INTO `top250`(`Title`, `Year`, `Rank`, `number_of_votes`, `Rating`) VALUES ('$title','$year','$rank','$number_of_votes','$rating')");
			$count++;
		}
		echo mysqli_info($conn);
	}

	private function match_all($regex, $str, $i = 0) {
		if (preg_match_all($regex, $str, $matches) === false)
			return false;
		else
			return $matches[$i];
	}

	private function match($regex, $str, $i = 0) {
		if (preg_match($regex, $str, $match) == 1)
			return $match[$i];
		else
			return false;
	}

	function getTop250() {
		$html = file_get_contents("http://www.imdb.com/chart/top");
		$top250 = array();
		$rank = 1;
		foreach ($this->match_all('/<tr class="(even|odd)">(.*?)<\/tr>/ms', $html, 2) as $m) {

			$title = $this -> match('/<td class="titleColumn">.*?<a.*?>(.*?)<\/a>/msi', $m, 1);
			$year = $this -> match('/<td class="titleColumn">.*?<span name="rd".*?>\((.*?)\)<\/span>/msi', $m, 1);
			$number_of_votes = $this -> match('/<strong name="nv" data-value="(.*?)"/msi', $m, 1);
			$rating = $this -> match('/<strong name=".*?" data-value=".*?">(.*?)<\/strong>/msi', $m, 1);

			$top250[] = array("rank" => $rank, "title" => $title, "year" => $year, "rating" => $rating, "number_of_votes" => $number_of_votes);
			$rank++;
		}
		return $top250;
	}

}

$imdb = new imdb();

$list = $imdb -> getTop250();

echo("made list");

$imdb -> CreateTable();
echo("made table ");
$imdb -> insertToTable($list);
?>