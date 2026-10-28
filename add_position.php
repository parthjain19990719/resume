<!DOCTYPE html>
<html lang="en">
<head>
    	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Position</title>
	<link rel="stylesheet" href="form.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link href="stylesheet.css" rel="stylesheet" type="text/css" />
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<?php
$noHead=1;
$fileName="add_position.php";
include_once("template.php");
function displayPage()
{
require 'config_resume.php';
?>

<form class="reg_form" action="submit_position.php" method="post" enctype="multipart/form-data" id="position_form">

	<h1 style="margin-bottom: 30px;">Add Position</h1>
        <div class="form-row">
        	<div class="form-group col-md-6">
        		<label for="Title" class="form-label">Title* : </label>
			<input type="text" class="form-control" id="Title" placeholder="Job title" name="Title" required>
           	</div>
	</div>
	<div class="form-row">
       		<div class="form-group col-md-12">
        		<label for="Description" class="form-label">Description* : </label>
                   	<textarea class="form-control" rows="20" cols="120" name="Description" id="Description" placeholder="Description" form="position_form" required></textarea>
            	</div>
	</div>
	<div class="form-row">
        	<div class="form-group col-md-6">
              		<label for="State" class="form-label">State* : </label>
                	<input type="text" class="form-control" id="State" placeholder="State" required name="State">
         	</div>
	</div>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="City" class="form-label">City* : </label>
                        <input type="text" class="form-control" id="City" placeholder="City" required name="City">
                </div>
	</div>
	<div class="form-row">
     		<div class="form-group col-md-6">
          		<label for="Position_Type" class="form-label">Position_Type* : </label><br>
                                <input type="radio" id="Remote" name="Position_Type" value="Remote" required>
                                <label for="Remote"> Remote &nbsp;&nbsp;</label>
                                <input type="radio" id="OnSite" name="Position_Type" value="OnSite" checked>
                                <label for="OnSite"> OnSite &nbsp;&nbsp;</label>
                                <input type="radio" id="Hybrid" name="Position_Type" value="Hybrid">
                                <label for="Hybrid"> Hybrid &nbsp;&nbsp;</label>
          	</div>
    	</div><br>
	<div class="form-row">
    		<div class="form-group col-md-6">
           		<label for="Exp" class="form-label">Experience* : </label>
                    	<input type="tel" id="Exp" class="form-control" placeholder="Required experience" required name="Exp">
           	</div>
	</div>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="Status" class="form-label">Status* : </label><br>
				<input type="radio" id="Not_Active" name="Status" value="0" required checked>
                                <label for="Not_Active"> Not Active &nbsp;&nbsp;</label>
                                <input type="radio" id="Active" name="Status" value="1" >
                                <label for="Active"> Active &nbsp;&nbsp;</label>
                </div>
	</div><br>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="Client_Name" class="form-label">Client_Name : </label>
                        <input type="text" class="form-control" id="Client_Name" placeholder="Client_Name" name="Client_Name">
                </div>
	</div>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="Direct_Vendor" class="form-label">Direct_Vendor* : </label><br>
                                <input type="radio" id="Direct_Vendor_Yes" name="Direct_Vendor" value="1" required checked>
                                <label for="Direct_Vendor_Yes"> Yes &nbsp;&nbsp;</label>
                                <input type="radio" id="Direct_Vendor_No" name="Direct_Vendor" value="0" >
                                <label for="Direct_Vendor_No"> No &nbsp;&nbsp;</label>
                </div>
	</div><br>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="Banner" class="form-label">Banner* : </label><br>
                                <input type="radio" id="Banner_Yes" name="Banner" value="1" required checked>
                                <label for="Banner_Yes"> Yes &nbsp;&nbsp;</label>
                                <input type="radio" id="Banner_No" name="Banner" value="0" >
                                <label for="Banner_No"> No &nbsp;&nbsp;</label>
                </div>
	</div><br>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="References_Required" class="form-label">References_Required* : </label>
                        <input type="tel" id="References_Required" class="form-control" placeholder="References Required" required name="References_Required">
                </div>
	</div>
	<div class="form-row">
		<div class="form-group col-md-6">
			<label for="Postdate" class="form-label">Postdate* : </label>
			<input type="date" id="Postdate" name="Postdate" class="form-control" value="<?php echo date('Y-m-d'); ?>" required />
		</div>
	</div>
	<div class="form-row">
       		<div class="form-group col-md-6">
              		<label for="InformTo_Email" class="form-label">InformTo_Email : </label>
                       	<input type="email" class="form-control" id="InformTo_Email" placeholder="InformTo_Email" name="InformTo_Email" >
           	</div>
	</div>
	<div class="form-row">
                <div class="form-group col-md-8">
                        <label for="Auto_Email_Text" class="form-label">Auto_Email_Text : </label>
                        <textarea class="form-control" rows="10" cols="80" name="Auto_Email_Text" id="Auto_Email_Text" placeholder="Auto_Email_Text" form="position_form"></textarea>
                </div>
	</div>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="Auto_Email_To_Candidate" class="form-label">Auto_Email_To_Candidate* : </label><br>
                                <input type="radio" id="Auto_Email_To_Candidate_Yes" name="Auto_Email_To_Candidate" value="1" required>
                                <label for="Auto_Email_To_Candidate_Yes"> Yes &nbsp;&nbsp;</label>
                                <input type="radio" id="Auto_Email_To_Candidate_No" name="Auto_Email_To_Candidate" value="0" checked>
                                <label for="Auto_Email_To_Candidate_No"> No &nbsp;&nbsp;</label>
                </div>
        </div><br>
	<div class="form-row">
                <div class="form-group col-md-6">
                        <label for="Client_Id" class="form-label">Client_Id* : </label>
                        <input type="text" class="form-control" id="Client_Id" placeholder="Client_Id" required name="Client_Id">
                </div>
	</div>
	<hr>
   	<div class="form-group col-md-6">
   		<label for="tag" class="form-label">Add Skill*: </label>
           	<div class="search_tag">
         		<div class="search_tag_header"><input type="text" id="language_text" onkeyup="addtag()" placeholder="Add Only One Skill At a Time.."></div>
       			<div class="search_tag_body"<ul id="tag_ul">
          		<?php
         	 	$query = "SELECT * FROM Skills WHERE Verified=1; ";
          		$query_run = mysqli_query($connection,$query);
           		if (mysqli_num_rows($query_run) > 0) {
          			while ($row = mysqli_fetch_array($query_run)) {
               		    		echo '<li><button type="button" class="tag_button" onclick="add_tag('. $row['Skill_Id'] .',\'' . $row['Skill'] . '\')">'. $row['Skill'] .'</button></li>';
                 		}
          	   	}?>
     			</div>
        		<input type="hidden" name="tags_array" id="tags_array_id" >
        		<input type="hidden" name="tags_keywords_array" id="tags_keywords_array_id" >
			<input type="hidden" name="tags_exp_array" id="tags_exp_array_id" >
			<input type="hidden" name="tags_mand_array" id="tags_mand_array_id" >
           	</div>
    	</div>
       	<hr>
   	<!-- popup for yoe -->
   	<div id="myModal" class="popup">
   		<div class="popup-content">
           		<span class="close">&times;</span>
                     	<label for="yoe" class="form-label" id="yoe_label"></label>
			<input type="number" class="form-control" style="width:50%;" placeholder="Experience in years" id="yoe_input" name="exp" min="0" max="50" value="5">
			<label for="Mandatory" class="form-label" id="Mandatory_label">Mandatory: </label>
			<input type="radio" id="Mandatory_Yes" name="Mandatory" value="1" required checked>
                       	<label for="Mandatory_Yes"> Yes &nbsp;&nbsp;</label>
                     	<input type="radio" id="Mandatory_No" name="Mandatory" value="0">
                     	<label for="Mandatory_No"> No &nbsp;&nbsp;</label><br>
                   	<button type="button" class="btn btn-primary" onclick="add_exp()"> Add </button>
          	</div>
	</div>
     	<label for="list_technology" class="form-label" id="list_technology_label" style="display: block;margin-top: 20px;margin-bottom: unset;">Your Skills: </label>
     	<table id="list_technology"></table><br>

	<input type="submit" id="submit" name="addposition" class="btn btn-primary" onclick="return validation(this.form)"/>

</form>

<script>

var tags_array = new Array();
var tags_keywords_array = new Array();
var tags_exp = new Array();
var tags_mand = new Array();
var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];
var curr_tag_id = "";
var curr_tag_keyword = "";

