<?php
$erreurlogin = '';
$erreurpassword = '';
$erreur = '';
$ressaye = '';
session_start();
require_once('Gestion_location/inc/connect_db.php');

if (isset($_POST['login'])) {
    if (empty($_POST['login'])) {
        $erreurlogin = "Login est obligatoire!";
    } else if (empty($_POST['password'])){
        $erreurpassword = "Mot de passe est obligatoire!";
    }else {
        $query = "SELECT * 
					FROM user 
					LEFT JOIN role_user ON user.role_user = role_user.id_roleuser
					WHERE etat_user ='T' 
					AND login_user='" . $_POST['login'] . "' and motdepasse_user='" . md5($_POST['password']) . "'";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['User'] = $_POST['login'];
            $_SESSION['Role'] =  $row['label_roleuser'];
			$_SESSION['Nom'] =  $row['nom_user'];
            $_SESSION['id_user'] =  $row['id_user'];
            $_SESSION['id_agence'] =  $row['id_agence'];
            header("location:dashboard.php");
        } else {
            $erreur = "Mot de passe incorrect !";
			$ressaye = "Veuillez essayer à nouveau.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/k2rent.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>K2 Location</title>
	<style>
		.form-control{
			background-color:#2D2E2E;
			border:1px solid #2D2E2E;
			color:#ffffff;
		}

		.login {
  			min-height: 100vh;
  			background-color: #000000;
  			color: #ffffff;
		}

		.bg-image {
		  background-image: url(assets/images/logink2.png);
		  background-size: cover;
		  background-position: center;
		}

		.login-heading {
		  font-weight: 600;
		}

		.btn-login {
			background: #D71218 0% 0% no-repeat padding-box;
		  	border-radius: 7px;
		  	text-align: center;
			color: #fff;
			font-size: 11px;
		  	width:36%;
			cursor: pointer;
		}
		.btn-login:hover {
    		background-color: rgba(255, 0, 0, 1);
		}

		.btn-login:active {
		    box-shadow: inset -2px -2px 3px rgba(255, 255, 255, .6),inset 2px 2px 3px rgba(0, 0, 0, .6);
		}

		@keyframes wiggle {
		  0%, 10% {
		    transform: rotateY(-90deg);
		  }
		  15% {
		    transform: rotateY(-80deg);
		  }
		  20% {
		    transform: rotateY(-70deg);
		  }
		  25% {
		    transform: rotateY(-60deg);
		  }
		  30% {
		    transform: rotateY(-50deg);
		  }
		  35% {
		    transform: rotateY(-40deg);
		  }
		  40% {
		    transform: rotateY(-30deg);
		  }
		  45% {
		    transform: rotateY(-20deg);
		  }
		  50% {
		    transform: rotateY(-10deg);
		  }
		  60%, 100% {
		    transform: rotateY(0deg);
		  }
		}

		@keyframes example {
		  0%   {font-size: 0%}
		  25%  {font-size: 25%}
		  50%  {font-size: 50%}
		  75%  {font-size: 75%}
		  100% {font-size: 100%}
		}


		@keyframes slidebienvenue {
		  from {
		    font-size: 0%;
		  }
	  
		  to {
		    font-size: 130%;
		  }
		}

		@keyframes slidek2 {
		  from {
		    font-size: 0%;
		  }
	  
		  to {
		    font-size: 230%;
		  }
		}

		.triangle{
		  	height: auto;
 	 		width: auto;
			display: flex;
  			background-color: #2D2E2E;
  			border-radius:7px;
  			justify-content:center;
  			align-items: center;
			padding: 10px;
		}
		.texterror{
			color: #EB0007;
		  	font-weight: bold;
		  	font-size: 18px;
			
		}
		.textressaye{
			color: white;
			font-weight: normal;
		  	font-size: 13px;
		
		}
		.obligatoire {
		  color: red;
		  /* font-weight: bold; */
		  font-size: 10px;
		}

		

		<?php if($erreur != ''){?>
			.input-group{
				border:1px solid red;
			}
		<?php }?>

		.lds-ring {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 8px;
  border: 8px solid #fff;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #fff transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

	</style>
</head>
<body>
	<div class="container-fluid ps-md-0">
	  	<div class="row g-0">
	    	<div class="d-none d-md-flex col-md-4 col-lg-5 bg-image"></div>
	    	<div class="col-md-8 col-lg-7">
	    	  	<div class="login d-flex align-items-center py-5">
	    	    	<div class="container">
	    	      		<div class="row">
	    	        		<div class="col-lg-4 mx-auto">
								<?php if ($erreur != ''){?>
									<div class="triangle">
										<i class='fadeIn animated bx bx-error bx-lg' style='color:#EB0007;'></i>
										<div class="col-lg-1"></div>
										<p1 class="texterror"><?php echo $erreur?><span class="textressaye"><br><?php echo $ressaye ?></span></p1>
									</div>
								<?php } ?>
								<p class="mb-4"></p>
								<div style="animation: wiggle 2s linear;"><img src="assets/images/k2rent.png" class="login-icon" alt="logo icon"></div>
								<p class="mb-4"></p>
								<div style="font-size: large; font-weight:bold; animation: 1s slidebienvenue;">Bienvenue chez</div>
								<div style="font-size: xx-large; font-weight: bold; animation: 3s slidek2;">K2 RENT</div>
								<div class="font-size: xx-small; animation: 1s slidein;">Veuillez vous connecter à votre compte</div>
							
	    	          			<form action="login.php" method="post">
									<br>
	    	            			<div>
	    	            			  <input type="texte" class="form-control" id="login" name="login" placeholder="Login">
	    	            			</div>
									<p1 class="obligatoire"><?php echo $erreurlogin;?></p1>
									<p class="mb-2"></p>
									<div class="input-group" id="show_hide_password">
	    	            			  <input type="password" class="form-control" id="password" name="password" placeholder="Password"><a href="javascript:;" class="input-group-text"><i class='bx bx-hide'></i></a>
									</div>
									<p1 class="obligatoire"><?php echo $erreurpassword;?></p1>
									<p class="mb-4"></p>
	    	            			<div class="d-grid">
	    	            			  <button class="btn btn-login" type='submit' name="Login">Connexion</button>
	    	            			</div>
	    	          			</form>
	    	        		</div>
	    	      		</div>
	    	    	</div>
	    	  	</div>
	    	</div>
	  	</div>
	</div>

	<footer class="bg-white shadow-sm border-top p-2 text-center fixed-bottom">Copyright © <?php echo date("Y"); ?>. All rights reserved.</footer>
	
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script>
		$(document).ready(function () {
			$("#show_hide_password i").on('click', function (event) {
				console.log($("#show_hide_password i").target);
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show").css('red');
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show").css('top', '30px');
				}
			});
		});


	</script>
	<script src="assets/js/app.js"></script>
</body>
</html>