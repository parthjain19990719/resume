<?php
  
if ((isset($_POST['Candidate_Id'])) && (isset($_POST['Position_Id'])) && (isset($_POST['No_of_References']))) {
	global $c_id;
	global $pos_id;
	$c_id = mysqli_real_escape_string($connection,$_POST['Candidate_Id']);
	$pos_id = mysqli_real_escape_string($connection,$_POST['Position_Id']);
	$No_of_References = mysqli_real_escape_string($connection,$_POST['No_of_References']);
	$Company_Name = "";
	$Contact_Name = "";
	$Contact_Title = "";
	$Contact_Telephone = "";
	$Contact_Email = "";
	$Begin_Date = "";
	$End_date = "";
	$Description = "";
	$already_exists_query = "SELECT CompanyName , Reference_Id FROM Candidate_References WHERE Candidate_Id='$c_id' AND Position_Id='$pos_id';";
	if(($result_exists = mysqli_query($connection, $already_exists_query)) && (mysqli_num_rows($result_exists) > 0)) {
		// alredy exists
		$exists_No_of_References = mysqli_num_rows($result_exists);
		if ($No_of_References <= $exists_No_of_References) {
			$i = 1;
			while ($row_exists = mysqli_fetch_assoc($result_exists)) {
				$r_id = $row_exists['Reference_Id'];
				$Company_Name = $_POST['Company_Name_' . $i];
				$Contact_Name = $_POST['Contact_Name_' . $i];
				$Contact_Title = $_POST['Contact_Title_' . $i];
				$Contact_Telephone = $_POST['Contact_Telephone_' . $i];
				$Contact_Email = $_POST['Contact_Email_' . $i];
				$Begin_Date = $_POST['Begin_Date_' . $i];
				$End_date = $_POST['End_Date_' . $i];
				$Description = $_POST['Description_' . $i];
				$update_query = "UPDATE Candidate_References SET CompanyName='$Company_Name' , ContactName='$Contact_Name' , ContactTitle='$Contact_Title' , ContactPhone='$Contact_Telephone' , ContactEmail='$Contact_Email' , BeginDate='$Begin_Date' , EndDate='$End_date' , Description='$Description' WHERE Reference_Id='$r_id'";
				mysqli_query($connection, $update_query);
				$i = $i + 1;
				if ($i > $No_of_References) { break; }
			}
		} else {
			$i = 1;
			while ($row_exists = mysqli_fetch_assoc($result_exists)) {
				$r_id = $row_exists['Reference_Id'];
				$Company_Name = $_POST['Company_Name_' . $i];
				$Contact_Name = $_POST['Contact_Name_' . $i];
				$Contact_Title = $_POST['Contact_Title_' . $i];
				$Contact_Telephone = $_POST['Contact_Telephone_' . $i];
				$Contact_Email = $_POST['Contact_Email_' . $i];
				$Begin_Date = $_POST['Begin_Date_' . $i];
				$End_date = $_POST['End_Date_' . $i];
				$Description = $_POST['Description_' . $i];
				$update_query = "UPDATE Candidate_References SET CompanyName='$Company_Name' , ContactName='$Contact_Name' , ContactTitle='$Contact_Title' , ContactPhone='$Contact_Telephone' , ContactEmail='$Contact_Email' , BeginDate='$Begin_Date' , EndDate='$End_date' , Description='$Description' WHERE Reference_Id='$r_id'";
				mysqli_query($connection, $update_query);
				$i = $i + 1;
			}
			while ($i <= $No_of_References) {
				$Company_Name = $_POST['Company_Name_' . $i];
				$Contact_Name = $_POST['Contact_Name_' . $i];  
				$Contact_Title = $_POST['Contact_Title_' . $i];        
				$Contact_Telephone = $_POST['Contact_Telephone_' . $i];
				$Contact_Email = $_POST['Contact_Email_' . $i];
				$Begin_Date = $_POST['Begin_Date_' . $i];
				$End_date = $_POST['End_Date_' . $i];      
				$Description = $_POST['Description_' . $i];
				$insert_query = "INSERT INTO Candidate_References (Candidate_Id , Position_Id , CompanyName , ContactName , ContactTitle , ContactPhone , ContactEmail , BeginDate , EndDate , Description) VALUES ('$c_id' , '$pos_id' , '$Company_Name' , '$Contact_Name' , '$Contact_Title' , '$Contact_Telephone' , '$Contact_Email' , '$Begin_Date' , '$End_date' , '$Description');";
				mysqli_query($connection, $insert_query);
				$i = $i + 1;
			}
		}
	} else {
		for ($i = 1; $i <= $No_of_References ; $i++) {
			// creating pipe seperated columns
			$Company_Name = $_POST['Company_Name_' . $i];
			$Contact_Name = $_POST['Contact_Name_' . $i];
			$Contact_Title = $_POST['Contact_Title_' . $i];
			$Contact_Telephone = $_POST['Contact_Telephone_' . $i];
			$Contact_Email = $_POST['Contact_Email_' . $i];
			$Begin_Date = $_POST['Begin_Date_' . $i];
			$End_date = $_POST['End_Date_' . $i];
			$Description = $_POST['Description_' . $i];
			$insert_query = "INSERT INTO Candidate_References (Candidate_Id , Position_Id , CompanyName , ContactName , ContactTitle , ContactPhone , ContactEmail , BeginDate , EndDate , Description) VALUES ('$c_id' , '$pos_id' , '$Company_Name' , '$Contact_Name' , '$Contact_Title' , '$Contact_Telephone' , '$Contact_Email' , '$Begin_Date' , '$End_date' , '$Description');";
			mysqli_query($connection, $insert_query);
		}
	}
	create_reference_pdf($c_id,$pos_id);
}
