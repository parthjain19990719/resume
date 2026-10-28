<?php require 'config_resume.php'; ?>
	<form class="reg_form" action="form_submit.php" method="post" enctype="multipart/form-data" id="registration_form">
		<h1 style="margin-bottom: 40px;">Personal Information</h1>
		<div class="form-row">
                        <div class="form-group col-md-6">
                                <label for="email" class="form-label">Email* : </label>
                                <input type="email" class="form-control" id="email" placeholder="Email" name="email" onchange="autofill_form(this.value)" required>
                        </div>
                </div>
		<div class="form-row">
                  	<div class="form-group col-md-6">
                    		<label for="Name" class="form-label">Name* : </label>
                    		<input type="text" class="form-control" id="Name" placeholder="Name" required name="Name">
                  	</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
                  		<label for="telephone" class="form-label">Contact Number* : </label>
                  		<input type="tel" id="mobile_number" class="form-control" placeholder="Contact Number" required name="telephone">
                	</div>
		</div>
		<div class="form-row">
                        <div class="form-group col-md-6">
                                <label for="zipcode" class="form-label">ZIP Code* : </label>
                                <input type="tel" id="zipcode" class="form-control" placeholder="ZIP Code" required name="zipcode">
                        </div>
                </div>
		<div class="form-row">
                        <div class="form-group col-md-6">
                                <label for="work_status" class="form-label">Work Status* : </label><br>
                                <input type="radio" id="USCitizen" name="work_status" value="US Citizen" onclick="show_visa_transfer_div(0)" checked>
                                <label for="USCitizen"> U.S.Citizen &nbsp;&nbsp;</label>
                                <input type="radio" id="Green Card" name="work_status" value="Green Card" onclick="show_visa_transfer_div(0)">
				<label for="Green Card"> Green Card &nbsp;&nbsp;</label>
				<input type="radio" id="EAD" name="work_status" value="EAD" onclick="show_visa_transfer_div(0)">
                                <label for="EAD"> EAD &nbsp;&nbsp;</label>
                                <input type="radio" id="Visa" name="work_status" value="Visa" onclick="show_visa_transfer_div(1)">
                                <label for="Hybrid"> Visa </label>
                        </div>
		</div><br>
		<div class="form-row" id="visa_transfer_div" style="display: none">
                        <div class="form-group col-md-6">
                                <label for="visa_transfer" class="form-label">Do you wish to transfer your visa?* : </label><br>
                                <input type="radio" id="no_visa" name="visa_transfer" value="0" checked>
                                <label for="no_visa"> No &nbsp;&nbsp;</label>
                                <input type="radio" id="yes_visa" name="visa_transfer" value="1">
                                <label for="yes_visa"> Yes </label>
                        </div><br>
		</div>
		<div class="form-row" id="Relocate_div">
                        <div class="form-group col-md-6">
                                <label for="Relocate" class="form-label">Are you willing to relocate?* : </label><br>
                                <input type="radio" id="No_Relocate" name="Relocate" value="0" checked>
                                <label for="No_Relocate"> No &nbsp;&nbsp;</label>
                                <input type="radio" id="Yes_Relocate" name="Relocate" value="1">
                                <label for="Yes_Relocate"> Yes </label>
                        </div><br>
                </div>
		<div class="form-row" id="salary_type_div">
			<div class="form-group col-md-6">
				<label for="salary_type" class="form-label">Salary Type* : </label><br>
				<input type="radio" id="hourly" name="salary_type" value="Hourly" onclick="change_salary_unit('Hourly')" checked>
				<label for="hourly"> Hourly &nbsp;&nbsp;</label>
				<input type="radio" id="Annually" name="salary_type" value="Annually" onclick="change_salary_unit('Annually')">
				<label for="Annually"> Annually </label>
			</div><br>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="salary" id="salary_label" class="form-label">Expected Hourly Rate* : </label>
				<input type="tel" id="salary" class="form-control" placeholder="Expected Hourly Rate" required name="salary">
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="Linkedin_URL" id="Linkedin_URL_label" class="form-label">Linkedin URL : </label>
				<input type="text" id="Linkedin_URL" class="form-control" placeholder="Your Linkedin Profile URL" name="Linkedin_URL">
			</div>
		</div>
		<div class="form-row">
                        <div class="form-group col-md-6">
                                <label for="position_type" class="form-label">Position Type* : </label><br>
                                <input type="radio" id="Remote" name="position_type" value="Remote" checked>
                                <label for="Remote"> Remote &nbsp;&nbsp;</label>
                                <input type="radio" id="OnSite" name="position_type" value="OnSite">
				<label for="OnSite"> Onsite &nbsp;&nbsp;</label>
				<input type="radio" id="Hybrid" name="position_type" value="Hybrid">
                                <label for="Hybrid"> Hybrid </label>
                        </div>
		</div>
		<br>
		<div class="form-group col-md-6">
			<label for="tag" class="form-label">Add Skill*: </label>
			<div class="search_tag">
                		<div class="search_tag_header"><input type="text" id="language_text" onkeyup="addtag()" placeholder="Add Skill.."></div>
				<div class="search_tag_body"<ul id="tag_ul">
				<?php
				$query = "SELECT * FROM Skills WHERE Verified=1; ";
                                $query_run = mysqli_query($connection,$query);
                                if (mysqli_num_rows($query_run) > 0) {
                                        while ($row = mysqli_fetch_array($query_run)) {
                                                echo '<li><button type="button" class="tag_button" onclick="add_tag('. $row['Skill_Id'] .',\'' . $row['Skill'] . '\')">'. $row['Skill'] .'</button></li>';
                                        }
                                        echo '<li id="other_li"><button type="button" class="tag_button" onclick="add_tag(\'-1\',\'other\')"> New Skill </button></li>';
                                }
				?>
				</div>
				<input type="hidden" name="tags_array" id="tags_array_id" >
				<input type="hidden" name="tags_keywords_array" id="tags_keywords_array_id" >
				<input type="hidden" name="tags_exp_array" id="tags_exp_array_id" >
			</div>
		</div>
		<hr>
		<!-- popup for yoe -->
               	<div id="myModal" class="popup">
               		<div class="popup-content">
				<span class="close">&times;</span>
				<label for="other_name" class="form-label" id="other_name_label" style="display:none;"> Skill Name: </label>
				<input type="text" class="form-control" style="width:50%;display:none;" id="other_name" name="other_name" placeholder="Name of Skill">
				<label for="yoe" class="form-label" id="yoe_label"></label>
				<input type="number" class="form-control" style="width:50%;" placeholder="Experience in years" id="yoe_input" name="exp" min="0" max="50">
				<button type="button" class="btn btn-primary" onclick="add_exp()"> Add </button>
                  	</div>
          	</div>
		<label for="list_technology" class="form-label" id="list_technology_label" style="display: block;margin-top: 20px;margin-bottom: unset;">Your Skills: </label>
		<table id="list_technology">
		</table><br>
		<div class="form-row">
                       	<div class="form-group col-md-6">
                        	<label for="file" class="form-label">Resume* : </label>
                             	<input type="file" class="form-control"  placeholder="Choose Document" name="file" required>
                    	</div>
		</div>
		<div class="form-row">
                        <div class="form-group col-md-6">
                                <input type="checkbox" name="terms" id="terms" onchange="activateButton(this)"> I do hereby certify, under the penalty of perjury, that information in my resume submitted for consideration is true, correct, complete, and made in good faith to the best of my knowledge and belief.
                        </div>
                </div>
		<input type="submit" id="submit" name="addresume" class="btn btn-primary" onclick="return validation();" disabled ></input>
  	</form>
