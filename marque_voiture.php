<?php
session_start();
if (!isset($_SESSION['Login'])) {
    header("Location:login.php");
} else {
    include('Gestion_location/inc/header_sidebar.php');
}
?>
<style>
    .priceChangeMarque{
        border: 0.5px solid #B6B6B6;
        display: grid !important;
        grid-template-columns: 25% 15% 60%;
        align-content: space-evenly;
        gap : 2px;
        justify-items: center;
        border-radius: 3px;    
    }
</style>
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Voiture</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des marques voiture</div>
				</ol>
			</nav>
			<div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchMarqueVoiture"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
                <div id="div2">
                    <button class="btn btn-add-vert" id="show_form_marquevoiture" title="Ajouter une marque">Ajouter une marque<i class="bx bx-plus" style="color:white"></i></button>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des marques -->
			<div class="table-responsive-xxl" id="marquevoiture-list"></div>
			<!-- end  Liste des marques -->
			<!-- Model suppression marques -->
			<div class="modal fade" id="deleteMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous supprimer la marque ?</p>
							<br>
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_delete">Supprimer</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model suppression marques -->
            <!-- Model alert suppression succès -->
			<div class="modal fade" id="SuccessDeleteMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletemarquevoiture_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression succès -->
            <!-- Model alert suppression echec -->
			<div class="modal fade" id="EchecDeleteMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletemarquevoiture_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression echec -->
			<!-- Model modification MarqueVoiture -->
			<div class="modal fade" id="updateMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Marque</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-marquevoitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <input type="hidden" id="up_marquevoitureid">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Marque<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="up_voituremarque" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Model<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="up_voituremodel" class="form-control p-0 border-0">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_update_marquevoiture">Modifier</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification MarqueVoiture -->
            <!-- Model alert modification succès -->
			<div class="modal fade" id="SuccessUpMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upmarquevoiture_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
			<div class="modal fade" id="EchecUpMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upmarquevoiture_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
            <!-- Model modification MarqueVoiture -->
            
			<div class="modal fade" id="updatePrixMarque" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form style="border-radius: 9px;">
                                <input type="hidden" id="up_prix_marquevoitureid">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>  
                                            <th class="col-md-7">Mois-<?php echo date('Y');?></th>
                                            <th>Prix de location/jour</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <style>
                                        .red-input:focus {
                                            font-weight: bold;
                                            font-size: 18px;
                                            color: #BF1616;
                                        }  
                                    </style>
                                        <tr>
                                            <td>Janvier</td>
                                            <td><input type="number" id="up_prixjan" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td >Février</td>
                                            <td><input type="number" id="up_prixfev" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Mars</td>
                                            <td><input type="number" id="up_prixmars" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Avril</td>
                                            <td><input type="number" id="up_prixavr" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Mai</td>
                                            <td><input type="number" id="up_prixmai" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Juin</td>
                                            <td><input type="number" id="up_prixjuin" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Juillet</td>
                                            <td><input type="number" id="up_prixjuil" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Aout</td>
                                            <td><input type="number" id="up_prixaout" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Septembre</td>
                                            <td><input type="number" id="up_prixsep" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Octobre</td>
                                            <td><input type="number" id="up_prixoct" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>
                                            <td>Novembre</td>
                                            <td><input type="number" id="up_prixnov" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                        <tr>    
                                            <td>Décembre</td>
                                            <td><input type="number" id="up_prixdec" class="red-input border-0" style="text-align:center; background-color:transparent"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="modal-footer">
							    <div>
								    <button class="buttonvalidate" id="btn_update_prix_marque">Mettre à jour</button>
                            	    <button class="buttonechec" id="btn-close">Annuler</button>
							    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification MarqueVoiture -->
            <!-- Model alert modification succès -->
			<div class="modal fade" id="SuccessUpPrixMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Prix Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="up_prix_marquevoiture_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
			<div class="modal fade" id="EchecUpPrixMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Prix Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="up_prix_marquevoiture_success"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
            <!-- Model ajout MarqueVoiture -->
            <div class="modal fade" id="Registration-MarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Marque</h5>
							<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
						</div>
                        <div class="modal-body">
                            <p id="message_marque"></p>
                            <form id="marquevoitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Marque<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="voituremarque" placeholder="Renault" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Model<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="voituremodel" placeholder="Clio 4" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <label class="col-md-12 p-0">Saisisser le prix par mois :<span class="text-danger">*</span></label>
                                <div style="display: grid !important; grid-template-columns: repeat(2,1fr) !important;grid-template-rows: 40px 40px 40px 40px 40px 40px !important;gap: 5px !important;">
                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Janvier
                                        <input type="number" id="prixjan" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>
                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Février
                                        <input type="number" id="prixfev" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>

                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Mars
                                        <input type="number" id="prixmars" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>
                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Avril
                                        <input type="number" id="prixavril" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>

                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Mai
                                        <input type="number" id="prixmai" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>
                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Juin
                                        <input type="number" id="prixjuin" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>

                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Juillet
                                        <input type="number" id="prixjuil" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>
                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Aout
                                        <input type="number" id="prixaout" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>

                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Septembre
                                        <input type="number" id="prixsept" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>
                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Octobre
                                        <input type="number" id="prixoct" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>

                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Novembre
                                        <input type="number" id="prixnov" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>
                                    <div class="priceChangeMarque">
                                        <img src="assets/images/img_prixvoiture/calendar.png" alt="">
                                        Décembre
                                        <input type="number" id="prixdec" style="border: transparent; width: 61px;" placeholder="90">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn-register-marquevoiture">Ajouter</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout MarqueVoiture -->
            <!-- Model alert ajout succès -->
			<div class="modal fade" id="SuccessAddMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addmarquevoiture_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout succès -->
            <!-- Model alert ajout echec -->
			<div class="modal fade" id="EchecAddMarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Marque</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addmarquevoiture_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout echec -->
		</div>
	</div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>
</body>
</html>