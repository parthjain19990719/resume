<?php 

require "config_resume.php";

function send_email($Position_Id,$Candidate_Id) {
	global $connection;
	$Position_Id = mysqli_real_escape_string($connection,$Position_Id);
 	$Candidate_Id = mysqli_real_escape_string($connection,$Candidate_Id);
    
    	$pos_query = "SELECT Title , Position_Random_Id , Auto_Email_Text , Client_Name FROM Positions WHERE Position_Id=$Position_Id;";
    	if (($result_pos = mysqli_query($connection,$pos_query)) && (mysqli_num_rows($result_pos) > 0)) {
        	$row_pos = mysqli_fetch_assoc($result_pos);
        	$title = $row_pos['Title'];
        	$rand_id = $row_pos['Position_Random_Id'];
		$email_text = $row_pos['Auto_Email_Text'];
		$client = $row_pos['Client_Name'];
        
        	if (strlen($email_text) <= 0) { 
            		return; //email_text is empty do not send mail
        	}
        
        	$candidate_query = "SELECT Name , Email FROM Candidates WHERE Candidate_Id=$Candidate_Id;";
        	if (($result_can = mysqli_query($connection,$candidate_query)) && (mysqli_num_rows($result_can) > 0)) {
            		$row_can = mysqli_fetch_assoc($result_can);
            		$to = $row_can['Email'];
            		$name = $row_can['Name'];
	    		$subject = "Re: " . $title;
	       		$from_email_id = "";
	        	$list_of_attachemnt_files = array();
	        
            		$email_text = str_replace('[<Name>]' ,$name, $email_text);
            		$email_text = str_replace('[<Title>]' ,$title, $email_text);
	        	$email_text = str_replace('[<Position_Random_Id>]' ,$rand_id, $email_text);
	        	$email_text = str_replace('[<to>]' ,$to, $email_text);
	        	if (strlen($client) > 0) {
	    	    		$email_text = str_replace('[<ClientName>]' , $client, $email_text);
	        	} else {
	    	    		$email_text = str_replace('[<ClientName>]' , "" , $email_text);
	        	}
            
            		//getting from email id
            		$from_pos = strpos($email_text,'[<From:');
            		if ($from_pos !== false) {
                		$from_end_pos = strpos($email_text,'>]',$from_pos);
                		if ($from_end_pos) {
                    			$from_email_id = substr($email_text, $from_pos+7, $from_end_pos-($from_pos+7));
                    			$email_text = substr_replace($email_text,"",$from_pos,($from_end_pos - $from_pos) + 2);
                		}
            		}
            
            		//geting attachments from Auto email text
            		$attachment_start_pos = strpos($email_text,'[<Files:');
            		if ($attachment_start_pos !== false) {
                		$attachment_end_pos = strpos($email_text,'>]',$attachment_start_pos);
                		if ($attachment_end_pos) {
                    			$attachment_text = substr($email_text, $attachment_start_pos+8, $attachment_end_pos-($attachment_start_pos+8));
                    			$email_text = substr_replace($email_text,"",$attachment_start_pos,($attachment_end_pos - $attachment_start_pos) + 2);
                    			$length_text = strlen($attachment_text);
                    			$filename_pos = strpos($attachment_text,',');
                    			if ((!($filename_pos)) && ($length_text > 0)) {
                            			array_push($list_of_attachemnt_files,$attachment_text);
                    			}
                    			while($filename_pos) {
                        			$filename = substr($attachment_text,0,$filename_pos);
			                        array_push($list_of_attachemnt_files,$filename);
			                        $attachment_text = substr($attachment_text,$filename_pos+1);
			                        $filename_pos = strpos($attachment_text,',');
			                        if ((!($filename_pos)) && (strlen($attachment_text) > 0)) {
			                            array_push($list_of_attachemnt_files,$attachment_text);
                        			}
		                	}
                		}
			}

			$email_text .= "\n\n";

			//getting attachments from Postion_Files and adding instructions
			$not_added_note = True;
			$pos_file_query = "SELECT File_Name , Instructions FROM Position_Files WHERE Position_Id=$Position_Id;";
			if (($result_pos_file = mysqli_query($connection,$pos_file_query)) && (mysqli_num_rows($result_pos_file) > 0)) {
				while ($row_pos_file = mysqli_fetch_array($result_pos_file)) {
					$File_Name = $row_pos_file['File_Name'];
					$File_Name_Path = "Uploads/docs/" . $File_Name;
					$Instructions = $row_pos_file['Instructions'];
					if (strlen($File_Name) > 0) {
						array_push($list_of_attachemnt_files,$File_Name_Path);
						if (strlen($Instructions) > 0) {
							if ($not_added_note) {
								$email_text .= "Note: \n";
								$not_added_note = False;
							}
                                                	$email_text .= $Instructions . "This is for attached file: " . $File_Name . "\n";
                                        	}
					}
				}
			}
            
            		$boundary = md5("random"); // define boundary with a md5 hashed value
 
	            	//header
	            	$headers = "MIME-Version: 1.0\r\n"; // Defining the MIME version
            		if (strlen($from_email_id) > 0) {
                		$headers .= "From:".$from_email_id."\r\n"; // Sender Email
            		}
            		$headers .= "Content-Type: multipart/mixed;"; // Defining Content-Type
            		$headers .= "boundary = $boundary\r\n"; //Defining the Boundary
         
            		//plain text
            		$body = "--$boundary\r\n";
            		$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
            		$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            		$body .= chunk_split(base64_encode($email_text));
         
            		//attachment
            		if (count($list_of_attachemnt_files) > 0) {
                		foreach ($list_of_attachemnt_files as $attachment) {
                    			if (file_exists($attachment)) {
                        			$file = fopen($attachment, "rb");
                        			$data = fread($file, filesize($attachment));
                        			fclose($file);
                        			$encoded_content = chunk_split(base64_encode($data));
                        			$body .= "--$boundary\r\n";
                       				$body .="Content-Type: application/pdf/png/docx; name=". basename($attachment) ."\r\n";
                        			$body .="Content-Disposition: attachment; filename=". basename($attachment) ."\r\n";
                        			$body .="Content-Transfer-Encoding: base64\r\n";
                        			$body .="X-Attachment-Id: ".rand(1000, 99999)."\r\n\r\n";
                        			$body .= $encoded_content; // Attaching the encoded file with email
		    			} else {
			    			error_log("Couldn't open attachment file: ".$attachment);
		    			}
                		}
			}

	        	// Send email
	        	mail($to, $subject, $body, $headers);
        	}
    	}
}

