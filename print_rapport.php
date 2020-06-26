<?php

//rapport.php

// Authenticate
include("session_auth.php");

if (!auth(2))
//il faut etre au moins ita
	Header("Location: login.php");

$user_id = $_SESSION['user_id'];
$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_level= $_SESSION['level'];

if (!empty($_POST[equipe]))
	$equip_id=$_POST[equipe];
if (!empty($_POST[manip]))
	$manip_id=$_POST[manip];
if (!empty($_POST[projet]))
	$projet_id=$_POST[projet];
if (!empty($_POST[tache]))
	$tache_id=$_POST[tache];
if (!empty($_POST[date]))
	$depuis=$_POST[date];

require("html_functions.php");

en_tete("rapport");

if ( $connex = connect_db() ){

	// recupere les refs du user
	$querry = "SELECT prenom,nom FROM users where loggin='$logged_in_user' " ;
	list($qh,$num) = query_db($querry);
	
$data = result_db($qh);
echo " Bienvenue $data[prenom] $data[nom] ($user_id)<br /><br />";
/////echo "equipe:".$equip_id." manip :".$manip_id." projet :".$projet_id." tache :".$tache_id."<br />";
?>
<br />
<table cellpadding="2" cellspacing="2" border="0"
 style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
  <tbody>
    <tr class=menu>
	 <td style="vertical-align: top; text-align: center;">
	<a href="accueil.php">Retour a l'accueil</a>
	<br /></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="rapport.php">Retour à la création de rapport</a>
	<br /></td>
 <td style="vertical-align: top; text-align: center;">
	<a href="logout.php?variable=projet">Quitter</a>
	<br /></td> </tr></tbody>
</table>
<br />

<br />
<h1>Rapport de temps passé depuis :
<?php
	echo $depuis."</h1>";
/****$querry = "SELECT id,nom FROM equipe " ;
	list($qh,$num) = query_db($querry);
	///recupere les infos des equipes   
****/
$temps_proj=0;
$temps_manip = 0;
$temps_equip = 0;
	//pour toutes les equipes
/***	while($equipes = result_db($qh)){

	echo "<h2>Equipe :".$equipes[nom]." (".$equipes[id].")</h2><ul>";	****/

		$querry = "SELECT * FROM manip ";
		if ($manip_id != 0)	// pour une manip, sinon pour toutes
			$querry.=" WHERE id='$manip_id'";
	//////	if (!empty($depuis))
	//////		$querry.=" AND date>='$depuis';";
		list($qh1,$num) = query_db($querry);
		///recupere les infos des manips par equipes

	echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"1\" style=\"width: 95%; text-align: left; margin-left: auto; margin-right: auto;\">  <tbody>";

	while($manips = result_db($qh1)){

		echo "<tr class=manip colspan=3><td>";
		echo "<h3>Manip :".$manips[nom]." (".$manips[id].") debut:".$manips[date]."<br />".$manips[descr]."</h3>";
		/// recherche les noms des chercheurs associés
			$querry =" select nom FROM users WHERE id=".$manips[chercheur];
			list($qhc,$num) = query_db($querry);
			$data =  result_db($qhc);
		echo "Pour :".$data[nom];
		if (!empty($manips[chercheur_bis])){
			$querry =" select nom FROM users WHERE id=".$manips[chercheur_bis];
			list($qhc,$num) = query_db($querry);
			$data =  result_db($qhc);
			echo " et ".$data[nom];

		}//end if empty
		echo "</td></tr>";

			$querry = "SELECT * FROM projet WHERE manip='$manips[id]' " ;
			if ( $projet_id !=0)	// pour un projet, sinon pour tous
				$querry.=" AND id='$projet_id'";
		//////	if (!empty($depuis))
		//////		$querry.=" AND date>='$depuis';";
			list($qh2,$num) = query_db($querry);
			///recupere les infos des projets
			while($projets = result_db($qh2)){
				echo "<tr class=projet><td></td><td colspan=2>";
				echo "<h4>Projet :".$projets[nom]." (".$projets[id].") debut:".$projets[date]."<br />".$projets[descr]."</h4>";

				$querry = "SELECT * FROM tache WHERE projet='$projets[id]' " ;
				if ( $tache_id !=0)	// pour une tache, sinon pour toutes
					$querry.=" AND id='$tache_id'";
				if (!empty($depuis))
					$querry.=" AND date>='$depuis';";
				list($qh3,$num) = query_db($querry);
				///recupere les infos des taches
				while($taches = result_db($qh3)){
					echo "<tr class=tache><td></td><td></td><td>";
					echo "Tache :".$taches[nom]." (".$taches[id].") debut:".$taches[date]."<br />".$taches[descr]."<br />";
					echo " Temps passé pour cette tache : ";
					$temps_tache=0; unset($user_tache);
					// recuper les temps passés pour cette tache
						$querry = "select * FROM temps WHERE id_tache=". $taches[id];
					if (!empty($depuis))
						$querry.=" AND date >='$depuis';"; 
					list($qh5,$num) = query_db($querry);
					///recupere les infos des taches
					while($timings = result_db($qh5)){
						if ( strstr($user_tache, $timings[user])==0)
							///evite les doublons
							$user_tache.= $timings[user].",";
						$temps_tache+= $timings[duree];

					}//end while timings
					echo $temps_tache." heures";echo "</td></tr>";
							

				if (!empty($user_tache)){
					echo " par:";
					//recherche les noms des users
					$users = str_replace(","," OR id=", rtrim($user_tache," ,"));
					$querry = "select nom FROM users WHERE id=".$users;
					list($qh6,$num) = query_db($querry);
					while($users= result_db($qh6))
						echo $users[nom].",";
					}//end if
					echo "</td></tr>";
						$temps_proj+= $temps_tache;

					}//while end taches
				echo "<tr><td>Temps passé pour ce projet : ".$temps_proj." heures</td></tr>>";
				$temps_manip += $temps_proj;
				$temps_proj =0;
				}//while end projets
			echo " <tr><td>Temps passé pour cette manip : ".$temps_manip." heures</td></tr>";
			$temps_equip += $temps_manip;
			$temps_manip=0;
			}//end while manips
		echo " <tr><td>Temps passé pour cette equipe : ".$temps_equip." heures</td></tr>";
		$temps_equip =0;
	////	}//end while equipes

	echo"</tbody></table>";

}//end if connect
?>
  
<br />
<br />
</div>
<?php pied_page() ?>
