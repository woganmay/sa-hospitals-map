<?php
	
/* Extract geolocations */
$file = fopen("South_African_Hospitals_Survey_2011-2012.csv", "r");

$n = 0;
$headers = array();

$find = array(
	'uid',
	'name',
	'classification',
	'ownership',
	'manager',
	'email',
	'cel',
	'overall_performance',
	'Latitude',
	'Longitude'
);

$output = array();

while($row = fgetcsv($file))
{
	if ($n == 0)
	{
		$headers = $row;	
	}
	else
	{
		
		// Correct geolocation
		$row[133] = str_replace("\n", "", $row[133]);
		
		// Add a new column for the coordinates
		preg_match("/\((.*)\)/Ui", $row[133], $match);
		
		$p = explode(",", $match[1]);
		$lat = trim($p[0]);
		$lng = trim($p[1]);
		
		$headers[134] = "Latitude";
		$headers[135] = "Longitude";
		$row[134] = (double)$lat;
		$row[135] = (double)$lng;
		
		$element = array();
		
		foreach($headers as $n => $h)
		{
			if (in_array($h, $find)) $element[$h] = $row[$n];
		}
		
		$output[] = $element;
		
	}
	
	$n++;
	
}

header("Content-Type: application/json");
echo json_encode($output);