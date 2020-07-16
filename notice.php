<?php
// notice.php

// Authenticate
include("session_auth.php");
session_start();
//if (!auth(1))
	//Header("Location: login.php");

$user_id        = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level     = $_SESSION['level'];

//recupere le numero du nom
if (empty($_GET['id']))
	Header('Location: list_appareil.php');
else
	$id_app=$_GET['id'];

require("html_functions.php");

if ($pdo = connect_db()) {

	$sql = 'SELECT id FROM Listing WHERE id = ?;' ;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_app));
	$listing = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$titre = 'Documents de l\'appareil : '.$listing[0]['nom'].' ('.$id_app.')';
	en_tete($titre);

	echo '<a href="'.$_SERVER['HTTP_REFERER'].'">Retour &agrave; la page cat&eacute;gories</a>';

	$sql = 'SELECT id,nom_notice,chemin_notice FROM notice WHERE id_appareil = ?;' ;
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($id_app));
	$notice = $stmt->fetchAll(PDO::FETCH_ASSOC);


	$dossier_proj = './data/notice/'.$id_app.'/';
	foreach($notice as $notice){
		if(is_file($notice['chemin_notice'])){
		// if (($handle = opendir($dossier_proj))) {
		$images = array();
		$fichiers = array();
		// if ($file = readfile($notice['nom_notice'])) {
			// echo $file.'<br>';
// 			////echo count($images);
			if ($notice['chemin_notice'] != "." && $notice['chemin_notice'] != "..") {
				if (preg_match('/^[a-zA-Z0-9_\-]+(:?\.jpg|\.gif|\.png|\.pdf|\.doc|\.xls|\.mov|\.avi|\.mpg|\.html|\.htm)$/', $notice['nom_notice'])) {
					//entasse les images
					//echo $file;
					array_push($images, $notice['nom_notice']);
				}
				else if (preg_match('/^[a-zA-Z0-9_\-]+(:?\.txt)$/', $notice['nom_notice'] ) == TRUE) {
					//et les fichiers textes
					array_push ($fichiers, $notice['nom_notice']);
				}
			} // end if file!=".."
			// repere le max
			$max = count($images);
			if (count($fichiers) > $max) {
				$max = count($fichiers);
				$min = count($images);
			}
			else
				$min = count($fichiers);
// 		} // end if file!=".."

// 		// closedir($handle);

		//si trouv&eacute; on cr&eacute;e un tableau 2 colonnes :
		//	a gauche les images
		//	a droite le texte
 		?>

 		<table cellpadding="1" cellspacing="1" border="1" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
 	<tbody>

 		<?php
		while ($file = array_pop($images)) {
			echo '<tr style="width: 40%; text-align: center;">';
			echo '  <td>';
			echo '    <a href="'.$dossier_proj.$file.'" target="_newFrame">';
			echo '      <img src="';
			//teste l'etension
			$pos = strrpos($file, ".");
			switch (strtolower(substr($file, $pos+1))){
				case "htm":
				case "html":
					echo 'images/link.svg"><br />';
					break;
				case "doc":
					echo 'images/document.png"><br />';
					break;
				case "xls":
					echo 'images/spreadsheet.png"><br />';
					break;
				case "pdf":
					echo 'images/pdf.png"><br />';
					break;
				case "gif":
				case "jpg":
				case "png":///image
					echo $dossier_proj.$file.'" width="150"><br />';
					break;
				case "avi":
				case "mov":
				case "mpg":///videos
					echo 'images/video.png"><br />';
					break;
				default :
					echo 'images/unknown.png"><br />';
					break;
			} // end switch
			//ajoute le nom du fichier sous l'image
			echo '    </a>'.$file;
			echo '  </td>';
			echo '  <td rowspan="'.$max.'" style="text-align: left;">';
			while ($autres = array_pop($fichiers)) {
				echo '    <h3>'.$autres.'</h3>';
				//inclue le fichier
				if ($text_handle = fopen($dossier_proj.$autres, "r")) {
					while (!feof($text_handle))
						echo  fgets($text_handle, 4096).'<br />';
					fclose($text_handle);
				} // end if fopen
			} // while end autres
			echo '  </td>';
			echo '  <td style="vertical-align: top;">';
			echo '    <a>'.$notice['id'].'</a>';
			echo '  </td>'.PHP_EOL;
			if ($user_level >=2){
				echo '  <td style="vertical-align: top;">';
				echo '    <a href="add_appareil.php?id=',$id_app,'">'.ICON_EDIT.'</a>';
				echo '  </td>'.PHP_EOL;
			}
			if ($user_level >=3){
				echo '  <td style="vertical-align: top;">';
				echo '    <a href="del_notice.php?id=',$notice['id'],'">'.ICON_TRASH.'</a>';
				echo '  </td>'.PHP_EOL;
			}
			echo '</tr>';
		} // while end  file
		?>

 	<tbody>
</table>

 <?php
		}
		}
	



}//end if connect
?>

<br />
</div>
<?php pied_page() ?>
