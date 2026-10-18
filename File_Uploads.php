<!DOCTYPE html>
<?php
$title="Advanced Image Inc.";
$fileName="Files_Uploads.php";
require "config_resume.php";
require "Resume_lib.php";
include_once("template.php");
function displayPage()
{
?>

<?php
require "config_resume.php";

$email_sended_p = 0;
$pos_id = 0;
$no_of_references = 0;
$suggested_skills_name_send_array =  array();
$suggested_skills_id_send_array =  array();
$Step3 = false;

if(isset($_POST['addresume'])) {
	$rand_id = mysqli_real_escape_string($connection,$_POST['Position_Random_Id']);
	$name = mysqli_real_escape_string($connection,$_POST['Name']);
    	$tags = json_decode($_POST['tags_array']);
    	$keywords = json_decode($_POST['tags_keywords_array']);
	$exp = json_decode($_POST['tags_exp_array']);
	$suggested_skills_name_send_array = json_decode($_POST['suggested_skills_name_send_array']);
	$suggested_skills_id_send_array = json_decode($_POST['suggested_skills_id_send_array']);
	$phone_no = mysqli_real_escape_string($connection,$_POST['telephone']);
	$zipcode = mysqli_real_escape_string($connection,$_POST['zipcode']);
    	$email = mysqli_real_escape_string($connection,$_POST['email']);
    	$salary_type = mysqli_real_escape_string($connection,$_POST['salary_type']);
	$salary = mysqli_real_escape_string($connection,$_POST['salary']);
	$Hire_Description = mysqli_real_escape_string($connection,$_POST['Hire_Description']);
    	$work_status = mysqli_real_escape_string($connection,$_POST['work_status']);
	$visa_transfer = mysqli_real_escape_string($connection,$_POST['visa_transfer']);
	$Linkedin_URL = mysqli_real_escape_string($connection,$_POST['Linkedin_URL']);
	$employer_emailid = "";
	if (isset($_POST['Employer_EmailId'])){
		$employer_emailid = mysqli_real_escape_string($connection,$_POST['Employer_EmailId']);
	}
	$custom_question_id = json_decode($_POST['Custom_Question_Id']);
	$referer = mysqli_real_escape_string($connection, $_POST['Referer']);

	$pos_query = "SELECT Position_Id , Auto_Email_To_Candidate , References_Required FROM Positions WHERE Position_Random_Id=$rand_id;";
	if ($result_pos = mysqli_query($connection,$pos_query)) { // getting position id
		if (mysqli_num_rows($result_pos) > 0) {
			$row_pos = mysqli_fetch_assoc($result_pos);
			$pos_id = $row_pos['Position_Id'];
			if($row_pos['References_Required'] > 0) { $Step3 = true; }
			$email_to_candidate = $row_pos['Auto_Email_To_Candidate'];
			$no_of_references = $row_pos['References_Required'];
			$candidate_query = "SELECT Candidate_Id FROM Candidates WHERE Email='$email';";
			$c_id = "";
			if ($result_candidate = mysqli_query($connection,$candidate_query)) {
				if (mysqli_num_rows($result_candidate) > 0) { 
					//candidate exists
					$row_candidate = mysqli_fetch_assoc($result_candidate);
					$c_id = $row_candidate['Candidate_Id'];
					$update_query = "UPDATE Candidates SET Name='$name' , Phone='$phone_no' , Zipcode='$zipcode' , Linkedin_URL='$Linkedin_URL' WHERE Candidate_Id='$c_id';";
					mysqli_query($connection,$update_query);
					//Now need to check this exists in Candidate_Postion table
					$check_query = "SELECT Work_Status FROM Candidates_Positions WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id';";
					if ($check_result = mysqli_query($connection,$check_query)) {
						$update_insert_pos_query = "";
						if (mysqli_num_rows($check_result) > 0) { //Already Candidate exists in Candidate_Position Table
							$update_insert_pos_query = "UPDATE Candidates_Positions SET Salary_Type='$salary_type' , Salary='$salary' , Work_Status='$work_status' , Visa_Transfer='$visa_transfer' , Hire_Description='$Hire_Description' , Referer='$referer' , Employer_EmailId='$employer_emailid' WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id';";
						} else { //Need to insert in Candidate_Positions
							$update_insert_pos_query = "INSERT INTO Candidates_Positions ( Candidate_Id , Position_Id , Salary_Type , Salary , Work_Status ,  Visa_Transfer , Hire_Description , Referer , Employer_EmailId) VALUES ('$c_id','$pos_id','$salary_type','$salary','$work_status','$visa_transfer','$Hire_Description','$referer','$employer_emailid');";
						}
						if ((mysqli_query($connection,$update_insert_pos_query)) && (isset($_FILES['file']))) {
							$file_name = $_FILES['file']['name'];
							$file_tmp = $_FILES['file']['tmp_name'];
							$extension = pathinfo($file_name, PATHINFO_EXTENSION);
							$final_file_name = str_replace(" ","_",$name) . "_" . $c_id . "_" . $pos_id . "." .$extension;
							if (move_uploaded_file($file_tmp, "Uploads/".$final_file_name)) {
								$query_file = "UPDATE Candidates_Positions SET Resume_filename='$final_file_name' WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id';";
								mysqli_query($connection,$query_file);
							}
						}
						if (count($tags)==count($keywords)) {
							for ($x = 0 ; $x < count($tags); $x++) {
								$query_tag = "";
								if ($tags[$x] == "-1"){
									$query_new_tag = "INSERT INTO Skills (Skill , Verified) VALUES ('".$keywords[$x]."', '0' );";
									mysqli_query($connection,$query_new_tag);
									$last_tag_id = mysqli_insert_id($connection);
									$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$c_id','" .$last_tag_id. "','". $exp[$x] ."');";
									mysqli_query($connection,$query_tag);
								} else {
									// Need to check if skill is already present
									$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$c_id','" .$tags[$x]. "','". $exp[$x] ."');";
									mysqli_query($connection,$query_tag);
									// duplicate insertion updating
									if (mysqli_errno($connection) == 1062) {
										$query_update_skill = "UPDATE Candidate_Skills SET Exp=".$exp[$x]." WHERE Candidate_Id=$c_id AND Skill_Id=".$tags[$x].";";
										mysqli_query($connection,$query_update_skill);
									}
								}
							}
						}
					}
				} else {
					//new candidate
					$insert_query = "INSERT INTO Candidates (Name , Phone , Zipcode , Email , Salary_Type , Salary , Work_Status , Visa_Transfer , Linkedin_URL) VALUES ('$name','$phone_no','$zipcode','$email','$salary_type','$salary','$work_status','$visa_transfer','$Linkedin_URL');";
					if (mysqli_query($connection,$insert_query)) {
						$c_id = mysqli_insert_id($connection);
						$insert_pos_query = "INSERT INTO Candidates_Positions ( Candidate_Id , Position_Id , Salary_Type , Salary , Work_Status , Visa_Transfer , Hire_Description , Referer , Employer_EmailId) VALUES ('$c_id','$pos_id','$salary_type','$salary','$work_status','$visa_transfer','$Hire_Description','$referer','$employer_emailid');";
						if ((mysqli_query($connection,$insert_pos_query)) && (isset($_FILES['file']))) {
							$file_name = $_FILES['file']['name'];
							$file_tmp = $_FILES['file']['tmp_name'];
							$extension = pathinfo($file_name, PATHINFO_EXTENSION);
							$final_file_name = str_replace(" ","_",$name) . "_" . $c_id . "_" . $pos_id . "." .$extension;
							if (move_uploaded_file($file_tmp, "Uploads/".$final_file_name)) {
								$query_file = "UPDATE Candidates_Positions SET Resume_filename='$final_file_name' WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id';";
								mysqli_query($connection,$query_file);
							}
						}
						// inserting tags
						if (count($tags)==count($keywords)) {
							for ($x = 0 ; $x < count($tags); $x++){
								$query_tag = "";
								if ($tags[$x] == "-1"){
									$query_new_tag = "INSERT INTO Skills (Skill , Verified) VALUES ('".$keywords[$x]."', '0' );";
									mysqli_query($connection,$query_new_tag);
									$last_tag_id = mysqli_insert_id($connection);
									$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$c_id','" .$last_tag_id. "','". $exp[$x] ."');";
								} else {
									$query_tag = "INSERT INTO Candidate_Skills (Candidate_Id , Skill_Id , Exp) VALUES ('$c_id','" .$tags[$x]. "','". $exp[$x] ."');";
								}
								mysqli_query($connection,$query_tag);
							}
						}
					}
				}
				//inserting/updating custom_question if exists
				if ($custom_question_id) {
					foreach ($custom_question_id as $custom_id) {
						$key = "custom_question_" . $custom_id;
						if (isset($_POST[$key])) {
							$custom_query_check = "SELECT * FROM Custom_Question_Answer WHERE Custom_Question_Id='$custom_id' AND Position_Id='$pos_id' AND Candidate_Id='$c_id';";
							if ($result_custom_check = mysqli_query($connection,$custom_query_check)) {
								$custom_insert_update_query = "";
								$answer = mysqli_real_escape_string($connection,$_POST[$key]);
								if (mysqli_num_rows($result_custom_check) > 0) {
									// already exists update
									$custom_insert_update_query = "UPDATE Custom_Question_Answer SET Custom_Question_Answer='$answer' WHERE Custom_Question_Id='$custom_id' AND Position_Id='$pos_id' AND Candidate_Id='$c_id';";
								} else { 
									//insert
									$custom_insert_update_query = "INSERT INTO Custom_Question_Answer ( Custom_Question_Id , Position_Id , Candidate_Id , Custom_Question_Answer) VALUES ('$custom_id','" .$pos_id. "','" .$c_id. "','" .$answer. "');";
								}
								mysqli_query($connection,$custom_insert_update_query);
							}
						}
					}
				}
				//sending email to candidate if Auto_Email_To_Candidate is on
				if ($email_to_candidate == 1) {
				    send_email($pos_id,$c_id);
				    $email_sended_p = 1;
				}
			}
		}
	}
}

//Checking for Step3
$action_file = 'form_submit_position.php';
if($Step3) {$action_file = 'References.php'; }

?>

<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resume</title>
	<link rel="stylesheet" href="form_submit.css">
	<link rel="stylesheet" href="form.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="stylesheet.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<style>
		.alert_box {
			background-color: #ffcc00;
			color: black;
		}
	</style>
</head>
<body>
	<div class="alert_box">
		<span style="display: block; margin: auto; width: 33%;">Alert! Your application is still incomplete.</span>
	</div>
	<form class="reg_form" action=<?php echo "$action_file"; ?> method="post" enctype="multipart/form-data" id="file_form">
		<h2 class="apply_h1"> Documents Required </h2>
		<input type="hidden" name="Position_Id" value="<?php echo $pos_id; ?>">
		<input type="hidden" name="Candidate_Id" value="<?php echo $c_id; ?>">
		<input type="hidden" name="suggested_skills_id_send_array" id="suggested_skills_id_send_array" value='<?php echo json_encode($suggested_skills_id_send_array); ?>'>
              	<input type="hidden" name="suggested_skills_name_send_array" id="suggested_skills_name_send_array" value='<?php echo json_encode($suggested_skills_name_send_array); ?>'>
		<?php // Get files from database
		$file_query = "SELECT File_Id , File_Name FROM Position_Files WHERE Position_Id='$pos_id';";
		if (($result_file = mysqli_query($connection,$file_query)) && (mysqli_num_rows($result_file) > 0)) {
			$file_count = 1;
			while ($row_file = mysqli_fetch_array($result_file)) {
				$filename = $row_file['File_Name'];
				$pos = strpos($filename,'.');
				$only_filename = substr($filename,0,$pos);
				$f_id = $row_file['File_Id'];
				echo "<div class='form-row'><div class='form-group col-md-8'>";
				echo "<input type='hidden' name='File_Id_$file_count' value='$f_id'>";
				echo "<label class='form-label' for='File_Upload_$file_count'>$file_count. $only_filename: <a href='Uploads/docs/$filename' download><img src='images/download-button.png' width='120' height='45'></a> Fill and Upload Below</label>";
				echo "<input type='file' class='form-control' placeholder='Upload $only_filename' required name='File_Upload_$file_count' >";
				$file_count = $file_count + 1 ;
				echo "</div></div>";
			}
			echo "<input type='submit' id='submit' name='addfiles' class='btn btn-primary'></input>";
		} ?>
		</form>
	</div>

</body>

<?php } ?>
