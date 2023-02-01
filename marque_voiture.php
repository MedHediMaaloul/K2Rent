<?php
session_start();
if (!isset($_SESSION['User'])) {
    header("Location:login.php");
} else {
    include('Gestion_location/inc/header_sidebar.php');
}
?>
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
    						<button class="button-close" id="btn-close-x">X</button>
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
    						<button class="button-close" id="btn-close-x">X</button>
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
    						<button class="button-close" id="btn-close-x">X</button>
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
                            <button class="button-close" id="btn-close-x">X</button>
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
    						<button class="button-close" id="btn-close-x">X</button>
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
    						<button class="button-close" id="btn-close-x">X</button>
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
            <!-- Model ajout MarqueVoiture -->
            <div class="modal fade" id="Registration-MarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Marque</h5>
							<button class="button-close" id="btn-close-x">X</button>
						</div>
                        <div class="modal-body">
                            <p id="message"></p>
                            <form id="marquevoitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Marque<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="voituremarque" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Model<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="voituremodel" class="form-control p-0 border-0">
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
    						<button class="button-close" id="btn-close-x">X</button>
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
    						<button class="button-close" id="btn-close-x">X</button>
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