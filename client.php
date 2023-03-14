<?php
session_start();
if (!isset($_SESSION['Login'])) {
    header("Location:login.php");
} else {
    include('Gestion_location/inc/header_sidebar.php');
}
?>
<style>
    #fiche_3row{
        display: grid !important;
        grid-template-rows: repeat(1, auto);
        grid-template-columns: repeat(3, auto);
        grid-column-gap: 1%;
    }
    #fiche_4row{
        display: grid !important;
        grid-template-rows: repeat(1, auto);
        grid-template-columns: repeat(4, auto);
        grid-column-gap: 1%;
    }
    .title {
        font-weight: 700;
        font-size: 18px;
        line-height: 20px;
        color: rgba(191, 22, 22, 1);
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .under_title{
        font-weight: 700;
        font-size: 14px;
        line-height: 20px;
        color: rgba(191, 22, 22, 1);
        margin-bottom: 3px;
    }
    .inputtext {
        width: 100%;
        padding: 0.47rem 0.75rem;
        border: 0.5px solid #B6B6B6;
        border-radius: 0.25rem;
    }   
    .form-control {
        border: 0.5px solid #B6B6B6;
    }
    input[type=radio] {
        width: 20px;
        height: 20px;
        accent-color: #BF1616;
    }

    .container {
    display: inline-block;
    position: relative;
    }
    .container .text {
        background: rgba(0, 0, 0, 0.8);
        z-index: 1;
        position: absolute;
        text-align: center;
        font-family: Georgia;
        margin: 0 auto;
        left: 0;
        right: 0;
        top: 30%;
        color: white;
        width: 70%;
    }
</style>
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Client</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des clients</div>
				</ol>
			</nav>
			<div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchClient"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
                <div id="div2">
                    <button class="btn btn-add-vert" id="show_form_client" title="Ajouter un client">Ajouter un client <i class="bx bx-plus" style="color:white"></i></button>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des clients -->
			<div class="table-responsive-xxl" id="client-list"></div>
			<!-- end  Liste des clients -->
			<!-- Model suppression client -->
			<div class="modal fade" id="deleteClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Client</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous supprimer le client ?</p>
							<br>
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_delete">Supprimer</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model suppression client -->
            <!-- Model alert suppression succès -->
			<div class="modal fade" id="SuccessDeleteClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Client</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deleteclient_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression succès -->
            <!-- Model alert suppression echec -->
			<div class="modal fade" id="EchecDeleteClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Client</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deleteclient_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression echec -->
			<!-- Model modification client -->
            <div class="modal fade bd-example-modal-lg" id="updateClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Client</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-agenceForm" autocomplete="off" class="form-horizontal form-material">
                                <input type="hidden" id="up_idclient">
                                <div class="title">Informations personnelles:</div>
                                <div id="fiche_4row">
                                    <div>
                                        <label class="col-md-12 p-0">Nom<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="up_clientNom" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Prénom<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="up_clientPrenom" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Date de naissance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="date" id="up_clientDateNaissance" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Lieu de naissance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="up_clientLieuNaissance" class="inputtext">
                                        </div>
                                    </div>
                                </div>
                                <div id="fiche_3row">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Email<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="email" id="up_clientEmail" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Téléphone<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="up_clientPhone" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Adresse<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="up_clientAdresse" class="inputtext">
                                        </div>
                                    </div>
                                </div>
                                <div class="title">Informations papiers:</div>
                                <div class="form-group mb-4">
                                    <div id ="choice" class="col-md-6 p-0" style="display: flex; gap: 5px; margin-top: 5px;">
                                        <input type="radio" id="CIN" name="Piece" value="1" onchange="updatepiecejointe(this.value)">
                                        <label for="CIN">Carte d'identité natinale</label>
                                        <input type="radio" id="PASSPORT" name="Piece" value="0" onchange="updatepiecejointe(this.value)">
                                        <label for="PASSPORT">Passport</label>
                                    </div>
                                </div>
                                <div id="up_cin" style="display:none">
                                    <div id="fiche_4row">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">N° CIN<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" id="up_clientNumCin" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Date de délivrance<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="up_clientDateCin" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">CIN (Recto)<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="up_clientRectoCin" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">CIN (Verso)<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="up_clientVersoCin" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="up_passport" style="display:none">
                                    <div id="fiche_3row">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">N° Passport<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" id="up_clientNumPassport" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Date de délivrance<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="up_clientDatePassport" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Pièce jointe<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="up_clientPassport" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="under_title">Permis:</div>
                                <div id="fiche_4row">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">N° de permis<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="up_clientNumPermis" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Date de délivrance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="date" id="up_clientDatePermis" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Lieu de délivrance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="up_clientLieuPermis" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Pièce jointe<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="file" id="up_clientPermis" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_update">Modifier</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification Client -->
            <!-- Model alert modification succès -->
			<div class="modal fade" id="SuccessUpClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Client</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upclient_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
			<div class="modal fade" id="EchecUpClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Client</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upclient_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
            <!-- Model ajout Client -->
            <div class="modal fade bd-example-modal-lg" id="Registration-Client" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Client</h5>
							<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
						</div>
                        <div class="modal-body">
                            <p id="message_client"></p>
                            <form id="clientForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="title">Informations personnelles:</div>
                                <div id="fiche_4row">
                                    <div>
                                        <label class="col-md-12 p-0">Nom<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="clientNom" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Prénom<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="clientPrenom" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Date de naissance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="date" id="clientDateNaissance" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Lieu de naissance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="clientLieuNaissance" class="inputtext">
                                        </div>
                                    </div>
                                </div>
                                <div id="fiche_3row">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Email<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="email" id="clientEmail" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Téléphone<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="clientPhone" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Adresse<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="clientAdresse" class="inputtext">
                                        </div>
                                    </div>
                                </div>

                                <div class="title">Informations papiers:</div>
                                <div class="form-group mb-4">
                                    <div id ="choice" class="col-md-6 p-0" style="display: flex; gap: 5px; margin-top: 5px;">
                                        <input type="radio" id="CIN" name="Piece" value="1" onchange="selectpiecejointe(this.value)">
                                        <label for="CIN">Carte d'identité natinale</label>
                                        <input type="radio" id="PASSPORT" name="Piece" value="0" onchange="selectpiecejointe(this.value)">
                                        <label for="PASSPORT">Passport</label>
                                    </div>
                                </div>
                                <div id="cin" style="display:none">
                                    <div id="fiche_4row">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">N° CIN<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" id="clientNumCin" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Date de délivrance<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="clientDateCin" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">CIN (Recto)<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="clientRectoCin" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">CIN (Verso)<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="clientVersoCin" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="passport" style="display:none">
                                    <div id="fiche_3row">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">N° Passport<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="text" id="clientNumPassport" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Date de délivrance<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="clientDatePassport" class="inputtext">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Pièce jointe<span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="clientPassport" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="under_title">Permis:</div>
                                <div id="fiche_4row">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">N° de permis<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="clientNumPermis" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Date de délivrance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="date" id="clientDatePermis" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Lieu de délivrance<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="clientLieuPermis" class="inputtext">
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Pièce jointe<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="file" id="clientPermis" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn-register-client">Ajouter</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout client -->
            <!-- Model alert ajout succès -->
			<div class="modal fade" id="SuccessAddClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Client</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addclient_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout succès -->
            <!-- Model alert ajout echec -->
			<div class="modal fade" id="EchecAddClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Client</h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addclient_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout echec -->
            <!-- Model papiers client -->
			<div class="modal fade" id="papierClient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-header" style="background: #D71218;">
                                <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Papiers Client</h5>
                                <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                            </div>
                            <div class="title">CIN</div>
                            <div class="row">
                                <div class="col-12 col-lg-6 col-lg-6">
                                    <div class="container">
                                        <span id="cin_recto"></span>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 col-lg-6">
                                    <div class="container">
                                        <span id="cin_verso"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="title">Passport</div>
                            <div class="row">                 
                                <div class="col-12 col-lg-12 col-lg-12">
                                    <div class="container">
                                        <span id="file_passport"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="title">Permis</div>
                            <div class="row">
                                <div class="col-12 col-lg-12 col-lg-12">
                                    <div class="container">
                                        <span id="permis"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification Client -->
		</div>
	</div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>

</body>
</html>