function create_reference_pdf($c_id,$p_id) {
	require 'fpdf/fpdf.php';
	global $connection;
	$p_id = mysqli_real_escape_string($connection,$p_id);
    	$c_id = mysqli_real_escape_string($connection,$c_id);
	$no_of_references = 0;
	$name = "";
	$skill_matrix = array();
	
	//getting name of candidate
	$name_query = "SELECT Name FROM Candidates WHERE Candidate_Id='$c_id';";
	if (($result_name = mysqli_query($connection,$name_query)) && (mysqli_num_rows($result_name) > 0)) {
		$row_name = mysqli_fetch_assoc($result_name);
                if ($row_name['Name'] < 1) { return; }
		$name = $row_name['Name'];
	} else { return; }

	//getting skills matrix
	$pos_skill_query = "SELECT Skill_Id FROM Position_Skills WHERE Position_Id='$p_id';";
	if (($result_pos_skill = mysqli_query($connection,$pos_skill_query)) && (mysqli_num_rows($result_pos_skill) > 0)) {
		while ($row_pos_skill = mysqli_fetch_array($result_pos_skill)) {
			$skill_id = $row_pos_skill['Skill_Id'];
			$temp_array = array();

			// getting skill name
			$skill_name_query = "SELECT Skill FROM Skills WHERE Skill_Id='$skill_id';";
			if (($result_skill_name = mysqli_query($connection,$skill_name_query)) && (mysqli_num_rows($result_skill_name) > 0)) {
				$row_skill_name = mysqli_fetch_assoc($result_skill_name);
				$skill_name = $row_skill_name['Skill'];
				array_push($temp_array,$skill_name);
			} else { return; }

			// getting skill exp
			$skill_Exp_query = "SELECT Exp FROM Candidate_Skills WHERE Candidate_Id='$c_id' AND Skill_Id='$skill_id' ; ";
			if (($result_skill_exp = mysqli_query($connection,$skill_Exp_query)) && (mysqli_num_rows($result_skill_exp) > 0)) {
				$row_skill_exp = mysqli_fetch_assoc($result_skill_exp);
				$skill_exp = $row_skill_exp['Exp'];
				array_push($temp_array,$skill_exp);
			} else { array_push($temp_array,0); }
			array_push($skill_matrix,$temp_array);
		}
	}

	$check_query = "SELECT CompanyName , ContactName , ContactTitle , ContactPhone , ContactEmail , BeginDate , EndDate , Description FROM Candidate_References WHERE Candidate_Id='$c_id' AND Position_Id='$p_id' ;";
	if (($result_check = mysqli_query($connection,$check_query)) && (mysqli_num_rows($result_check) > 0)) {
		$pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(true);
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0,10,$name,0,1,'C');
                $pdf->SetFont('Arial','B',14);
                $pdf->Cell(0,15,'Consultant Relevant Skills Matrix',0,1,'C');
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(94,7,'Relevant Skill',1);
                $pdf->Cell(94,7,'Years of Experience',1);
                $pdf->Ln();
                $pdf->SetFont('Arial','',12);
                foreach($skill_matrix as $row) {
                        foreach($row as $col) {
                                $pdf->Cell(94,6,$col,1);
                        }
                        $pdf->Ln();
                }
                $pdf->Cell(0,5,'',0,1,'C');
                $pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,10,'Consultant References',0,1,'C');
		$count = 1;
		while ($row = mysqli_fetch_assoc($result_check)) {
			$CompanyName = $row['CompanyName'];
			$ContactName = $row['ContactName'];
			$ContactTitle = $row['ContactTitle'];
			$ContactPhone = $row['ContactPhone'];
			$ContactEmail = $row['ContactEmail'];
			$BeginDate = $row['BeginDate'];
			$EndDate = $row['EndDate'];
			$Description = $row['Description'];
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(0,10,"Reference $count",0,1);
			$pdf->SetFont('Arial','',12);
			$Company_text = "Company Name: " . $CompanyName . "\n";
			$pdf->Write(5,$Company_text);
			$name_text = "Contact Name: " . $ContactName . "\n";
			$pdf->Write(5,$name_text);
			$title_text = "Contact Title: " . $ContactTitle . "\n";
			$pdf->Write(5,$title_text);
			$phone_text = "Contact Phone: " . $ContactPhone . "\n";
			$pdf->Write(5,$phone_text);
			$email_text = "Contact Email: " . $ContactEmail . "\n";
			$pdf->Write(5,$email_text);
			$begin_text = "Begin Date: " . $BeginDate . "\n";
			$pdf->Write(5,$begin_text);
			$end_text = "End Date: " . $EndDate . "\n";
			$pdf->Write(5,$end_text);
			$des_text = "Description of Work Performed: " . $Description . "\n";
			$pdf->Write(5,$des_text);
			$pdf->Cell(0,5,'',0,1,'C');
			$count = $count + 1;
		}
		$filename = $name . "_References_" . $c_id . "_" . $p_id . ".pdf";
		$pdf->Output('F', "Uploads/References/$filename");
	}
}

?>