<script>

var tags_array = new Array();
var tags_keywords_array = new Array();
var tags_exp = new Array();
var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];
var curr_tag_id = "";
var curr_tag_keyword = "";

function activateButton(element) {
      if(element.checked) {
        document.getElementById("submit").disabled = false;
       }
       else  {
        document.getElementById("submit").disabled = true;
      }
}

function autofill_form(email_value) {
	email = { "Email": email_value }
	let options = {
    		method: 'POST',
		headers: {'Content-Type': 'application/json;charset=utf-8' },
	    	body: JSON.stringify(email)
	}
	let fetchRes = fetch("auto_fill.php",options);
	fetchRes.then(response => response.json()).then(data => {
	if (data['Already_present']) {
		// clearing arrays
		tags_array = [];
		tags_keywords_array = [];
		tags_exp = [];
		if (data['auto_data']['Name']) { document.getElementById("Name").value = data['auto_data']['Name']; }
		if (data['auto_data']['Zipcode']) { document.getElementById("zipcode").value = data['auto_data']['Zipcode']; }
		if (data['auto_data']['Phone']) { document.getElementById("mobile_number").value = data['auto_data']['Phone']; }
		if (data['auto_data']['Work_Status']) {
			if (data['auto_data']['Work_Status'] != 'Visa') {
				if (data['auto_data']['Work_Status'] == 'US Citizen') {
					document.getElementById("USCitizen").checked = true;
				} else if (data['auto_data']['Work_Status'] == 'Green Card') {
					document.getElementById("Green Card").checked = true;
				} else {
					document.getElementById("EAD").checked = true;
				}
				show_visa_transfer_div(0);
				if (data['auto_data']['Salary_Type'] == 'Hourly') {
                                        document.getElementById("hourly").checked = true;
                                        change_salary_unit('Hourly');
                                } else {
                                        document.getElementById("Annually").checked = true;
                                        change_salary_unit('Annually');
                                }
			} else {
				document.getElementById("Visa").checked = true;
                                show_visa_transfer_div(1);
                                if (data['auto_data']['Visa_Transfer'] == '0') {
                                        document.getElementById("no_visa").checked = true;
                                        visa_transfer_yes(0);
                                } else {
                                        document.getElementById("yes_visa").checked = true;
                                        visa_transfer_yes(1);
                                }
			}
		}
		if (data['auto_data']['Relocate'] == '0') { document.getElementById("No_Relocate").checked = true; }
		else { document.getElementById("Yes_Relocate").checked = true; }
		if (data['auto_data']['Salary']) { document.getElementById("salary").value = data['auto_data']['Salary']; }
		if (data['auto_data']['Position_Type']) {
			if (data['auto_data']['Position_Type'] == 'Remote') { document.getElementById("Remote").checked = true; }
			else if (data['auto_data']['Position_Type'] == 'OnSite') { document.getElementById("OnSite").checked = true; }
			else { document.getElementById("Hybrid").checked = true; }
		}
		if (data['auto_data']['Linkedin_URL']) { document.getElementById("Linkedin_URL").value = data['auto_data']['Linkedin_URL']; }
		if (data['auto_data']['skill_list']) {
			// adding skills
			for (let i=0; i < data['auto_data']['skill_list'].length ; i++) {
				auto_fill_skills(data['auto_data']['skill_list'][i]['Skill_Id'], data['auto_data']['skill_list'][i]['Skill'], data['auto_data']['skill_list'][i]['Exp']);
			}
		}
	}
	});
}