function addtag() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById('language_text');
        filter = input.value.toUpperCase();
        ul = document.getElementById("tag_ul");
        li = ul.getElementsByTagName('li');

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

        if (filter.length < 1) {
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

function open_popup(tagid,tag_keyword) {
        modal.style.display = "block";
        var label = document.getElementById("yoe_label");
        label_text = "Years of Experience in " + tag_keyword + " : ";
        label.innerHTML = label_text;
}

function add_exp(){
	var exp = document.getElementById("yoe_input").value;
	var mand = 1;
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
	if (document.getElementById("Mandatory_No").checked){
		mand = 0;
	}
        tags_array.push(curr_tag_id);
        tags_keywords_array.push(curr_tag_keyword);
	tags_exp.push(exp);
	tags_mand.push(mand)
        modal.style.display = "none";
        document.getElementById("yoe_input").value = "";
        update_list();
}

function update_list(){
        document.getElementById("list_technology").innerHTML = "";
        var ull = document.getElementById("list_technology");
        for (let i = 0; i < tags_array.length; i++) {
                option_innerhtml = fill_exp_mand_options(tags_exp[i] , i, tags_mand[i]);
                li_innerhtml = "<td>" + (i+1).toString() + ".</td><td>" + tags_keywords_array[i] + " Mandatory: </td><td>" + option_innerhtml + ` Years</td> <td><button type="button" style="background: none;border: none;" onclick="remove_tag(${i})"><img src="images/delete-button.png" style="height: 25px;width: 25px;"></img></button></td>`;
                let lii = document.createElement("tr");
                lii.innerHTML = li_innerhtml;
                ull.appendChild(lii);
        }
        curr_tag_id = "";
	curr_tag_keyword = "";
	console.log(tags_array);
	console.log(tags_keywords_array);
	console.log(tags_exp);
	console.log(tags_mand);
}

function fill_exp_mand_options(exp,index,mand){
	innerhtml = "<select id='mand_select_id' onchange='change_mand_select(this," + index + ")'>";
	if (mand == 1){
		innerhtml = innerhtml + "<option selected value='1'>Yes</option><option value='0'>No</option>";
	} else {
		innerhtml = innerhtml + "<option value='1'>Yes</option><option selected value='0'>No</option>";
	}
	innerhtml = innerhtml + "</select>Exp: " + "<select id='exp_select_id' onchange='change_exp_select(this," + index + ")'>";
        for (let i = 1; i<=99; i++){
                if ( i == exp) { innerhtml = innerhtml + "<option selected value='" + i + "'>" + i + "</option>"; }
                else {innerhtml = innerhtml + "<option value='" + i + "'>" + i + "</option>"; }
        }
        innerhtml = innerhtml + "</select>";
        return innerhtml;
}

function change_exp_select(e,index){
        var selectedValue = e.options[e.selectedIndex].value;
        tags_exp[index] = selectedValue;
        update_list();
}

function change_mand_select(e,index){
        var selectedValue = e.options[e.selectedIndex].value;
        tags_mand[index] = selectedValue;
        update_list();
}

function remove_tag(index){
        tags_array.splice(index, 1);
        tags_keywords_array.splice(index , 1);
	tags_exp.splice(index , 1);
	tags_mand.splice(index , 1);
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

function validation(form){

	var text_length = document.getElementById('Description').value.length;
	if (text_length < 10) {
                document.getElementById('Description').value = "";
                document.getElementById('Description').scrollIntoView({ block: 'center',  behavior: 'smooth' });
		alert("Description too small");
                return false;
	}
	
	var exp_reg = /^\d{1,2}$/;
	if(!(document.getElementById("Exp").value.match(exp_reg))){
		document.getElementById('Exp').value = "";
                document.getElementById('Exp').scrollIntoView({ block: 'center',  behavior: 'smooth' });
                alert("Not a valid Experience");
                return false;
	}

	var ref_reg = /^\d{1,2}$/;
        if(!(document.getElementById("References_Required").value.match(ref_reg))){
                document.getElementById('References_Required').value = "";
                document.getElementById('References_Required').scrollIntoView({ block: 'center',  behavior: 'smooth' });
                alert("Not a valid References required");
                return false;
	}

	if ((tags_array.length < 1) && (tags_keywords_array.length < 1)){
                alert("Please add skills");
                return false;
        }
        else{
                document.getElementById('tags_array_id').value = JSON.stringify(tags_array);
                document.getElementById('tags_keywords_array_id').value = JSON.stringify(tags_keywords_array);
		document.getElementById('tags_exp_array_id').value = JSON.stringify(tags_exp);
		document.getElementById('tags_mand_array_id').value = JSON.stringify(tags_mand);
        }

        return true;

}

</script>


<?php } ?>
