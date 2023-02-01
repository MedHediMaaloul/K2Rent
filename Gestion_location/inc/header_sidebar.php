<!DOCTYPE html>
<html lang="en" class="color-sidebar sidebarcolor3 color-header headercolor1">

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
	<link href="https://fonts.googleapis.com/css2?family=Roboto" rel="stylesheet">
	<link href="https://fonts.cdnfonts.com/css/bambino-2" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="app.css">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
			<div class="sidebar-header">
				<div class="col-3"></div>
				<div><img src="assets/images/k2rent_menu.png" class="logo-icon" alt="logo icon"></div>
				<div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i></div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<li>
                    <a href="dashboard.php">
						<div class="menu-title">Tableau De Bord</div>
					</a>
				</li>
				<?php if ($_SESSION['Role'] == "superadmin") {?>
                <li>
                    <a href="agence.php">
						<div class="menu-title">Agence</div>
					</a>
				</li>
                <li>
                    <a href="utilisateur.php">
						<div class="menu-title">Utilisateur</div>
					</a>
				</li>
				<hr class="solid">
				<?php }?>
				<li>
					<a href="client.php">
						<div class="menu-title">Client</div>
					</a>
				</li>
                <li>
					<a href="javascript:;" class="has-arrow">
						<div class="menu-title">Voiture</div>
					</a>
					<ul>
						<li> <a href="voiture.php"><i class="bx bx-right-arrow-alt"></i>Liste des voitures</a></li>
						<li> <a href="marque_voiture.php"><i class="bx bx-right-arrow-alt"></i>Liste des marques</a></li>
					</ul>		
				</li>
                
                <li>
					<a href="stock_voiture.php">
						<div class="menu-title">Stock</div>
					</a>
				</li>
                <hr class="solid">
                <li>
					<a href="javascript:;" class="has-arrow">
						<div class="menu-title">Contrat</div>
					</a>
					<ul>
						<li> <a href="contrat_voiture.php"><i class="bx bx-right-arrow-alt"></i>Liste des Contrats</a></li>
						<li> <a href="archivage-contart-voiture.php"><i class="bx bx-right-arrow-alt"></i>Liste archivage des Contrats</a></li>
                        <li> <a href="contart-pack.php"><i class="bx bx-right-arrow-alt"></i>Historique Contrat</a></li>
					</ul>
				</li>
                <li>
                    <a href="planning.php">
						<div class="menu-title">Planning</div>
					</a>
				</li>
                <hr class="solid">
				<li>
					<a href="entretien.php" class="has-arrow">
						<div class="menu-title">Entretien</div>
					</a>
					<ul>
                        <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Archivage Entretien</a>
                            <ul>
						        <li> <a href="entretien-voiture.php"><i class="bx bx-right-arrow-alt"></i>Archivage Entretien Vehicule</a>
						        </li>
                                <li> <a href="entretien-materiel.php"><i class="bx bx-right-arrow-alt"></i>Archivage Entretien Matériel</a>
						        </li>
                            </ul>
                        </li>
                        <li> <a href="historique-entretien.php"><i class="bx bx-right-arrow-alt"></i>Historique Entretiens</a>
						</li>
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
								<div class="user-name"><i class='bx bx-time-five'></i><i id="time"></i></div>
						  	</li>
					  	</ul>
					</div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
						<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" id="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<span class="alert-count"></span>
									<i class='bx bx-message-alt-edit'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Contrat crée</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<p class="msg-info" id=""></p>
									</div>
							
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" id="toggle-contrat" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
									<span id="count-contrat" class="alert-count"></span>
									<i class='bx bx-message-alt-detail'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Contrat qui prendront fin</p>
										</div>
									</a>
									<div class="header-message-list">
										<p class="msg-info" id="dropdown-menu-contrat"></p>	
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown">
					<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<img src="assets/images/avatars/avatar.jpg" class="user-img" alt="user avatar">
						<div class="user-info ps-3">
							<span class="user-name mb-0"><?php echo $_SESSION['Nom'];?></span>
							<p class="role mb-0"><?php echo $_SESSION['Role'];?></p>
						</div>
					</a>
					<ul class="dropdown-menu dropdown-menu-end">
						<li><a class="dropdown-item" href="logout.php?logout"><i class='bx bx-log-out-circle'></i><span>Déconnexion</span></a></li>
					</ul>
					</div>
				</nav>
			</div>
		</header>