function change_exp_select(e,index){
	var selectedValue = e.options[e.selectedIndex].value;
	tags_exp[index] = selectedValue;
	update_list();
}

function fill_exp_options(exp,index){
	innerhtml = "<select id='exp_select_id' onchange='change_exp_select(this," + index + ")'>";
	for (let i = 1; i<=99; i++){
		if ( i == exp) { innerhtml = innerhtml + "<option selected value='" + i + "'>" + i + "</option>"; }
		else {innerhtml = innerhtml + "<option value='" + i + "'>" + i + "</option>"; }
	}
	innerhtml = innerhtml + "</select>";
	return innerhtml;
}

function auto_fill_skills(skill_id,skill,Exp) {
	tags_array.push(skill_id);
	tags_keywords_array.push(skill);
	tags_exp.push(Exp);
	update_list();
}

function show_visa_transfer_div(show){
	if (show == 0){
		document.getElementById('visa_transfer_div').style.display = "none";
		document.getElementById('salary_type_div').style.display = "block";
	} else {
		document.getElementById('visa_transfer_div').style.display="block";
		document.getElementById('salary_type_div').style.display="none";
	}
	document.getElementById('no_visa').checked = true;
	change_salary_unit('Hourly');
	document.getElementById('hourly').checked = true;
}

