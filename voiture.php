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
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Voiture</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des voitures</div>
				</ol>
			</nav>
			<div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchVoiture"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
                <div id="div2">
                    <button class="btn btn-add-vert" id="show_form_voiture" title="Ajouter une voiture">Ajouter une voiture <i class="bx bx-plus" style="color:white"></i></button>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des voitures -->
			<div class="table-responsive-xxl" id="voiture-list"></div>
			<!-- end  Liste des voitures -->
			<!-- Model suppression voitures -->
			<div class="modal fade" id="deleteVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Voiture</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous supprimer la voiture ?</p>
							<br>
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_delete">Supprimer</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model suppression voitures -->
            <!-- Model alert suppression succès -->
			<div class="modal fade" id="SuccessDeleteVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Voiture</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletevoiture_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression succès -->
            <!-- Model alert suppression echec -->
			<div class="modal fade" id="EchecDeleteVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Voiture</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletevoiture_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression echec -->
			<!-- Model modification Voiture -->
			<div class="modal fade" id="updateVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Voiture</h5>
                            <button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-voitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <input type="hidden" id="up_voitureid">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Immatriculation<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="up_voiturepimm1" placeholder="999" class="form-control p-0 border-0">
                                        </div>
                                        <div id="divtu">TU</div>
                                        <div id="divimm2">
                                            <input type="number" id="up_voiturepimm2" placeholder="9999" class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM marque_voiture WHERE action_marquevoiture = '1' ORDER BY marque ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Marque<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select name="type" id="up_voitureMarqueModel"
                                            class="form-control p-0 border-0" required>
                                            <option value="0" disabled selected>Selectionner la marque</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_marquevoiture'] . '">' . $row['marque'] .  '  ' . $row['model'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM carburant_voiture ORDER BY label_carburant ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Type carburant<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select name="type" id="up_voituretypecarburant"
                                            class="form-control p-0 border-0" required>
                                            <option value="0" disabled selected>Selectionner le type de carburant</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_carburantvoiture'] . '">' . $row['label_carburant'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Boite de vitesse<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select type="text" name="type" id="up_voitureboitevitesse"
                                            class="form-control p-0 border-0" required>
                                            <option value="" disabled selected>Selectionner la boite de vitesse</option>
                                            <option value="Manuelle">Manuelle</option>
                                            <option value="Automatique">Automatique</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Nombre de place<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="up_voiturenbreplace" placeholder="5" class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Nombre de valise<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select type="text" name="type" id="up_voiturenbrevalise"
                                            class="form-control p-0 border-0" required>
                                            <option value="" disabled selected>Selectionner le nombre de valise</option>
                                            <option value="1">1G + 1P</option>
                                            <option value="2">1G + 2P</option>
                                            <option value="3">2G + 1P</option>
                                            <option value="4">2G + 2P</option>
                                            <option value="5">3G + 1P</option>
                                            <option value="6">3G + 2P</option>
                                            <option value="7">3G + 3P</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Puissance (CV)<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="up_voiturepuissance" placeholder="4" class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Air conditionné<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div>
                                            <input type="checkbox" id="up_voitureavecclim" name="up_voitureclim" value="1">
                                            <label for="up_voitureavecclim">OUI</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="up_voituresansclim" name="up_voitureclim" value="0">
                                            <label for="up_voituresansclim">NON</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Carte grise<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="file" id="up_voiturecartegrise" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Assurance<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="file" id="up_voitureassurance" class="form-control p-0 border-0">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_update_voiture">Modifier</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification Voiture -->
            <!-- Model alert modification succès -->
			<div class="modal fade" id="SuccessUpVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Voiture</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upvoiture_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
			<div class="modal fade" id="EchecUpVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Voiture</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upvoiture_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
            <!-- Model ajout Voiture -->
            <div class="modal fade" id="Registration-Voiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Voiture</h5>
							<button class="button-close" id="btn-close-x">X</button>
						</div>
                        <div class="modal-body">
                            <p id="message"></p>
                            <form id="voitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Immatriculation<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="voiturepimm1" placeholder="999" class="form-control p-0 border-0">
                                        </div>
                                        <div id="divtu">TU</div>
                                        <div id="divimm2">
                                            <input type="number" id="voiturepimm2" placeholder="9999" class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM marque_voiture WHERE action_marquevoiture = '1' ORDER BY marque ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Marque<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select id="voitureMarqueModel" name="voitureMarqueModel" style="width: 470px;" class="form-control p-0 border-0" required>
                                            <option value="0" disabled selected>Selectionner la marque</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_marquevoiture'] . '">' . $row['marque'] .  '  ' . $row['model'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM carburant_voiture ORDER BY label_carburant ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Type carburant<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select name="type" id="voituretypecarburant"
                                            class="form-control p-0 border-0" required>
                                            <option value="" disabled selected>Selectionner le type de carburant</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_carburantvoiture'] . '">' . $row['label_carburant'] . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Boite de vitesse<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select type="text" name="type" id="voitureboitevitesse"
                                            class="form-control p-0 border-0" required>
                                            <option value="" disabled selected>Selectionner la boite de vitesse</option>
                                            <option value="Manuelle">Manuelle</option>
                                            <option value="Automatique">Automatique</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Nombre de place<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="voiturenbreplace" placeholder="5" class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Nombre de valise<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select type="text" name="type" id="voiturenbrevalise"
                                            class="form-control p-0 border-0" required>
                                            <option value="" disabled selected>Selectionner le nombre de valise</option>
                                            <option value="1">1G + 1P</option>
                                            <option value="2">1G + 2P</option>
                                            <option value="3">2G + 1P</option>
                                            <option value="4">2G + 2P</option>
                                            <option value="5">3G + 1P</option>
                                            <option value="6">3G + 2P</option>
                                            <option value="7">3G + 3P</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Puissance (CV)<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="voiturepuissance" placeholder="4" class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Air conditionné<span class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div>
                                            <input type="checkbox" id="voitureavecclim" name="voitureclim" value="1">
                                            <label for="voitureavecclim">OUI</label>
                                        </div>
                                        <div>
                                            <input type="checkbox" id="voituresansclim" name="voitureclim" value="0">
                                            <label for="voituresansclim">NON</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Carte grise<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="file" id="voiturecartegrise" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Assurance<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="file" id="voitureassurance" class="form-control p-0 border-0">
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
                                        <select name="type" id="voitureagence"
                                            class="form-control p-0 border-0" required>
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
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn-register-voiture">Ajouter</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout Voiture -->
            <!-- Model alert ajout succès -->
			<div class="modal fade" id="SuccessAddVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Voiture</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addvoiture_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout succès -->
            <!-- Model alert ajout echec -->
			<div class="modal fade" id="EchecAddVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Voiture</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addvoiture_echec"></center>
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