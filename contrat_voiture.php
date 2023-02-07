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
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Contrat</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des Contrats</div>
				</ol>
			</nav>
			<div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchContrat"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
                <div id="div2">
                    <button class="btn btn-add-vert" id="show_form_contrat" title="Ajouter un contrat">Ajouter un contrat<i class="bx bx-plus" style="color:white"></i></button>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des contrats -->
			<div class="table-responsive-xxl" id="contrat-list"></div>
			<!-- end  Liste des contrats -->
			<!-- Model suppression contrats -->
			<div class="modal fade" id="deleteContrat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Contrat</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous supprimer le contrat ?</p>
							<br>
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_delete">Supprimer</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model suppression contrats -->
            <!-- Model alert suppression succès -->
			<div class="modal fade" id="SuccessDeleteContrat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Contrat</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletecontrat_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression succès -->
            <!-- Model alert suppression echec -->
			<div class="modal fade" id="EchecDeleteContrat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Contrat</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletecontrat_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression echec -->
            <!-- Model ajout Contrat -->
            <div class="modal fade" id="Registration-Contrat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Contrat</h5>
							<button class="button-close" id="btn-close-x">X</button>
						</div>
                        <div class="modal-body">
                            <p id="message"></p>
                            <form id="contratForm" autocomplete="off" class="form-horizontal form-material">

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Date Début<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="date" id="DateDebutContrat" onchange="afficher_voiture_dispo()" class="form-control p-0 border-0" required>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Date Fin<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="date" id="DateFinContrat" onchange="afficher_voiture_dispo()" class="form-control p-0 border-0" required>
                                    </div>
                                </div>

                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM client WHERE action_client = '1' ORDER BY nom_client ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Client<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select id="ClientContrat" name="ClientContrat" style="width: 470px;" class="form-control p-0 border-0" required>
                                            <option value="0" disabled selected>Selectionner le client</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_client'] . '">' . $row['nom_client'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM agence WHERE id_agence != '0' AND action_agence = '1' ORDER BY nom_agence ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Agence<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select name="AgenceContrat" id="AgenceContrat" onchange="afficher_voiture_dispo()" class="form-control p-0 border-0" required>
                                            <option value="" disabled selected>Selectionner l'agence</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_agence'] . '">' . $row['nom_agence'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-4" id="listevoiture"></div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn-register-contrat">Ajouter</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout Contrat -->
            <!-- Model alert ajout succès -->
			<div class="modal fade" id="SuccessAddContrat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Contrat</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addcontrat_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout succès -->
            <!-- Model alert ajout echec -->
			<div class="modal fade" id="EchecAddContrat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Contrat</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addcontrat_echec"></center>
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