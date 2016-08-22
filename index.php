<?php
use \Punic\Unit;
use \Punic\Language;
use \Punic\Territory;
require_once('punic/punic.php');
$obj = new Index;

class Index {
	function __construct() {
		$this->count_languages_of_the_world();
	}
	
	function count_languages_of_the_world() {
		$languages = Language::getAll(false, false, '');
		$langKeys = array_keys($languages);
		$finalArray = [];
		
		foreach($langKeys as $key) {
			$territoryLanguages = Territory::getLanguages($key, '', false);
			
			for($i = 0, $size = count($territoryLanguages); $i < $size; $i++) {
				$counter = 0;
				$id = '';
				$speakers = 0.0;
				$languages = $territoryLanguages[$i];
				
				foreach($languages as $language) {
					if ($counter == 0) {
						$id = $language;
					} else if($counter == 1) {
						$population = Territory::getPopulation($key);
						$speakers = ($language / 100) * $population;
					}
					
					$counter++;
				}
				
				if (array_key_exists($id, $finalArray)) {
					$value = $finalArray[$id];
					$newValue = $value + $speakers;
					$finalArray[$id] = $value + $speakers;
				} else {
					$finalArray[$id] = $speakers;
				}
			}
		}
		
		asort($finalArray);
		$finalKeys = array_keys($finalArray);
		
		foreach($finalKeys as $key) {
			if(isset($finalArray[$key])) {
				$speakers = $finalArray[$key];
				$name = Territory::getName($key, '');
				$this->console_log($key . ' ' . $name . ' ' . $speakers);
			}
		}
	}
	
	function console_log($data) {
		if (is_array($data)) {
			$output = "<script>console.log( '" . implode( ',', $data) . "' );</script>";
		} else {
			$output = "<script>console.log( '" . $data . "' );</script>";
		}

		echo $output;
	}
}
?>
