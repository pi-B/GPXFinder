<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
	<div class="box-connexion">	
		<form method="post">
			<div class="connexion-info" id="identifiant"> 
				<label>Identifiant</label>
				<input type="input" name="user_id">
			</div>
			<div class="connexion-info" id="motdepasse"> 
				<label>Mot de passe</label>
				<input type="input" name="user_password">
				<label> Confirmation mot de passe</label>
				<input type="input" name="password_confirmation">
			</div>
			<button type="submit" >Créer compte</button>
		</form>
	</div>	
</body>
</html>



<?php 
	echo("maxime = ");
	echo(md5("pierre").'<br>');
	echo("moxime = ");
	echo(md5("maxime"));
	
	/*
	La basse de donnée contient trois colonnes :
	user_PK : int(11) , null = non, autoincrement(pas besoin d'avoir une clé primaire compliqué puisqu'on a"qu'un compte pour le moment) => Clé Primaire

	id : varchar(20), utf_general_ci, null = non

	password : varchar(300), utf_general_ci, null = non

	*/

	/*
		Version 1.0 : 
		Verifie si un identifiant a été entré et que sa longueur est supérieur à 4 caractère.

		Prochaines évolutions : contient pas de caractères spéciaux (utiliser une liste de caractère et vérifier qu'ils ne se trouvent pas d ans $_POST['user_id'])
	*/
	function identifiantValide(){
		return(empty($_POST['user_id']) == false && strlen($_POST['user_id']) >= 4  );
	}

	/*
		Version 1.1:
		vérifie que le mot de passe est supérieur à 8 caractères et correspond au mot de passe de confirmation.

		Evolution : vérifier qu'il y'a des caractères spéciaux pour le rendre secure
		-> peut être que c'est faisable avec JS
	*/
	function motDePasseValide(){
		return (empty($_POST['user_password']) == false && strlen($_POST['user_password']) >= 8 && confirmation_password());
	}
	/*
		Version 1 :
		Vérifie que le mot de passe et sa confirmation sont égaux

		Evolution : Le faire avec du JS plutot qu'en PHP
	*/
	function confirmation_password(){
		return(!empty($_POST['password_confirmation']) && $_POST['user_password'] == $_POST['password_confirmation']);
	}

	/*
		Version 1.0 : 
		On veut permettre qu'un seul compte administrateur. Donc on vérifie sur un query select * from user renvoie quelque chose. 

		Evolution :
	*/
	function compteUnique(){
		require ('config.txt');
		try{
			$linkpdo = new PDO("mysql:host=$host;dbname=$db", $user,$pwd);
		}
		catch (Exception $e){
			die('Erreur : ' . $e->getMessage());
		}

		$prepare = $linkpdo->prepare("select * from `user`");
		$prepare->execute();

		$data = $prepare->fetchAll();
		return(empty($data));	
	}

	$salt = "2BCL";

	require('config.txt');
	if(identifiantValide() && motDePasseValide() && compteUnique()) {
		try{
			$linkpdo = new PDO("mysql:host=$host;dbname=$db", $user,$pwd);
		}
		catch (Exception $e){
			die('Erreur : ' . $e->getMessage());
		}	

		$req = $linkpdo->prepare('INSERT INTO user (id,password) VALUES (:identifiant,:motdepasse)');
	
		$req->execute(array('identifiant' => $_POST['user_id'],'motdepasse' => md5($_POST['user_password']).$salt)) ;
	}

?>