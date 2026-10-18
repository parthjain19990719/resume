<!DOCTYPE html>
<?php
$noHead=1;
$fileName="job_application.php";
$company_name = "Advanced Image Inc.";

require 'config_resume.php';

if (isset($_GET['Position_Id'])) {
	$rand_id = mysqli_real_escape_string($connection,$_GET['Position_Id']);
	$query = "SELECT * FROM Positions WHERE Position_random_Id='$rand_id' AND Status='1';";
	if($result = mysqli_query($connection, $query)) {
		if (mysqli_num_rows($result) > 0) {
			// From Position Page
			$row = mysqli_fetch_assoc($result);
			$scroll_data_array = array();
			$pos_id = $row['Position_Id'];
                        $scroll_data_array = $row;
			$scroll_data_array['All_Skills'] = array();

			//Banner
			if ($row['Banner']) {
				$temp_string = "";
				if ($row['Client_Name']) {
					$temp_string = "You exclusively authorize " .$company_name. " to represent you for the position (" .$row['Title']. ") with our client (" .$row['Client_Name']. ").";
				} else {
					$temp_string = "You exclusively authorize " .$company_name. " to represent you for the position (" .$row['Title']. ") with our client.";
				}
				$scroll_data_array['Banner_String'] = $temp_string;
			}

			// Uploads and References Check for step2 and step3
			$Step3 = false;
			if($row['References_Required'] > 0) { $Step3 = true; }
			$Step2 = false;
			$file_query = "SELECT File_Name FROM Position_Files WHERE Position_Id='$pos_id' ;";
			if (($result_file = mysqli_query($connection, $file_query)) && (mysqli_num_rows($result_file) > 0)) { $Step2 = true; }

			// checking for custom_questions
			$scroll_data_array['Question_list'] = array();
			$custom_query_check = "SELECT Custom_Question_Id FROM Custom_Question_Position WHERE Position_Id='$pos_id';";
			if ($result_custom_check = mysqli_query($connection, $custom_query_check)) {
				if (mysqli_num_rows($result_custom_check) > 0) {
					while ($custom_row = mysqli_fetch_assoc($result_custom_check)) {
						//getting custom question
						$custom_id = $custom_row['Custom_Question_Id'];
						$question_query = "SELECT * FROM Custom_Questions WHERE Custom_Question_Id='$custom_id';";
						if ($result_custom = mysqli_query($connection, $question_query)) {
							$question_row = mysqli_fetch_assoc($result_custom);
							array_push($scroll_data_array['Question_list'],$question_row);
						}
					}
				}
			}
			$query2 = "SELECT Skill_Id , Exp FROM Position_Skills WHERE Position_Id='$pos_id';";
			if($result2 = mysqli_query($connection, $query2)) {
				if (mysqli_num_rows($result2) > 0) {
					while($row2 = mysqli_fetch_assoc($result2)) {
						$temp_array = array();
						$s_id = $row2['Skill_Id'];
						$query3 = "SELECT Skill FROM Skills WHERE Skill_Id=$s_id;";
						if ($result3 = mysqli_query($connection, $query3)) {
							if (mysqli_num_rows($result3) > 0) {
								$row3 = mysqli_fetch_assoc($result3);
								array_push($temp_array , $row3['Skill'] , $row2['Skill_Id'] , $row2['Exp']);
								array_push($scroll_data_array['All_Skills'] , $temp_array);
							}
						}
					}
				}
			}

			//skills meta tags
			$skills_meta_tags = '';
			for ($i = 0 ; $i < count($scroll_data_array['All_Skills']); $i++) {
				$skills_meta_tags = $skills_meta_tags . ' , ' . $scroll_data_array['All_Skills'][$i][0];
			}

			echo "<html lang=\"en\"><head><meta charset=\"UTF-8\"><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
			<title>Job application for " . $row['Title'] ."</title>
			<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
			<meta name=\"description\" content=\"Job Position for " . $row['Title'] .". Candidates can apply and also provide their Employers information if looking only for Corp to Corp. Even Recruiters can also apply in behalf of their employees.\"\>
			<meta name=\"robots\" content=\"index, follow, noarchive\"\>
			<meta name=\"keywords\" content=\"Job, Openings" . $skills_meta_tags . "\"\>
			<link rel=\"stylesheet\" href=\"form.css\">
			<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3\" crossorigin=\"anonymous\">
			<link href=\"stylesheet.css\" rel=\"stylesheet\" type=\"text/css\" />
			<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p\" crossorigin=\"anonymous\"></script>";
                        $desc = str_replace(array("\r\n", "\n", "\r"), ' ', $row['Description']);
                        $string_structured_data = '{ "@context" : "https://schema.org/", "@type" : "JobPosting", "title" : "'. $row['Title'] .'", "description" : "<p>'. $desc .'</p>", "datePosted" : "'. $row['Postdate'] .'", "validThrough" : "'. $row['Postdate'] .'T00:00", "applicantLocationRequirements": { "@type": "Country", "name": "USA" }, "jobLocation": { "@type": "Place", "address": { "@type": "PostalAddress", "addressLocality": "'. $row['City'] .'" ,"addressRegion": "'. $row['State'] .'", "addressCountry": "US" } }, "employmentType": "FULL_TIME", "hiringOrganization" : { "@type" : "Organization", "name" : "'. $row['Client_Name'] .'"}';
                        if ($row['Position_Type'] == 'Remote') { $string_structured_data = $string_structured_data . ', "jobLocationType": "TELECOMMUTE"'; }
                        $string_structured_data = $string_structured_data . ' }';
			$string_structured_data = str_replace("'","\'",$string_structured_data);
			echo "<script type=\"application/ld+json\">";
			echo $string_structured_data;
			echo "</script></head>";

			function displayPage() { require "form_position.php"; }
		}
		else { 
			function displayPage() { echo "<h2> Position not found </h2>"; }
		}
	} else { 
		function displayPage() { echo "<h2> Position not nound </h2>"; }
	}
	include_once("template.php");
} else {
	echo "<html lang=\"en\">
	<head><meta charset=\"UTF-8\">
		<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
		<title>Job application</title>
		<meta name=\"description\" content=\"Job Position. Candidates can apply and also provide their Employers information if looking only for Corp to Corp. Even Recruiters can also apply in behalf of their employees.\"\>
		<meta name=\"robots\" content=\"index, follow, noarchive\"\>
		<meta name=\"keywords\" content=\"Job, Openings\"\>
		<link rel=\"stylesheet\" href=\"form.css\">
		<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3\" crossorigin=\"anonymous\">
		<link href=\"stylesheet.css\" rel=\"stylesheet\" type=\"text/css\" />
		<script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p\" crossorigin=\"anonymous\"></script>
	</head>";
	function displayPage() { require 'form_general.php'; }
	include_once("template.php");
}

?>
