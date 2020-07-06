<?php

//accueil.php

// Authenticate
include("session_auth.php");

if (!auth(1))
	Header("Location: login.php");

$logged_in_user = strtolower($_SESSION['logged_in_user']);
$user_id = $_SESSION['user_id'];
$user_level = $_SESSION['level'];
require("html_functions.php");

en_tete('Liste des Manips');

//recuper la methode de tri
if (empty($_GET['tri']))
	$tri ="nom";
else
	$tri = $_GET['tri'];

if ( $pdo = connect_db() ){
	// recupere les refs du user
	// $querry = "SELECT * FROM users where loggin='$logged_in_user' " ;
	// list($qh,$num) = query_db($querry);
	// $data = result_db($qh);
	$sql = 'SELECT nom, prenom FROM users WHERE loggin = ?;';
	$stmt = $pdo->prepare($sql);
	$stmt->execute(array($logged_in_user));
	$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// echo 'Bienvenue ',$user[0]['prenom'],'', $user[0]['nom'] , '(',$user_id,')<br /><br />';
	// echo '<br>';

	?>
	<br />
	<table cellpadding="2" cellspacing="2" border="0" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
		<tbody>
			<tr class=menu>
				<th style="vertical-align: top; text-align: center; ">
					<a href="list_fourn.php?tri=nom">Liste des<br />Fournisseurs</a>
					<br />
				</th>
				<!-- <td style="vertical-align: top; text-align: center;">
				<a href="list_app.php">Liste des<br />Appareils</a>
				<br /></td> -->
				<th style="vertical-align: top; text-align: center; " >
					<a href="list_users.php?tri=nom">Liste des<br />Utilisateurs</a>
					<br />
				</th>
				<th style="vertical-align: top; text-align: center; " >
					<a href="list_equip.php?tri=nom">Liste des<br />&Eacute;quipes</a>
					<br />
				</th>
				<?php if ($user_level>=2){  ?>
				<th style="vertical-align: top; text-align: center; " >
					<a href="add_manip.php">Ajouter<br />une Manip</a>
					<br />
				</th>
				<th style="vertical-align: top; text-align: center; class:menu;" >
					<a href="rapport.php">Editer<br />les rapports</a>
					<br />
				</th>
				<?php } ?>
				<th style="vertical-align: top; text-align: center; " >
					<a href="logout.php?variable=projet">Quitter</a>
					<br />
				</th>
			</tr>
		</tbody>
	</table>
	<br />
	Voici la liste des manips d&eacute;j&agrave; rentr&eacute;es dans la base de donn&eacute;es :
	<br />
	<table cellpadding="2" cellspacing="2" border="1" style="width: 90%; text-align: left; margin-left: auto; margin-right: auto;">
		<tbody>
			<tr bgcolor="#f7d709">
				<th style="vertical-align: top; text-align: center;">
					<a href ="accueil.php?tri=date">Date</a><br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="accueil.php?tri=nom">Nom</a><br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="accueil.php?tri=descr">Description</a><br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="accueil.php?tri=local">Local</a> <br />
				</th>
				<th style="vertical-align: top; text-align: center;">
					<a href ="accueil.php?tri=equipe">&Eacute;quipe</a>
				</th>
				<?php if ($user_level!=1){
				//pas necessaire si chercheur logue
				?>
				<th style="vertical-align: top; text-align: center;">
					<a href ="accueil.php?tri=chercheur">Chercheur</a>
				</th>
				<th style="vertical-align: top; text-align: center;">
					<br />
				</th>
				<?php } ?>
				<th style="vertical-align: top; text-align: center;">
					<br />
				</th>
			</tr>
			<?php	//interrogation base de donnees

			// recupere la liste de manips
			// $querry = "SELECT * FROM manip ";

			// $querry.="order by '$tri' ";
			// list($qh,$num) = query_db($querry);
			$sql = 'SELECT * FROM manip ORDER BY ?;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array($tri));
			$manip = $stmt->fetchAll(PDO::FETCH_ASSOC);

			// while ($data = result_db($qh)) {
			foreach ($manip as $manip) {
				$relief =0;
				if ($user_level >1)
					$relief =1;
				// remplit le tableau
				if ($user_level <=1){
				//mise en evidence des manips concernant le chercheur connecte
					if ($manip['chercheur']== $user_id || $manip['chercheur_bis']== $user_id){
						echo '<tr bgcolor="#FFFAD0" style="vertical-align: top;">';
						$relief = 1;
					}
				}else{
					echo '<tr style="vertical-align: top;">';
				}
				echo '<td style="vertical-align: top;">';
				echo $manip['date'];
				echo '</td><td style="vertical-align: top;">';
				if ($relief ==1)
					//possibilite d'edition des manips concernant le chercheur connecte
					echo '<a href="manip_maint.php?id=',$manip['id'],'">';
				echo $manip['nom'];
				if ($relief ==1)
						echo "</a>";
				echo '</td><td style="vertical-align: top;">';
				echo $manip['descr'];
				echo '</td><td style="vertical-align: top;">';
				echo $manip['local'];
				echo '</td><td style="vertical-align: top;">';
				// recupere la liste de equipes
				// $querry = "SELECT nom FROM equipe WHERE id ='$data['equipe']'";
				// list($qheq,$numeq) = query_db($querry);
				// 	$eq = result_db($qheq)	 ;
				$sql = 'SELECT nom FROM equipe WHERE id = ?;';
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array($manip['equipe']));
				$equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if(!empty($equipe)){echo $equipe[0]['nom'];}
				if ($user_level!=1){
					//si chercheur logue pas necessaire
					echo '</td><td style="vertical-align: top;">';
						// recupere la liste des chercheurs
					// $querry = "SELECT nom FROM users WHERE id ='$data['chercheur']'";
					// list($qheq,$numeq) = query_db($querry);
					// 	$equipe = result_db($qheq)	 ;
					// 	echo $eq['nom'];
					$sql = 'SELECT nom FROM users WHERE id = ?;';
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array($manip['chercheur']));
					$chercheur = $stmt->fetchAll(PDO::FETCH_ASSOC);
					// var_dump($chercheur);
					if(!empty($chercheur)){echo $chercheur[0]['nom'];}
				}
				if ($user_level>=2){
					echo '</td><td style="vertical-align: top;">';
					echo '<a href="add_manip.php?id=',$manip['id'],'"><img src="images/pen.svg" nosave="" title="Modifier" /></a>';
				}
				if ($user_level==3){
					echo '</td><td style="vertical-align: top;">';
					echo '<a href="del_manip.php?id=',$manip['id'],'"><img src="images/trash.svg" nosave="" title= "Supprimer" /></a>';
				}
			echo '</td></tr>';
			} // end foreach
			?>
 		</tbody>
	</table>
	<br />

<?php
} //end if
pied_page()
?>