function visa_transfer_yes(yes) {
	if (yes == 1) {
		change_salary_unit('Annually');
		document.getElementById('Annually').checked = true;
	} else {
		change_salary_unit('Hourly');
                document.getElementById('hourly').checked = true;
	}
}

function change_salary_unit(salary_type){
	var label;
	label = document.getElementById('salary_label');
	if (salary_type == 'Hourly'){ 
		label.innerHTML = 'Expected Hourly Rate* : ';
		document.getElementById('salary').placeholder = 'Expected Hourly Rate';
	}
	else if (salary_type = 'Annually') { 
		label.innerHTML = 'Expected Annual Salary* : ';
		document.getElementById('salary').placeholder = 'Expected Annual Salary';
	}
}

function addtag() {
	var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('language_text');
        filter = input.value.toUpperCase();
        ul = document.getElementById("tag_ul");
	li = ul.getElementsByTagName('li');
	other_button = document.getElementById('other_li').getElementsByTagName("button")[0];

     	for (i = 0; i < li.length; i++) {
       		b = li[i].getElementsByTagName("button")[0];
           	b.style.display = "none";
      	}
	
	for (i = 0; i < li.length; i++) {
        	a = li[i].getElementsByTagName("button")[0];
            	txtValue = a.textContent || a.innerText;
            	if (txtValue.toUpperCase().indexOf(filter) > -1) {
             		a.style.display = "block";
              	}
	}

	if (filter.length > 0){
		other_button.style.display = "block"; //for other button
	}
	
	if (filter.length < 1){
       		for (i = 0; i < li.length; i++) {
              		b = li[i].getElementsByTagName("button")[0];
                    	b.style.display = "none";
           	}
  	}
}

function add_tag(tagid,tag_keyword){
	document.getElementById('language_text').value = "";
     	ul = document.getElementById("tag_ul");
    	li = ul.getElementsByTagName('li');
     	for (i = 0; i < li.length; i++) {
     		b = li[i].getElementsByTagName("button")[0];
             	b.style.display = "none";
	}
	curr_tag_id = tagid;
	curr_tag_keyword = tag_keyword;
	open_popup(tagid,tag_keyword);
}

function open_popup(tagid,tag_keyword){
	modal.style.display = "block";
	if (tagid == -1){
		document.getElementById("other_name_label").style.display = "block";
		document.getElementById("other_name").style.display = "block";
	}
	else{
		document.getElementById("other_name_label").style.display = "none";
        document.getElementById("other_name").style.display = "none";
	}
	var label = document.getElementById("yoe_label");
	label_text = "Years of Experience in " + tag_keyword + " : ";
	label.innerHTML = label_text;
}

