<?php



session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	require_once("database.php");

function masyvas($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && masyvas($needle, $item, $strict))) {
	            return true;
	        }
	    }
	    return false;
	}

function pDuom($duomenys, $lentele){
		global $db;
		$req="SELECT $duomenys FROM $lentele";
		$req=$db->query($req);
		$rezultatas=array();
		while ($row = $req->fetch_assoc()) {
			array_push($rezultatas, $row);

		}
		return $rezultatas;
	}



function isloggedin(){
		if(isset($_SESSION["userio_id"])){
			return true;

		}else{
			return false;
		}
	}



if(isset($_POST['registruotis'])){
		$username=$_POST['vardas'];
		$email=$_POST['email'];
		$pass=$_POST['password'];

		$pass2=$_POST['repeat_pass'];



		  if(isset($_POST['check'])){

            $t1=implode(',', $_POST['check']);
  }

		if(!empty($username)&&!empty($pass)&&$pass==$pass2){
			if (masyvas($username, pDuom('name', 'users'))){
				$error1 =  'Toks vartotojas jau egzistuoja';
			}elseif(strlen($pass)<5){
				$error1 =  'Per trumpas slaptažodis. Įveskite ilgesnį ir bandykite dar kartą.';
			}else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $emailErr = "Invalid email format"; }


			else{
				$uzklausa = "INSERT INTO users VALUES('', '$username', '$pass', '$t1', '$email')";
				if ($db->query($uzklausa) === TRUE){
					?><script>window.location="login.php"</script><?php
				}else{
					$error1 = 'Registracija nepavyko. Patikrinkite įvestus duomenis ir bandykite dar kartą.';
				}
			}

		}else{
			$error1 = 'Patikrinkite savo duomenis.';
		}
	}







	if(isset($_POST['prisijungti'])){
		$username=$_POST['nick'];
		$pass=$_POST['password'];
		if(!empty($username)&&!empty($pass)){
			$atsakymas = $db->query("SELECT id FROM users WHERE name='$username' AND password='$pass'");
			if ($atsakymas->num_rows == 1){
				$useris = $atsakymas->fetch_assoc();
				$_SESSION['userio_id']=$useris['id'];
				$_SESSION['userio_nick']=$useris['name'];
				$_SESSION['userio_email']=$useris['email'];
				?><script>window.location="vartotojo.php"</script><?php
			}else{
				$error2 = 'Patikrinkite savo duomenis ir bandykite dar kartą.';
			}
		}else{
			$error2 = 'Laukai tušti, pasitikrinkite ar įvedete duomenis';
		}
	}







?>