<?php
if(!isset($noHead)) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/TR/REC-html40" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title><?php echo $title; ?></title>
<?php if(isset($keywords))
      {
         echo $keywords;
      }
?>
<?php if(isset($description))
      {
         echo $description;
      }
?>
</head>
<?php
}
/*
<style>
body {
  background-image: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)),url('/AILogoBg.png');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: cover;
}
</style> */
?>
<body>
<link href="stylesheet.css" rel="stylesheet" type="text/css" />

<table width=925 border="0" cellpadding="0" cellspacing="0" align=center> <?php /* whole page */ ?>
 <tr>
  <td><?php include_once("header.php"); ?>  </td>
 </tr>
 <tr>
  <td> <?php displayPage(); ?>
  </td>
 </tr>
 <tr>
  <td> <?php include_once("footer.php"); ?>
  </td>
 </tr>
 <tr><td>&nbsp;</td></tr>
</table> 

</html>
