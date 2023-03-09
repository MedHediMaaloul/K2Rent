<?php
session_start();
if (!isset($_SESSION['Login'])) {
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
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Voitures</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des papiers</div>
				</ol>
			</nav>
			<div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchPapier"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des papier voiture -->
			<div class="table-responsive-xxl" id="papier-voiture"></div>
			<!-- end Liste des papier voiture -->
            </div>
			       <!-- Model modification Voiture -->
				   <div class="modal fade" id="updatePapiers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Papier Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-voitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <input type="hidden" id="up_IdPapier">
                                </div>
								<div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Assurance</label>
                                    <div class="col-md-12">
                                        <input type="file" id="up_voitureassurance" class="form-control p-0 border-0">
                                    </div>

                                </div>  <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Vignette</label>
                                    <div class="col-md-12">
                                        <input type="file" id="up_voiturevignette" class="form-control p-0 border-0">
                                    </div>
                                </div>

                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Visite Technique</label>
                                    <div class="col-md-12">
                                        <input type="file" id="up_voiturevisite" class="form-control p-0 border-0">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <div style="float: right;">
                                <button class="buttonvalidate" id="btn_update_papier">Modifier</button>
                                <button class="buttonechec" id="btn-close">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification Voiture -->
            <!-- Model alert modification succès -->
            <div class="modal fade" id="SuccessUpPapierVoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier papiers voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="uppapiervoiture_success"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
            <div class="modal fade" id="EchecUpPapierVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier papiers voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="uppapiervoiture_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
	</div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>

</body>
</html>