function add_exp(){
	if (curr_tag_id == -1){
	    let other_name_value = document.getElementById("other_name").value;
		if (other_name_value.length < 1){
			alert("Please enter Name of Technology");
			return;
		} else if (other_name_value.length > 40) {
		    alert("Please enter valid Name of Technology");
			return;
		} else if (other_name_value.includes(",")) {
		    alert("Please enter valid Name of Technology");
			return;
		} else if (other_name_value.includes(";")) {
		    alert("Please enter valid Name of Technology");
			return;
		}
	}
	var exp = document.getElementById("yoe_input").value;
	if (exp.length < 1){
		alert("Please fill Years of Experience");
		return;
	}
	var year = /^\d{1}$/;
	var years = /^\d{2}$/;
	if ((!(exp.match(year))) && (!(exp.match(years)))){
		alert("Please fill correct value in Years of Experience");
		return;
	}
	tags_array.push(curr_tag_id);
	if (curr_tag_id == -1){
		curr_tag_keyword = document.getElementById("other_name").value;
		document.getElementById("other_name").value = "";
	}
	tags_keywords_array.push(curr_tag_keyword);
	tags_exp.push(exp);
	modal.style.display = "none";
	document.getElementById("yoe_input").value = "";
	update_list();
}

function update_list(){
	//if (tags_array.length > 0){
	//	document.getElementById("list_technology_label").style.display = "block";
	//}
	//else {
	//	document.getElementById("list_technology_label").style.display = "none";
	//}
	document.getElementById("list_technology").innerHTML = "";
	var ull = document.getElementById("list_technology");
	for (let i = 0; i < tags_array.length; i++) {
		option_innerhtml = fill_exp_options(tags_exp[i] , i);
  		//li_innerhtml = tags_keywords_array[i] + " - "  + option_innerhtml + ` Years <button type="button" style="background: none;border: none;" onclick="remove_tag(${i})">
		//<img src="images/delete-button.png" style="height: 25px;width: 25px;"></img></button>`;
		li_innerhtml = "<td>" + (i+1).toString() + ".</td><td>" + tags_keywords_array[i] + "</td><td>" + option_innerhtml + ` Years</td> <td><button type="button" style="background: none;border: none;" onclick="remove_tag(${i})"><img src="images/delete-button.png" style="height: 25px;width: 25px;"></img></button></td>`;
        	let lii = document.createElement("tr");
		lii.innerHTML = li_innerhtml;
		ull.appendChild(lii);
	}
	//ull.appendChild(document.createElement("br"));
	curr_tag_id = "";
	curr_tag_keyword = "";
}

function remove_tag(index){
	tags_array.splice(index, 1);
	tags_keywords_array.splice(index , 1);
	tags_exp.splice(index , 1);
	update_list();
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
	modal.style.display = "none";
	curr_tag_id = "";
        curr_tag_keyword = "";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	if (event.target == modal) {
	  	modal.style.display = "none";
	  	curr_tag_id = "";
		curr_tag_keyword = "";
  	}
}

function validation() {
	if ((tags_array.length < 1) && (tags_keywords_array.length < 1)){
		alert("Please add skills");
		return false;
	}
	else{
		document.getElementById('tags_array_id').value = JSON.stringify(tags_array);
		document.getElementById('tags_keywords_array_id').value = JSON.stringify(tags_keywords_array);
		document.getElementById('tags_exp_array_id').value = JSON.stringify(tags_exp);
	}
	var zipcode_reg = /^\d{3,7}$/;
	if(!(document.getElementById("zipcode").value.match(zipcode_reg))){
                alert("Not a valid ZIP Code");
                return false;
        }
	var phoneno = /^\d{10}$/;
	if(!(document.getElementById("mobile_number").value.match(phoneno))){
		alert("Not a valid Mobile Number");
		return false;
	}
	var salary = /^\d{1,20}$/;
	if(!(document.getElementById("salary").value.match(salary))){
                alert("Not a valid Salary");
                return false;
	}
	//linkedin url check
	var linkedin_url = document.getElementById('Linkedin_URL').value;
	if (linkedin_url.length > 1) {
	    if (linkedin_url.search("linkedin.com") == -1) {
	        alert("Not a valid Linkedin URL");
	        return false;
	    }
	    if (linkedin_url.length < 26) {
	        alert("Not a valid Linkedin URL");
	        return false;
	    }
	}
	return true;
}

</script>
</body>
</html>

