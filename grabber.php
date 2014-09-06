<?php 
class imdb{
private function match_all($regex, $str, $i = 0){
        if(preg_match_all($regex, $str, $matches) === false)
            return false;
        else
            return $matches[$i];
    }
 
    private function match($regex, $str, $i = 0){
        if(preg_match($regex, $str, $match) == 1)
            return $match[$i];
        else
            return false;
    }

public  function getTop250(){
       $html = file_get_contents("http://www.imdb.com/chart/top");
        $top250 = array();
        $rank = 1;
        foreach ($this->match_all('/<tr class="(even|odd)">(.*?)<\/tr>/ms', $html, 2) as $m) {
            
            $title = $this->match('/<td class="titleColumn">.*?<a.*?>(.*?)<\/a>/msi', $m, 1);
            $year = $this->match('/<td class="titleColumn">.*?<span name="rd".*?>\((.*?)\)<\/span>/msi', $m, 1);
            $numVotes = $this->match('/<strong name="nv" data-value="(.*?)"/msi', $m, 1);
			$rating= $this->match('/<strong name=".*?" data-value=".*?">(.*?)<\/strong>/msi', $m, 1);
            
            
            $top250[] = array("rank"=>$rank, "title"=>$title, "year"=>$year, "rating"=>$rating, "numVotes"=>$numVotes);
            $rank++;
        }
        return $top250;
    }
	}
	$imdb=new imdb();
	var_dump($imdb);
	$list=$imdb->getTop250();
	var_dump($list);

	
	?>