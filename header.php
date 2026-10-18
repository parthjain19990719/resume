<?php

$pages["index.php"]="HOME";

$pages["ourclients.php"]="OUR CLIENTS";

$pages["aboutus.php"]="ABOUT US";

$pages["services.php"]="SERVICES";

$pages["technology.php"]="TECHNOLOGY";

$pages["Verticals.php"]="VERTICALS";

$pages["career.php"]="CAREERS";

$pages["ContactUs.php"]="CONTACT US";

?>



   <table width="100%" border="0" cellpadding="0" cellspacing="0"> <?php /* Header */ ?>

    <tr>

     <td>

      <table width="100%"  border="0" cellpadding="0" cellspacing="0">

       <tr>

        <td width="100"> &nbsp; </td>

        <td width="247"><img src="images/Logo.gif" alt="Advanced Image Inc." v:shapes="_x0000_s1025" height="48" border="0" width="247" /></td>

        <td align="center"><strong>YOUR GLOBAL IT SERVICES PARTNER</strong></td>

        <td height="81" width="100" align="right">&nbsp;</td>

       </tr>

      </table>

     </td>

    </tr>

    <tr>

     <td height="5" bgcolor="#0099cc" valign="bottom"><font size="1">

       &nbsp;</font>

     </td>

    </tr>

    <tr>

     <td>

      <table class="mainmenu" border="0" cellpadding="3" cellspacing="0" width="100%">

       <tr class="emailLink">

        <?php

          foreach ($pages as $key => $value)

          {

            if($key != $fileName)

            {

               echo '<td class="emailLink" width="12%" align="center"><strong><a href="'.$key.'"><font color="#000080" size="2">'.$value.'</font></a></strong></td>';

            } else

            {

               echo '<td bgcolor="#ffffff" width="12%" align="center"><strong><font color="#000080" size="2">'.$value.'</font></strong></td>';

            }

            if($key != "ContactUs.php")

            {

              echo '<td class="emailLink" width="2%" align="center"><font color="#003399" size="2">|</font></td>';

            }

          }

        ?>

       </tr>

      </table>

     </td>

    </tr>

    <tr>

     <td height="5" bgcolor="#ffcc00" valign="bottom"><font size="1">

      &nbsp;</font>

     </td>

    </tr>

   </table>  <?php /* Header */ ?>