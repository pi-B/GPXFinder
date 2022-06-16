<?php

	require('config.txt');

	function identifiantValide(){
		return(empty($_POST['user_id']) == false && strlen($_POST['user_id']) >= 4  );
	}

	function motDePasseValide(){
		return (empty($_POST['user_password']) == false && strlen($_POST['user_password']) >= 8 && confirmation_password());
	}

	function confirmation_password(){
		return(!empty($_POST['password_confirmation']) && $_POST['user_password'] == $_POST['password_confirmation']);
	}


	if(identifiantValide() && motDePasseValide() ) {
		try{
			$linkpdo = new PDO("mysql:host=$host;dbname=$db", $user,$pwd);
		}
		catch (Exception $e){
			die('Erreur : ' . $e->getMessage());
		}	

		$req = $linkpdo->prepare('INSERT INTO user (login,password) VALUES (:identifiant,:motdepasse)');
	
		$req->execute(array('identifiant' => $_POST['user_id'],'motdepasse' => md5($_POST['user_password']))) ;
	}

?>