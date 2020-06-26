<?php

//notice.php

// Authenticate
include("session_auth.php");

//if (!auth(1))
	//Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

//recupere  le numero du nom
$nom_id=$_GET['id'];
if (empty($nom_id))
	Header("Location : instru.php");

require("html_functions.php");

if ( $connex = connect_db() ){

	$querry = "SELECT nom FROM Listing where id='$nom_id' " ;
	list($qh,$num) = query_db($querry);
	$data = result_db($qh);
	$nom_nom= $data['nom'];

$titre ="Documents de l'appareil : ".$data['nom'];

en_tete($titre);

echo "<a href=\"". $_SERVER['HTTP_REFERER']."\">Retour à la page catégories...</a>";

	
$dossier_proj ="data/instru/".$nom_nom."/";

	//remplace les espaces par des underscore
	$dossier_proj = str_replace(" ", "_", $dossier_proj);
	// cherche l'existence de ce dossier
	//echo $dossier_proj;
	/// @ devant la fonction pour eviter d'avoir un message d'erreur sur la page web, s'il n'y a pas de dossier
	if ( ($handle = @opendir($dossier_proj)) != FALSE){
		
		
		
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

		
		//si trouvé on créé un tableau 2 colonnes :
		//	a gauche les images
		//	a droite le texte
		
?>		<table cellpadding="1" cellspacing="1" border="1" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  		<tbody>
    			
<?php		while ( $file = array_pop($images) ){
		echo "<tr style=\"width: 40%; text-align: center;\" ><td><a href=\"".$dossier_proj.$file."\" target=\"_newFrame\"><img src=\"";
			//teste l'etension
			$pos = strrpos($file, ".");
			switch ( strtolower(substr($file, $pos+1))){
				case "htm":
				case "html":
					echo "images/html.png\" ><br />";
					break;
				case "doc":
					echo "images/document.png\" ><br />";
					break;
				case "xls":
					echo "images/spreadsheet.png\" ><br />";
					break;
				case "pdf":
					echo "images/pdf.png\" ><br />";
					break;
				case "gif":
				case "jpg":
				case "png":///image
					echo $dossier_proj.$file."\" width=\"150\"><br />";
					break;
				case "avi":
				case "mov":
				case "mpg":///videos
					echo "images/video.png\" ><br />";
					break;
				default :
					echo "images/unknown.png\" ><br />";
					break;
			}//end switch
			//ajoute le nom du fichier sous l'image
			echo "</a>".$file."</td>";
			echo "<td rowspan=\"" .$max."\" style=\" text-align: left;\" >";
				while ( $autres = array_pop($fichiers) ){
					echo "<h3>".$autres."</h3>";
					//inclue le fichier
					if ( $text_handle = fopen( $dossier_proj.$autres, "r")){
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
