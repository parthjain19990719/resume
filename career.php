<!DOCTYPE html>
<html lang="en">
<head>
    	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<title>Open Positions with Advanced Image Inc</title>
    	<meta name="description" content="All Open Job Positions with Advanced Image Inc. Candidates can apply and also provide their Employers information if looking only for Corp to Corp. Even Recruiters can also apply in behalf of their employees."/>
    	<meta name="robots" content="index, follow, noarchive">
    	<meta name="keywords" content="Jobs, Openings, C#, .NET, ECM, Alfresco, Java, C++, Apply, C2C">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="Positions.css">
	<link href="stylesheet.css" rel="stylesheet" type="text/css" />
    	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<?php
$noHead=1;
$fileName="career.php";
include_once("template.php");
function displayPage()
{
?>

<div class="pos_form">
	<ul id="list_position" class="ul_pos"></ul>
</div>

<script>

	// Fetch job positions from the server
	fetch('get_positions.php')
		.then(response => response.json())
		.then(data => {
			const uList = document.getElementById('list_position');
			for (var key in data){
				position= JSON.parse(data[key]);
				Random_Id = position.Position_Random_Id;
				const li = document.createElement('li');
				li.setAttribute('class','li_pos');
				li.setAttribute('onclick',"enable_div(this," + Random_Id +")");

				const div_heading =  document.createElement('div');
				const heading = document.createElement('h2');
				heading.innerHTML = position.Title;
				div_heading.appendChild(heading);
				const div_bottom_right =  document.createElement('div');
				if (position.Postdate){
				    div_bottom_right.setAttribute('style','position: relative;height: 20px;');
				    const posted_date_p = document.createElement('p');
				    if (position.Client_Name) {
					    posted_date_p.innerHTML = '<b>Client: </b>' + position.Client_Name + "&nbsp<b>Posted On: </b>" + position.Postdate ;
				    } else {
				        posted_date_p.innerHTML = "<b>Posted On: </b>" + position.Postdate ;
				    }
					posted_date_p.setAttribute('style','position: absolute;bottom:0;right:0;');
                    div_bottom_right.appendChild(posted_date_p);
                }
				li.appendChild(div_heading);
				li.appendChild(div_bottom_right);

				const div = document.createElement('div');
				div.setAttribute('id',Random_Id);
				div.setAttribute('class','disable_div');
				li.appendChild(div);

				const p1 = document.createElement('p');
				p1.innerHTML = '<b>Worklocation: </b>' + position.City + ' , ' + position.State;
				div.appendChild(p1);

				const p2 = document.createElement('p');
                                p2.innerHTML = '<b>Remote options: </b>' + position.Position_Type;
				div.appendChild(p2);

				const p3 = document.createElement('p');
                                p3.innerHTML = '<b>Min. Experience: </b>' + position.Exp + ' years';
				div.appendChild(p3);

				if (position.Client_Name){
					const p4 = document.createElement('p');
                                	p4.innerHTML = '<b>Client: </b>' + position.Client_Name;
					div.appendChild(p4);
					const p5 = document.createElement('p');
					if ((position.Direct_Vendor) && (position.Direct_Vendor == '1')){ 
						p5.innerHTML = '<b>We are the direct vendor to ' + position.Client_Name + '</b>'; 
					} 
					div.appendChild(p5);
				}

				if ((position.All_skills_temp) && (position.All_skills_temp.length > 0)) {  //adding skills
					let temp_string = "";
					for (let i = 0; i < position.All_skills_temp.length; i++) { 
						temp_string = temp_string + position.All_skills_temp[i][1]  + " Years of experience in " + position.All_skills_temp[i][0] + ' , '; 
					}
					temp_string = temp_string.slice(0,-2);
					const p7 = document.createElement('p');
					p7.innerHTML = "<b>Skills Required: </b>" + temp_string;
					div.appendChild(p7);
				}

				const p6 = document.createElement('p');
                                p6.innerHTML = '<b>Description: </b><br>' + position.Description;
				div.appendChild(p6);

				const button = document.createElement('button');
				button.setAttribute('class','btn btn-primary');
				button.setAttribute('onclick',"go_to_form_page(" + Random_Id + ")");
				button.innerHTML = "Apply";
				div.appendChild(button);

				uList.appendChild(li);
			
			};
	    
		})
		.catch(error => console.error('Error:', error));

	function enable_div(li,rand_id){
		li.className = "li_pos_hover_disabled";
		document.getElementById(rand_id).style.display = 'block';
	}

	function go_to_form_page(rand_id) {
		url = "job_application.php?Position_Id=" + rand_id;
		window.location.href = url;
	}

</script>
<?php } ?>
