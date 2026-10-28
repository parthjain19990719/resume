<!DOCTYPE html>
<?php
$title="Advanced Image Inc.";
$fileName="form_submit.php";
include_once("template.php");
function displayPage()
{
?>
<html lang="en">

<?php
require "config_resume.php";

if(isset($_POST['addresume'])){
	$name = mysqli_real_escape_string($connection,$_POST['Name']);
	$tags = json_decode($_POST['tags_array']);
	$keywords = json_decode($_POST['tags_keywords_array']);
	$exp = json_decode($_POST['tags_exp_array']);
	$zipcode = mysqli_real_escape_string($connection,$_POST['zipcode']);
	$phone_no = mysqli_real_escape_string($connection,$_POST['telephone']);
	$email = mysqli_real_escape_string($connection,$_POST['email']);
	$salary_type = mysqli_real_escape_string($connection,$_POST['salary_type']);
	$salary = mysqli_real_escape_string($connection,$_POST['salary']);
	$position_type = mysqli_real_escape_string($connection,$_POST['position_type']);
	$work_status = mysqli_real_escape_string($connection,$_POST['work_status']);
	$visa_transfer = mysqli_real_escape_string($connection,$_POST['visa_transfer']);
	$Relocate = mysqli_real_escape_string($connection,$_POST['Relocate']);
	$Linkedin_URL =  mysqli_real_escape_string($connection,$_POST['Linkedin_URL']);
	
	$query = "INSERT INTO Candidates (Name , Phone , Email , Zipcode , Salary_Type , Salary , Position_Type , Work_Status , Visa_Transfer , Relocate , Linkedin_URL) VALUES ('$name','$phone_no','$email','$zipcode','$salary_type','$salary','$position_type','$work_status','$visa_transfer','$Relocate','$Linkedin_URL');";
	$last_id = "";
	if(mysqli_query($connection,$query)){
		$last_id = mysqli_insert_id($connection);
		if (isset($_FILES['file'])) {
                	$file_name = $_FILES['file']['name'];
                	$file_tmp = $_FILES['file']['tmp_name'];
			$extension = pathinfo($file_name, PATHINFO_EXTENSION);
			$final_file_name = $name."_".$last_id.".".$extension;
                	if (move_uploaded_file($file_tmp, "Uploads/".$final_file_name)) {
				$query_file = "UPDATE Candidates SET Resume_filename='$final_file_name' WHERE Candidate_Id=$last_id ;";
				mysqli_query($connection,$query_file);
                	}
		}
		// inserting tags
		if (count($tags)==count($keywords)){
			for ($x = 0 ; $x < count($tags); $x++){
				$query_tag = "";
				if ($tags[$x] == "-1"){
					$query_new_tag = "INSERT INTO Skills (Skill , Verified) VALUES ('".$keywords[$x]."', '0' );";
					mysqli_query($connection,$query_new_tag);
					$last_tag_id = mysqli_insert_id($connection);
					$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$last_id','" .$last_tag_id. "','". $exp[$x] ."');";
				}
				else{
					$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$last_id','" .$tags[$x]. "','". $exp[$x] ."');";
				}	
				mysqli_query($connection,$query_tag);
			}
		}
	}
	else {
		// Duplicate entry
		if (mysqli_errno($connection) == 1062) {
			$query_update = "UPDATE Candidates SET Name='$name' , Phone='$phone_no' , Zipcode='$zipcode' , Salary_Type='$salary_type' , Salary='$salary' , Position_Type='$position_type' , Work_Status='$work_status' , Visa_Transfer='$visa_transfer' , Relocate='$Relocate' , Linkedin_URL='$Linkedin_URL' WHERE Email='$email';";
			if(mysqli_query($connection,$query_update)) {
				$query_last_id = "SELECT Candidate_Id FROM Candidates WHERE Email='$email';";
				$last_id = "";
				$result_last_id = mysqli_query($connection, $query_last_id);
				if (($result_last_id) && (mysqli_num_rows($result_last_id) > 0)){
                    			$row_last_id = mysqli_fetch_assoc($result_last_id);
					$last_id = $row_last_id['Candidate_Id'];
				}
				if (isset($_FILES['file']) && ($last_id)) {
		            		$file_name = $_FILES['file']['name'];
					$file_tmp = $_FILES['file']['tmp_name'];
					$extension = pathinfo($file_name, PATHINFO_EXTENSION);
		   			$final_file_name = $name."_".$last_id.".".$extension;
					if (move_uploaded_file($file_tmp, "Uploads/".$final_file_name)) {
						$query_file = "UPDATE Candidates SET Resume_filename='$final_file_name' WHERE Candidate_Id=$last_id ;";
		 				mysqli_query($connection,$query_file);
					}
				}
				//inserting/updating skills
				if ((count($tags)==count($keywords)) && ($last_id)) {
					for ($x = 0 ; $x < count($tags); $x++){
						$query_tag = "";
						if ($tags[$x] == "-1"){
							$query_new_tag = "INSERT INTO Skills (Skill , Verified) VALUES ('".$keywords[$x]."', '0' );";
							mysqli_query($connection,$query_new_tag);
							$last_tag_id = mysqli_insert_id($connection);
							$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$last_id','" .$last_tag_id. "','". $exp[$x] ."');";
							mysqli_query($connection,$query_tag);
						}
						else {
							$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$last_id','" .$tags[$x]. "','". $exp[$x] ."');";
							mysqli_query($connection,$query_tag);
							// duplicate insertion updating
							if (mysqli_errno($connection) == 1062) {
								$query_update_skill = "UPDATE Candidate_Skills SET Exp=".$exp[$x]." WHERE Candidate_Id=$last_id AND Skill_Id=".$tags[$x].";";
								mysqli_query($connection,$query_update_skill);
							}
						}
					}
				}
			}
		}
	}
}
?>

<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resume</title>
        <link rel="stylesheet" href="form_submit.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="stylesheet.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body>
	<div class="thank_you_form">
		<div class="thank-you-container">
			<div class="thank-you-message">Thank you for applying!</div>
			<p class="appreciate">We appreciate your time and will get back to you as soon as feasible.</p>
		</div>
	</div>

<script>

var timeInterval = 5000;

setTimeout(go_to_carrer_page, timeInterval);
function go_to_carrer_page() {
   document.location.href = "career.php";
}

</script>

</body>
</html>

<?php } ?>
