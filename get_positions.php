<?php
require 'config_resume.php';

$query = "SELECT * FROM Positions WHERE Status=1 ORDER BY Postdate DESC";

$positions = array();

if($result = mysqli_query($connection, $query)) {
	if (mysqli_num_rows($result) > 0) {
		$count = 1;
		while ($row = mysqli_fetch_assoc($result)) {
			$temp_array = array();
			$query2 = "SELECT Skill_Id , Exp  FROM Position_Skills WHERE Position_Id=" . $row['Position_Id'] . ";";
			if ($result2 = mysqli_query($connection, $query2)) {
				if (mysqli_num_rows($result2) > 0) {
					while ($row2 = mysqli_fetch_assoc($result2)) {
						$temp_array2 = array();
						$query3 = "SELECT Skill FROM Skills WHERE Skill_Id=" . $row2['Skill_Id'] . ";";
						if ($result3 = mysqli_query($connection, $query3)) {
							$row3 = mysqli_fetch_assoc($result3);
							array_push($temp_array2 , $row3['Skill'] , $row2['Exp']);
							array_push($temp_array , $temp_array2);
						}
						mysqli_free_result($result3);
					}
				}
				mysqli_free_result($result2);
				$row['All_skills_temp'] = $temp_array;
			}
			$positions[$count] = json_encode($row);
			$count = $count + 1;
		}
	}
	mysqli_free_result($result);
}

echo json_encode($positions);

?>
