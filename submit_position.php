<!DOCTYPE html>
<?php
$title="Insert Position";
$fileName="submit_postion.php";
require "config_resume.php";
require "Resume_lib.php";
include_once("template.php");
function displayPage()
{
?>
<html lang="en">
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
</head>
<body>
        <div class="thank_you_form">
		<div class="thank-you-container">
			<p class="appreciate">

<?php
require "config_resume.php";

//check and create position random id
$check_rand_id = false;
$Position_Random_Id = 0;
do {
	$Position_Random_Id = random_int(100000, 999999);
	$check_query = "SELECT Position_Id FROM Positions WHERE Position_Random_Id=$Position_Random_Id;";
	if ($result_check = mysqli_query($connection,$check_query)) {
		if (mysqli_num_rows($result_check) > 0) { //this number exists looping again
			$check_rand_id = true;
		} else {
			$check_rand_id = false;
		}
	}

} while ($check_rand_id);

//inserting values
if(isset($_POST['addposition'])) {
	$Title = mysqli_real_escape_string($connection,$_POST['Title']);
	$Description = mysqli_real_escape_string($connection,$_POST['Description']);
	$State = mysqli_real_escape_string($connection,$_POST['State']);
	$City = mysqli_real_escape_string($connection,$_POST['City']);
	$Position_Type = mysqli_real_escape_string($connection,$_POST['Position_Type']);
	$Exp = mysqli_real_escape_string($connection,$_POST['Exp']);
	$Status = mysqli_real_escape_string($connection,$_POST['Status']);
	$Client_Name = mysqli_real_escape_string($connection,$_POST['Client_Name']);
	$Direct_Vendor = mysqli_real_escape_string($connection,$_POST['Direct_Vendor']);
	$Banner = mysqli_real_escape_string($connection,$_POST['Banner']);
	$References_Required = mysqli_real_escape_string($connection,$_POST['References_Required']);
	$Postdate = mysqli_real_escape_string($connection,$_POST['Postdate']);
	$InformTo_Email = mysqli_real_escape_string($connection,$_POST['InformTo_Email']);
	$Auto_Email_Text = mysqli_real_escape_string($connection,$_POST['Auto_Email_Text']);
	$Auto_Email_To_Candidate = mysqli_real_escape_string($connection,$_POST['Auto_Email_To_Candidate']);
	$Client_Id = mysqli_real_escape_string($connection,$_POST['Client_Id']);
	$tags = json_decode($_POST['tags_array']);
        $keywords = json_decode($_POST['tags_keywords_array']);
	$exp = json_decode($_POST['tags_exp_array']);
	$mand = json_decode($_POST['tags_mand_array']);

	$insert_query = "INSERT INTO Positions ( Position_Random_Id, Title, Description, State, City, Position_Type, Exp, Status, Client_Name, Direct_Vendor, Banner, References_Required, Postdate, InformTo_Email, Auto_Email_Text, Auto_Email_To_Candidate, Client_Id) VALUES ('$Position_Random_Id','$Title','$Description','$State','$City','$Position_Type','$Exp','$Status','$Client_Name','$Direct_Vendor','$Banner','$References_Required','$Postdate','$InformTo_Email','$Auto_Email_Text','$Auto_Email_To_Candidate','$Client_Id');";
	if (mysqli_query($connection,$insert_query)) { //insert position
		$p_id = mysqli_insert_id($connection); //get position id to insert skills
		if (count($tags)==count($keywords)) {
			for ($x = 0 ; $x < count($tags); $x++) {
				$query_tag = "INSERT INTO Position_Skills (Position_Id , Skill_Id , Exp, Mandatory) VALUES ('$p_id','" .$tags[$x]. "','". $exp[$x] ."','" .$mand[$x]. "');";
				mysqli_query($connection,$query_tag);
			}
			echo "Insertion Successful";
		}
		else {
			echo "Skill Insertion Unsuccessful";
		}
	}
	else {
		echo "Insertion Unsuccessful";
	}

}
} ?>
