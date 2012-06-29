<?php

function en_tete( $titre){
   /////echo"<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">\n";
   echo "<html><head>\n";
   echo "  <meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\" />\n";
   echo "  <title>$titre</title>\n";
   echo "</head><body>\n";
   echo "<div width=\"100%\" height=\"100%\" align=\"center\" valign=\"center\"><br />\n";
   echo "<br /><table cellpadding=\"2\" cellspacing=\"0\" border=\"0\" style=\"text-align: left; width: 75%;\" align=\"center\">";
   echo "<tbody> <tr bgcolor=\"#f7d709\">  <td style=\"vertical-align: center;\">";
   echo "      </td> <td style=\"vertical-align: top;\"><br />";
   echo "<h1>GestEx Instrumentation</h1>";
   echo $titre;
   echo "  </td></tr></tbody></table>";
   echo "<br />\n";
   echo "</div>";
   }


function pied_page(){
   echo "<center>\n";
   echo "<img src=\"images/striped.gif\" nosave=\"\" border=\"0\" height=\"13\"  width=\"532\" align=\"bottom\" />\n";

   //ne garde que le nom de fichier
   $filetmp = explode('/',$_SERVER['PHP_SELF']);
   $file = $filetmp[count($filetmp)-1];
   ///mise a jour de ce fichier
   echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" style=\"text-align: center; width: 95%;\">\n";
   echo " <tbody>   <tr><td>\n";
   echo "<!-- <img src=\"images/php-small-purple.gif\" align=\"top\" nosave=\"\" /> --></td>\n";
   echo "<td><address><a href=\"mailto:Muriel.Lagauzere@hmg.inpg.fr?Subject=GestEx%20to%20WebMaster\">\n";
   echo "Muriel Lagauzere</a></address><br /><i>Derni&egrave;re mise &agrave; jour :\n";
   echo strftime('%d/%m/%Y',filemtime($file)); 
   echo "</i></td>";
   echo "<td><!-- <img src=\"images/mysql.png\"  align=\"top\" nosave=\"\" /> --></td>";
   echo "</tr></tbody></table></center>\n";
   echo "</body></html>\n";
   }

?>
