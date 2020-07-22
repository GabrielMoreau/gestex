<?php

//doc_labview.php

// Authenticate
require_once('auth-functions.php');

//if (!auth(1))
	//Header("Location: login.php");

$logged_id = $_SESSION['logged_id'];
$logged_user = strtolower($_SESSION['logged_user']);
$logged_level= $_SESSION['logged_level'];

//recupere  le numero du nom
$nom_id=$_GET['id'];
if (empty($nom_id))
	Header("Location: list_labview.php");

require_once('html-functions.php');

if ( $connex = connect_db() ){

	$querry = "SELECT manipch FROM labview WHERE id='$nom_id';";
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);
	$nom_nom= $data['manipch'];

$titre ="Documents de la manip : ".$data['manipch'];

en_tete($titre);

echo "<a href=\"". $_SERVER['HTTP_REFERER']."\">Retour &agrave; la page liste des programmes labview...</a>";

$dossier_lab ="data/labview/".$nom_nom."/";

	//remplace les espaces par des underscore
	$dossier_lab = str_replace(" ", "_", $dossier_lab);

	// cherche l'existence de ce dossier
	//echo $dossier_lab;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if ( ($handle = @opendir($dossier_lab)) != FALSE)

	{

	$images = array();	$fichiers= array();
   while (false !== ($file = readdir($handle))) {
   ////echo count($images);
       if ($file != "." && $file != "..") {
           if ( eregi("^[a-zA-Z0-9_\-]+(:?\.jpg|\.gif|\.png|\.pdf|\.doc|\.xls|\.mov|\.avi|\.mpg|\.html|\.htm)$", $file) == TRUE ){
		///entasse les images
		////echo $file;
			array_push( $images,$file );
		 }
	elseif ( eregi("^[a-zA-Z0-9_\-]+(:?\.txt)$", $file ) == TRUE ){
	 	//et les fichiers textes
		array_push ( $fichiers, $file );
       }
   }//end while
	//repere le max
	 $max = count($images);
	if ( count( $fichiers) > $max){
		$max = count( $fichiers);
		$min = count($images);
		}
	else
		$min = count( $fichiers);
	}//end if file!=".."

   closedir($handle);

		//si trouve on cree un tableau 2 colonnes :
		//	a gauche les images
		//	a droite le texte

?>		<table cellpadding="1" cellspacing="1" border="1" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  		<tbody>

<?php		while ( $file = array_pop($images) ){
		echo "<tr style=\"width: 40%; text-align: center;\" ><td><a href=\"".$dossier_lab.$file."\" target=\"_newFrame\"><img src=\"";

			//teste l'etension
			$pos = strrpos($file, ".");
			switch ( strtolower(substr($file, $pos+1))){
				case "htm":
				case "html":
					echo "images/link.svg\" /><br />";
					break;
				case "doc":
					echo "images/document.png\" /><br />";
					break;
				case "xls":
					echo "images/spreadsheet.png\" /><br />";
					break;
				case "pdf":
					echo "images/pdf.png\" /><br />";
					break;
				case "gif":
				case "jpg":
				case "png":///image
					echo $dossier_lab.$file."\" width=\"150\" /><br />";

					break;
				case "avi":
				case "mov":
				case "mpg":///videos
					echo "images/video.png\" /><br />";
					break;
				default :
					echo "images/unknown.png\" /><br />";
					break;
			}//end switch
			//ajoute le nom du fichier sous l'image
			echo "</a>".$file."</td>";
			echo "<td rowspan=\"" .$max."\" style=\" text-align: left;\" >";
				while ( $autres = array_pop($fichiers) ){
					echo "<h3>".$autres."</h3>";
					//inclue le fichier
					if ( $text_handle = fopen( $dossier_lab.$autres, "r")){
						while (!feof($text_handle))
    							echo  fgets($text_handle, 4096)."<br />";
    						fclose($text_handle);
					}//end if fopen
				}//while end autres
		 echo "</td></tr>";
		}//while end  file
?>

		<tbody></table>

<?php
	}
	else
		echo "pas de documents disponibles pour ce projet!<br />";
	}//end if connect
?>

<br />
<br />
</div>
<?php pied_page() ?>
