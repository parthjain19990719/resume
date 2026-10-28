<?php

require 'config_resume.php';

$data = json_decode(file_get_contents('php://input'), true);

$result_array =  array();
if ((isset($data['Email'])) && (isset($data['Position_Random_Id']))) {
	$email = mysqli_real_escape_string($connection,$data['Email']);
	$rand_id = mysqli_real_escape_string($connection,$data['Position_Random_Id']);
	$pos_query = "SELECT Position_Id From Positions WHERE Position_Random_Id='$rand_id';";
	if($result_pos = mysqli_query($connection, $pos_query)) {
		if (mysqli_num_rows($result_pos) > 0) {
			if (mysqli_num_rows($result_pos) > 0) {
				$row_pos = mysqli_fetch_assoc($result_pos);
				$pos_id = $row_pos['Position_Id'];
				$query = "SELECT Candidate_Id , Name , Zipcode , Phone , Work_Status , Visa_Transfer , Relocate , Salary_Type , Salary , Linkedin_URL FROM Candidates WHERE Email='$email';";
				if($result = mysqli_query($connection, $query)) {
					if (mysqli_num_rows($result) > 0) {
						$result_array['Already_present'] = true;
						$row = mysqli_fetch_assoc($result);
						$result_array['auto_data'] = $row;
						$c_id = $row['Candidate_Id'];
						$other_query = "SELECT Work_Status , Visa_Transfer , Salary_Type , Salary , Hire_Description FROM Candidates_Positions WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id';";
						if ($result_other = mysqli_query($connection, $other_query)) {
							if (mysqli_num_rows($result_other) > 0) {
								$row_other = mysqli_fetch_assoc($result_other);
								foreach ($row_other as $x => $y) { $result_array['auto_data']["$x"] = $y; }
							}
							mysqli_free_result($result_other);
						}
						$query2 = "SELECT Skill_Id , Exp FROM Candidate_Skills WHERE Candidate_Id='$c_id'";
						$result_array['auto_data']['skill_list'] = array();
						$skills_array = array();
						if ($result2 = mysqli_query($connection, $query2)) {
							if (mysqli_num_rows($result2) > 0) {
								while($row2 = mysqli_fetch_assoc($result2)) {
									$s_id = $row2['Skill_Id'];
									$query3 = "SELECT Skill FROM Skills WHERE Skill_Id='$s_id' AND Verified=1;";
									if (($result3 = mysqli_query($connection, $query3)) && (mysqli_num_rows($result3) > 0)) {
										$row3 = mysqli_fetch_assoc($result3);
										$skills_array['Skill'] = $row3['Skill'];
										$skills_array['Skill_Id'] = $row2['Skill_Id'];
										$skills_array['Exp'] = $row2['Exp'];
										array_push($result_array['auto_data']['skill_list'], $skills_array);
									}
								}
							}
							mysqli_free_result($result2);
						}
					}
					mysqli_free_result($result);
				}
			}
		}
		mysqli_free_result($result_pos);
	}
} else if (isset($data['Email'])) {
	$email = mysqli_real_escape_string($connection,$data['Email']);
	$query = "SELECT Candidate_Id , Name , Zipcode , Phone , Work_Status , Visa_Transfer , Relocate , Salary_Type , Salary , Position_Type , Linkedin_URL FROM Candidates WHERE Email='$email';";
	if($result = mysqli_query($connection, $query)) {
		if (mysqli_num_rows($result) > 0) {
			$result_array['Already_present'] = true;
			$row = mysqli_fetch_assoc($result);
			$result_array['auto_data'] = $row;
			$c_id = $row['Candidate_Id'];
			$query2 = "SELECT Skill_Id , Exp FROM Candidate_Skills WHERE Candidate_Id='$c_id'";
			$result_array['auto_data']['skill_list'] = array();
			$skills_array = array();
			if ($result2 = mysqli_query($connection, $query2)) {
				if (mysqli_num_rows($result2) > 0) {
					while($row2 = mysqli_fetch_assoc($result2)) {
						$s_id = $row2['Skill_Id'];
						$query3 = "SELECT Skill FROM Skills WHERE Skill_Id='$s_id' AND Verified=1;";
						if (($result3 = mysqli_query($connection, $query3)) && (mysqli_num_rows($result3) > 0)) {
							$row3 = mysqli_fetch_assoc($result3);
							$skills_array['Skill'] = $row3['Skill'];
							$skills_array['Skill_Id'] = $row2['Skill_Id'];
							$skills_array['Exp'] = $row2['Exp'];
							array_push($result_array['auto_data']['skill_list'], $skills_array);
						}
					}
				}
				mysqli_free_result($result2);
			}
		}
		mysqli_free_result($result);
	} else {
		$result_array['Already_present'] = false;
	}
}
$json = json_encode($result_array);

echo $json;

?>
