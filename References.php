<!DOCTYPE html>
<?php
$title="Advanced Image Inc.";
$fileName="References.php";
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
$c_id = 0;
$no_of_references = 0;
$suggested_skills_name_send_array =  array();
$suggested_skills_id_send_array =  array();

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
	$Relocate = mysqli_real_escape_string($connection,$_POST['Relocate']);
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
			$email_to_candidate = $row_pos['Auto_Email_To_Candidate'];
			$no_of_references = $row_pos['References_Required'];
			$candidate_query = "SELECT Candidate_Id FROM Candidates WHERE Email='$email';";
			$c_id = "";
			if ($result_candidate = mysqli_query($connection,$candidate_query)) {
				if (mysqli_num_rows($result_candidate) > 0) { 
					//candidate exists
					$row_candidate = mysqli_fetch_assoc($result_candidate);
					$c_id = $row_candidate['Candidate_Id'];
					$update_query = "UPDATE Candidates SET Name='$name' , Phone='$phone_no' , Zipcode='$zipcode' , Relocate='$Relocate' , Linkedin_URL='$Linkedin_URL' WHERE Candidate_Id='$c_id';";
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
					$insert_query = "INSERT INTO Candidates (Name , Phone , Zipcode , Email , Salary_Type , Salary , Work_Status , Visa_Transfer , Relocate , Linkedin_URL) VALUES ('$name','$phone_no','$zipcode','$email','$salary_type','$salary','$work_status','$visa_transfer','$Relocate','$Linkedin_URL');";
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

if(isset($_POST['addfiles'])) {
        $pos_id = mysqli_real_escape_string($connection,$_POST['Position_Id']);
	$c_id = mysqli_real_escape_string($connection,$_POST['Candidate_Id']);
	$suggested_skills_name_send_array = json_decode($_POST['suggested_skills_name_send_array']);
	$suggested_skills_id_send_array = json_decode($_POST['suggested_skills_id_send_array']);
	//getting no of references
	$pos_query = "SELECT References_Required FROM Positions WHERE Position_Id=$pos_id;";
	if ($result_pos = mysqli_query($connection,$pos_query)) { // getting no of references
		if (mysqli_num_rows($result_pos) > 0) {
			$row_pos = mysqli_fetch_assoc($result_pos);
			$no_of_references = $row_pos['References_Required'];
		}
	}
        $i = 1;
        while (isset($_POST["File_Id_$i"])) {
                $file_key = "File_Upload_$i";
                $file_id_key = "File_Id_$i";
                $f_id = mysqli_real_escape_string($connection,$_POST[$file_id_key]);
                $check_query = "SELECT File_Name FROM Candidate_Positions_Files WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id' AND File_Id='$f_id';";
                if ($result_check = mysqli_query($connection,$check_query)) {
                        if (isset($_FILES[$file_key])) {
                                $file_name = $_FILES[$file_key]['name'];
                                $file_tmp = $_FILES[$file_key]['tmp_name'];
                                $extension = pathinfo($file_name, PATHINFO_EXTENSION);
                                $final_file_name = "File_" . $c_id . "_" . $pos_id . "_" . $f_id . "." .$extension;
                                if (move_uploaded_file($file_tmp, "Uploads/Files/".$final_file_name)) {
                                        $file_query = "";
                                        if (mysqli_num_rows($result_check) > 0) {
                                                $file_query = "UPDATE Candidate_Positions_Files SET File_Name='$final_file_name' WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id' AND File_Id='$f_id';";
                                        } else {
                                                $file_query = "INSERT INTO Candidate_Positions_Files (Candidate_Id , Position_Id , File_Id , File_Name) VALUES ('$c_id' , '$pos_id' , '$f_id' , '$final_file_name');";
                                        }
                                        mysqli_query($connection,$file_query);
                                }
                        }
                }
                $i = $i + 1;
        }
}

$action_file = 'form_submit_position.php';

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
	<div id="references_form_div">
		<form class='reg_form' action=<?php echo "$action_file"; ?> method='post' enctype='multipart/form-data' id='references_form'>

		<?php //skill matrix table 
		if ((count($suggested_skills_id_send_array) > 0) && (count($suggested_skills_name_send_array) > 0) && (count($suggested_skills_name_send_array) == count($suggested_skills_id_send_array))){
			echo "<h2 class='apply_h1'> Consultant Relevant Skills Matrix </h2>";
			echo "<table class='skill_table' style='width:100%'>";
			echo "<tr class='skill_table'><th class='skill_table'>Relevant Skill</th><th class='skill_table'>Years of Experience</th></tr>";
			for ($itr = 0; $itr < count($suggested_skills_id_send_array); $itr++) {
				$skill_id = $suggested_skills_id_send_array[$itr];
				$skill_name = $suggested_skills_name_send_array[$itr];
				$get_exp_query = "SELECT Exp FROM Candidate_Skills WHERE Candidate_Id='$c_id' AND Skill_Id='$skill_id' ;";
				echo "<tr class='skill_table'><td class='skill_table'>$skill_name</td><td class='skill_table'>";
				if (($result_exp = mysqli_query($connection,$get_exp_query)) && (mysqli_num_rows($result_exp) > 0)) {
                                        //exp exists
					$row_exp = mysqli_fetch_assoc($result_exp);
					$skill_exp = $row_exp['Exp'];
					echo $skill_exp;
				} else { echo "0"; }
				echo "</td></tr>";
			}
			echo "</table><br>";
		}
		?>

		<h2 class="apply_h1"> Consultant References </h2>
		<input type="hidden" name="Position_Id" value="<?php echo $pos_id; ?>">
		<input type="hidden" name="Candidate_Id" value="<?php echo $c_id; ?>">
		<input type="hidden" name="No_of_References" value="<?php echo $no_of_references; ?>">
		<div class="forms_container">
		<?php for ($i = 1; $i <= $no_of_references ; $i++) { ?>
			<div class="each_form_class">
			<h4 class="apply_h1"> Reference <?php echo $i; ?> </h4>
			<div class="form-row">
				<div class="form-group col-md-11">
					<label for="Company_Name_<?php echo $i; ?>" class="form-label">Company Name* : </label>
					<input type="text" class="form-control" id="Company_Name_<?php echo $i; ?>" placeholder="Company Name" required name="Company_Name_<?php echo $i; ?>">
				</div>
			</div>
			<div class="form-row">           
				<div class="form-group col-md-11">                    
					<label for="Contact_Name_<?php echo $i; ?>" class="form-label">Contact Name* : </label>
					<input type="text" class="form-control" id="Contact_Name_<?php echo $i; ?>" placeholder="Contact Name" required name="Contact_Name_<?php echo $i; ?>">
				</div>
			</div>
			<div class="form-row">           
				<div class="form-group col-md-11">                    
					<label for="Contact_Title_<?php echo $i; ?>" class="form-label">Contact Title* : </label>
					<input type="text" class="form-control" id="Contact_Title_<?php echo $i; ?>" placeholder="Contact Title" required name="Contact_Title_<?php echo $i; ?>">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-11">
					<label for="Contact_Telephone_<?php echo $i; ?>" class="form-label">Contact Number* : </label>
					<input type="tel" id="Contact_Telephone_<?php echo $i; ?>" class="form-control" placeholder="Contact Number" required name="Contact_Telephone_<?php echo $i; ?>">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-11">
					<label for="Contact_Email_<?php echo $i; ?>" class="form-label">Contact Email* : </label>
					<input type="email" class="form-control" id="Contact_Email_<?php echo $i; ?>" placeholder="Contact Email" name="Contact_Email_<?php echo $i; ?>" required>
				</div>
			</div>
			<div class="form-row">           
                                <div class="form-group col-md-11">              
                                        <label for="Begin_Date_<?php echo $i; ?>" class="form-label">Begin Date* : </label>
                                        <input type="date" class="form-control" id="Begin_Date_<?php echo $i; ?>" placeholder="Begin Date" name="Begin_Date_<?php echo $i; ?>" required>
                                </div>
			</div>
			<div class="form-row">
                                <div class="form-group col-md-11">
                                        <label for="End_Date_<?php echo $i; ?>" class="form-label">End Date* : </label>
                                        <input type="date" class="form-control" id="End_Date_<?php echo $i; ?>" placeholder="End Date" name="End_Date_<?php echo $i; ?>" required>
                                </div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-11">
					<label for="Description_<?php echo $i; ?>" class="form-label">Description of work performed* : </label>
					<textarea class="form-control" rows="5" cols="40" name="Description_<?php echo $i; ?>" id="Description_<?php echo $i; ?>" form="references_form" placeholder="Description of work performed relevant to this position." required style="margin-bottom: 0;"></textarea><p>Atleast 70 characters</p>
				</div>
			</div>
			</div>
		<?php } ?>
		</div>
		<input type="submit" id="submit" name="addreferences" class="btn btn-primary" onclick="return validation(this.form);"></input>
		</form>
	</div>

<script>

function validation(form) {
	for ( var i = 0; i < form.elements.length; i++ ) {
		var e = form.elements[i];
		var e_type = e.type;
		if (e_type == 'tel') {
			var phoneno = /^[+]{0,1}(?:[0-9\-\(\)]\s?){6,15}[0-9]{1}$/;
			if(!(e.value.match(phoneno))){
				alert("Not a valid Contact Number");
				e.scrollIntoView({ block: 'center',  behavior: 'smooth' });
				e.focus();
				return false;
			}
		}
		else if (e_type == 'textarea') {
			var text_length = e.value.length;
			if (text_length < 70) {
				alert("Description too small");
				e.scrollIntoView({ block: 'center',  behavior: 'smooth' });
				e.focus();
				return false;
			}
		}
	}
	return true;
}

</script>

</body>

<?php } ?>
