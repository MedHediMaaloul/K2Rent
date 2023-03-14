<!DOCTYPE html>
<html lang="en" class="color-header headercolor3">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/K2.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- ajax -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<!-- <link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet"> -->
	<link href='https://fonts.googleapis.com/css?family=Rajdhani' rel='stylesheet'>
	<link href="https://fonts.cdnfonts.com/css/bambino-2" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="app.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<!-- calendar css -->
	<link href="assets/css/calendar.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="assets/css/dark-theme.css" />
	<link rel="stylesheet" href="assets/css/semi-dark.css" />
	<link rel="stylesheet" href="assets/css/header-colors.css" />
	<title>K2 Rent</title>
	<style>
		i+i {
  			margin-left: 5px;
		}
	</style>
</head>

<body onload="startTime()">
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<img src="assets/images/k2rent.png" class="logo-icon">
			<!--navigation-->
			<ul class="metismenu" id="menu" >
				<li>
                    <a href="dashboard.php">
						<div class="menu-title"><img src="assets/images/img_menu/dashboard.png" style="margin-right: 15px;" width="18px" height="18px">Tableau De Bord</div>
					</a>
				</li>
				<?php if ($_SESSION['Role'] == 0) {?>
                <li>
                    <a href="agence.php">
						<div class="menu-title"><img src="assets/images/img_menu/agence.png" style="margin-right: 15px;" width="20px" height="18px">Agences</div>
					</a>
				</li>
                <li>
                    <a href="utilisateur.php">
						<div class="menu-title"><img src="assets/images/img_menu/user.png" style="margin-right: 15px;" width="20px" height="20px">Utilisateurs</div>
					</a>
				</li>
				<?php }?>
				<li>
					<a href="client.php">
						<div class="menu-title"><img src="assets/images/img_menu/clients.png" style="margin-right: 15px;" width="20px" height="20px">Clients</div>
					</a>
				</li>
                <li>
					<a href="javascript:;" class="has-arrow">
						<div class="menu-title"><img src="assets/images/img_menu/voiture.png" style="margin-right: 15px;" width="22px" height="13px">Voitures</div>
					</a>
					<ul>
						<li> <a href="voiture.php">Liste des Voitures</a></li>
						<li> <a href="marque_voiture.php">Liste des Marques</a></li>
						<li> <a href="papier_voiture.php">Liste des Papiers</a></li>
						<li> <a href="papier_archivage_voiture.php">Liste Archivage des Papiers</a></li>
					</ul>		
				</li>
                
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="menu-title"><img src="assets/images/img_menu/contrat.png" style="margin-right: 15px;" width="20px" height="20px">Contrat</div>
					</a>
					<ul>
						<li> <a href="contrat_voiture.php">Liste des Contrats</a></li>
						<li> <a href="contrat_archivage_voiture.php">Liste Archivage des Contrats</a></li>
                        <li> <a href="historique_contrat_voiture.php">Historiques Contrat</a></li>
					</ul>
				</li>

				<li>
                    <a href="planning.php">
						<div class="menu-title"><img src="assets/images/img_menu/planing.png" style="margin-right: 15px;" width="20px" height="20px">Planning</div>
					</a>
				</li>

                <li>
					<a href="stock_voiture.php">
						<div class="menu-title"><img src="assets/images/img_menu/stock.png" style="margin-right: 15px;" width="20px" height="20px">Stock</div>
					</a>
				</li>

				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="menu-title"><img src="assets/images/img_menu/entretien.png" style="margin-right: 15px;" width="17px" height="17px">Entretiens</div>
					</a>
					<ul>
						<li> <a href="entretien_voiture.php"></i>Liste des Entretiens</a></li>
						<li> <a href="entretien_archivage_voiture.php"></i>Liste archivage des Entretiens</a></li>
                        <li> <a href="historique_entretien_voiture.php"></i>Historiques Entretiens</a></li>
					</ul>
				</li>

				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="menu-title"><img src="assets/images/img_menu/notification.png" style="margin-right: 15px;" width="20px" height="20px">Notifications</div>
					</a>
					<ul>
						<li> <a href="notification_contrat.php"></i>Notifications des Contrats</a></li>
						<li> <a href="notification_controle_papier.php"></i>Notifications des Contrôles Papiers</a></li>
					</ul>
				</li>
                	
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="user-info d-none d-lg-block">
						<ul class="nav">
							<li class="nav-item">
								<div class="user-name"><img src="assets/images/img_menu/time.png" style="margin-bottom: 5px;" width="24px" height="24px"><i id="time"></i></div>
						  	</li>
					  	</ul>
					</div>
					<div class="top-menu ms-auto" >
						<ul class="navbar-nav align-items-center" style="margin-right: -30px; margin-top:10px;">
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" id="toggle-controle-visite-technique" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
									<span id="count_visite_fin" class="alert-count"></span>
									<img src="assets/images/img_menu/Visitetechnique.png" width="50px" height="50px">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<div id="div1">
												<p class="msg-header-title">Contrôle visite technique</p>
											</div>
											<div id="divnotif">
												<p id="count_visite_fin_not_vue"></p>
											</div>
										</div>
									</a>
									<div class="header-message-list">
										<p class="msg-info" id="controle_visite"></p>	
									</div>
								</div>
							</li>

							<li class="nav-item dropdown dropdown-large" style="margin-left: -29px;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" id="toggle-controle-assurance" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
									<span id="count_assurance_fin" class="alert-count"></span>
									<img src="assets/images/img_menu/Assurance.png" width="50px" height="50px">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<div id="div1">
												<p class="msg-header-title">Contrôle assurance</p>
											</div>
											<div id="divnotif">
												<p id="count_assurance_fin_not_vue"></p>
											</div>
										</div>
									</a>
									<div class="header-message-list">
										<p class="msg-info" id="controle_assurance"></p>	
									</div>
								</div>
							</li>

							<li class="nav-item dropdown dropdown-large" style="margin-left: -29px;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" id="toggle-contrat-fin" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
									<span id="count_contrat_fin" class="alert-count"></span>
									<img src="assets/images/img_menu/FinContrat.png" width="50px" height="50px">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<div id="div1">
												<p class="msg-header-title">Contrats qui prendront fin</p>
											</div>
											<div id="divnotif">
												<p id="count_contrat_fin_not_vue"></p>
											</div>
										</div>
									</a>
									<div class="header-message-list">
										<p class="msg-info" id="contrat_prendront_fin"></p>	
									</div>
								</div>
							</li>

							<li class="nav-item dropdown dropdown-large" style="margin-left: -29px;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" id="toggle-contrat-create" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<span id="count_contrat_crée" class="alert-count"></span>
									<img src="assets/images/img_menu/CreateContrat.png" width="50px" height="50px">
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<div id="div1">
												<p class="msg-header-title">Contrats crées</p>
											</div>
											<div id="divnotif">
												<p id="count_contrat_crée_not_vue"></p>
											</div>
										</div>
									</a>
									<div class="header-notifications-list">
										<p class="msg-info px-0" id="contrat_crée"></p>
									</div>
							
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown">
					<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<img src="assets/images/avatars/avatar.png" class="user-img" alt="user avatar">
					</a>
					<ul class="dropdown-menu dropdown-menu-end">
						<li>
							<div class="user-info ps-2"><span class="user-name"><?php echo $_SESSION['Nom'];?></span></div>
							<?php if ($_SESSION['Role'] != 0) {?>
								<div class="user-info ps-2"><span class="role"><?php echo $_SESSION['RoleLabel'];?></span></div>
							<?php }?>
						</li>
						<hr class="solid">
						<li><a class="dropdown-item" href="logout.php?logout"><i class='bx bx-log-out-circle'></i><span>Déconnexion</span></a></li>
					</ul>
					</div>
				</nav>
			</div>
		</header>