<?php
session_start();
if (!isset($_SESSION['Login'])) {
    header("Location:login.php");
} else {
    include('Gestion_location/inc/header_sidebar.php');
}
?>
<style>
/* css modal ajout voiture */
.form-control {
    border: 0.5px solid #B6B6B6;
}
.form-select-simple {
    border: 0.5px solid #B6B6B6;
    display:block;
    width:100%;
    padding:.375rem .75rem;
    font-size:1rem;
    font-weight:400;
    line-height:1.5;
    color:#212529;
    background-color:#ffffff;
    background-clip:padding-box;
}
.select2-container{
    display:block;
    width:100%;
    font-size:1rem;
    font-weight:500;
    line-height:1.5;
    color:#212529;
    background-color:#ffffff;
    background-clip:padding-box;
}

.select2-container--default .select2-selection--single .select2-selection__rendered{
    line-height: 36px;
}
.select2-container .select2-selection--single .select2-selection__rendered{
    padding-left: 20px !important;
}
.select2-container .select2-selection--single {
    height: 37px !important;
}
.select2-container--default .select2-selection--single {
    border-radius: 2px;
}
.select2-selection__arrow {
    height: 40px !important;
}

#formAjoutVoiture {
    display: grid !important;
    grid-template-rows: repeat(3, auto);
    gap: 1%;
}

#fiche_technique {
    display: grid !important;
    grid-template-rows: repeat(2, auto);
    grid-template-columns: repeat(3, auto);
    grid-column-gap: 1%;
    grid-row-gap: 12%;
}

#options {
    display: grid !important;
    grid-template-columns: repeat(2, auto);
    grid-column-gap: 3%;
}

.rowsName {
    font-weight: 700;
    font-size: 16px;
    line-height: 20px;
    color: rgba(191, 22, 22, 1);
}
#papier {
    display: grid !important;
    grid-template-rows: repeat(2, auto);
}
#papier1 {
    display: grid !important;
    grid-template-columns: repeat(2, auto);
    grid-column-gap: 3%;
}

#papier2 {
    display: grid !important;
    grid-template-columns: repeat(3, auto);
    grid-template-rows: repeat(2, auto);
    grid-column-gap: 3%;
}

.griseVignette {
    display: grid !important;
    grid-template-columns: repeat(2, auto);
    align-items: center;
    gap: 2%;
}
#btn-edit-papier{
    margin-right:18px !important;
    border-radius: 0.25rem;
    padding: 0% 0% 0% 0% !important;
    height: 28px;
    padding-left: 7px !important;
}
#btn-edit-papier:hover{
    background:#BF1616 !important ;
}

#btn-edit-papier:hover #iconpapiers{
    color:white !important;
}

.modal-content {
    border-radius: 23px 23px 0px 0px !important;
}

.modal-header {
    border-radius: 22px 22px 0px 0px !important;
}
#PIMM {
    display: flex;
    gap: 5%;
    justify-content: center;
    align-items: center;
}
.pimm {
    border: 0.5px solid #B6B6B6;
    border-radius: 0.25rem;
    padding: 0.47rem 0.75rem;
}
.numberDate {
    width: 100%;
    padding: 0.47rem 0.75rem;
    border: 0.5px solid #B6B6B6;
    border-radius: 0.25rem;
}

input[type=radio] {
    width: 20px;
    height: 20px;
    accent-color: #BF1616;
}

#buttonMenu {
    display: flex !important;
    justify-content: center;
    margin-top: 10%;
    margin-bottom: 10%;
}
.btn-secondary{
    background: #F1F1F1;
border-radius: 3px;
font-weight: 600;
font-size: 14px;
line-height: 18px;
color: #6C6C6C;
border-color: transparent;
}
.btn-secondary:hover{
    background: #BF1616; 
    border-color: transparent;
}

.btn-secondary:focus{
    border-radius: 4%;
    background: #BF1616;
border-radius: 3px;
border-color: #BF1616;
box-shadow: none;
}

</style>
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Voiture</div>
                    <div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;"
                        aria-current="page">Liste des voitures</div>
                </ol>
            </nav>
            <div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" style="border: 1px solid #ffffff;"
                            placeholder="Que recherchez-vous?" id="searchVoiture">
                        <span class="position-absolute top-50 search-show translate-middle-y"><i
                                class='bx bx-search'></i></span>
                    </div>
                </div>
                <div id="div2">
                    <button class="btn btn-add-vert" id="show_form_voiture" title="Ajouter une voiture">Ajouter une
                        voiture <i class="bx bx-plus" style="color:white"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Liste des voitures -->
            <div class="table-responsive-xxl" id="voiture-list"></div>
            <!-- end  Liste des voitures -->

            <!-- Model suppression voitures -->
            <div class="modal fade" id="deleteVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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
            <div class="modal fade" id="SuccessDeleteVoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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
            <div class="modal fade" id="EchecDeleteVoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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
            <div class="modal fade" id="updateVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-voitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <input type="hidden" id="up_voitureid">
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Immatriculation<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="up_voiturepimm1" placeholder="999"
                                                class="form-control p-0 border-0">
                                        </div>
                                        <div id="divtu">TU</div>
                                        <div id="divimm2">
                                            <input type="number" id="up_voiturepimm2" placeholder="9999"
                                                class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM marque_voiture WHERE action_marquevoiture = '1' ORDER BY marque ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Marque<span class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <select name="type" id="up_voitureMarqueModel" class="form-select-simple"
                                            required>
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
                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Type carburant<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <select name="type" id="up_voituretypecarburant"
                                            class="form-select-simple" required>
                                            <option value="0" disabled selected>Selectionner le type de carburant
                                            </option>
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

                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Boite de vitesse<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <select type="text" name="type" id="up_voitureboitevitesse"
                                            class="form-select-simple" required>
                                            <option value="" disabled selected>Selectionner la boite de vitesse</option>
                                            <option value="Manuelle">Manuelle</option>
                                            <option value="Automatique">Automatique</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Nombre de place<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="up_voiturenbreplace" placeholder="5"
                                                class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Nombre de valise<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-12">
                                        <select type="text" name="type" id="up_voiturenbrevalise"
                                            class="form-select-simple" required>
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

                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Puissance (CV)<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-12 p-0">
                                        <div id="divimm1">
                                            <input type="number" id="up_voiturepuissance" placeholder="4"
                                                class="form-control p-0 border-0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Air conditionné<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-6 p-0" style="display: flex; gap: 5px; margin-top: 5px;">
                                        <input type="radio" id="up_voitureavecclim" name="up_voitureclim" value="1">
                                        <label for="up_voitureavecclim">OUI</label>
                                        <input type="radio" id="up_voituresansclim" name="up_voitureclim" value="0">
                                        <label for="up_voituresansclim">NON</label>
                                    </div>
                                </div>

                                <div class="form-group border-bottom mb-4">
                                    <label class="col-md-12 p-0">Carte grise</label>
                                    <div class="col-md-12">
                                        <input type="file" id="up_voiturecartegrise" class="form-control">
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
            <div class="modal fade" id="SuccessUpVoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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
            <div class="modal fade" id="EchecUpVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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
            <div class="modal fade bd-example-modal-lg" id="Registration-Voiture" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div id="bodyAjoutVoiture" class="modal-body">
                            <p id="message"></p>
                            <form id="formAjoutVoiture" autocomplete="off" class="form-horizontal form-material">
                                <div class="rowsName">Fiche technique:</div>
                                <div id="fiche_technique">
                                    <?php
                                        include_once('Gestion_location/inc/connect_db.php');
                                        $query = "SELECT * FROM marque_voiture WHERE action_marquevoiture = '1' ORDER BY marque ASC";
                                        $result = mysqli_query($conn, $query);
                                    ?>
                                    <div>
                                        <label>Marque<span class="text-danger">*</span></label>
                                        <div>
                                            <select class="form-select" id="voitureMarqueModele" name="voitureMarqueModele" required>
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

                                    <div>
                                        <label style="margin-left: 13%;">Immatriculation<span
                                                class="text-danger">*</span></label>
                                        <div id="PIMM">
                                            <div id="divimmm1">
                                                <input class="pimm" type="number" style="width:80px" id="voiturepimm1"
                                                    placeholder="9999">
                                            </div>
                                            <div id="divtuu">TU</div>
                                            <div id="divimmm2">
                                                <input class="pimm" type="number" style="width:80px" id="voiturepimm2"
                                                    placeholder="9999">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label>Nombre de place<span class="text-danger">*</span></label>
                                        <div>
                                            <div id="divimmm1" class="inputstyle">
                                                <input type="number" id="voiturenbreplace" placeholder="5"
                                                    class="numberDate">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Boite de vitesse<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select type="text" name="type" id="voitureboitevitesse"
                                                class="form-select-simple" required>
                                                <option value="" disabled selected>Selectionner la boite de vitesse
                                                </option>
                                                <option value="Manuelle">Manuelle</option>
                                                <option value="Automatique">Automatique</option>
                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                        include_once('Gestion_location/inc/connect_db.php');
                                        $query = "SELECT * FROM carburant_voiture ORDER BY label_carburant ASC";
                                        $result = mysqli_query($conn, $query);
                                    ?>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Type carburant<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="type" id="voituretypecarburant" class="form-select-simple" required>
                                                <option value="" disabled selected>Selectionner le type de carburant
                                                </option>
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
                                        <label class="col-md-12 p-0">Puissance (CV)<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12 p-0">
                                            <div id="divimmm1">
                                                <input type="number" id="voiturepuissance" placeholder="4"
                                                    class="numberDate">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rowsName">Options:</div>
                                <div id="options">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Nombre de valise<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select type="text" name="type" id="voiturenbrevalise" class="form-select-simple"
                                                required>
                                                <option value="" disabled selected>Selectionner le nombre de valise
                                                </option>
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
                                        <label class="col-md-12">Air conditionné<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12" style="display: flex; gap: 10px; margin-top: 7px;">
                                            <input type="radio" id="voitureavecclim" name="voitureclim" value="1"
                                                checked>
                                            <label for="voitureavecclim">OUI</label>
                                            <input type="radio" id="voituresansclim" name="voitureclim" value="0">
                                            <label for="voituresansclim">NON</label>

                                        </div>
                                    </div>
                                </div>

                                <div class="rowsName">Papier:</div>
                                <div id="papier">
                                    <div id="papier1">
                                        <div class="form-group mb-4 griseVignette">
                                            <label class="col-md-12  ">Carte grise<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="voiturecartegrise" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4 griseVignette">
                                            <label class="col-md-12 p-0 ">Vignette<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="voiturevignette" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="papier2">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0 ">Assurance<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="voitureassurance" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Prix assurance<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="number" id="prixassurance" class="numberDate">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Date fin assurance<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="datefinassurance" class="numberDate">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0 ">Visite technique<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="visitetechniquevoiture" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Prix visite technique<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="number" id="prixvisitetechnique" class="numberDate">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Date fin visite technique<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="datefinvisitetechnique" class="numberDate">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php
                                if($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1"){
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM agence WHERE id_agence != '0' AND action_agence = '1' ORDER BY nom_agence ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="rowsName">Agence <span class="text-danger">*</span></div>
                                <div id="agence">
                                    <div class="form-group mb-4">
                                        <div class="col-md-12">
                                            <select name="voitureagence" id="voitureagence" class="form-control"
                                                required>
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
                                </div>
                                <?php
                                }
                                ?>
                            </form>
                        </div>
                        <div class="modal-footer" style="margin-top:5%;">
                            <div style="float: right;">
                                <button class="buttonvalidate" id="btn-register-voiture">Ajouter</button>
                                <button class="buttonechec" id="btn-close">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout Voiture -->

            <!-- Model update visite assurance -->
            <div class="modal fade" id="AssuranceVisite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="min-height:200px;">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Papier voiture
                            </h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div id="buttonMenu">
                                <button id="btn-edit-assurance" style="width:150px" type="button"
                                    class="btn btn-secondary">Assurance</button>
                                <button id="btn-edit-visit" style="width:150px" type="button"
                                    class="btn btn-secondary">Visite Technique</button>
                            </div>
                            <div id="assurance_form" hidden>
                                <div class="modal-body">
                                    <p id="message_assurance"></p>
                                    <form id="form" autocomplete="off" class="form-horizontal form-material">
                                        <div class="form-group mb-4">
                                            <input hidden type="text" id="id_assurance_voiture"
                                                class="form-control p-0 border-0">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Prochaine date fin<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="date-fin-assurance"
                                                    class="form-control p-0 border-0">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Prix (DT)<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12 p-0">
                                                <input type="number" id="prix-assurance" placeholder="200"
                                                    class="form-control p-0 border-0">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Assurance<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="edit-photo-assurance"
                                                    class="form-control p-0 border-0">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <div style="float: right;">
                                        <button class="buttonvalidate" id="btn-edit-assur">Modifier</button>
                                        <button class="buttonechec" id="btn-close">Annuler</button>
                                    </div>
                                </div>
                            </div>

                            <div id="visite_form" hidden>
                                <div class="modal-body">
                                    <p id="message_visite"></p>
                                    <form id="form" autocomplete="off" class="form-horizontal form-material">
                                        <div class="form-group mb-4">
                                            <input hidden type="text" id="id_visite_voiture"
                                                class="form-control p-0 border-0">
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Prochaine date fin<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="date" id="date-fin-visite"
                                                    class="form-control p-0 border-0">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Prix (DT)<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12 p-0">
                                                <input type="number" id="prix-visite" placeholder="200"
                                                    class="form-control p-0 border-0">
                                            </div>
                                        </div>
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Visite technique<span
                                                    class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input type="file" id="edit-photo-visite"
                                                    class="form-control p-0 border-0">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <div style="float: right;">
                                        <button class="buttonvalidate" id="btn-edit-visite">Modifier</button>
                                        <button class="buttonechec" id="btn-close">Annuler</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model  update visite assurance -->

            <!-- Model alert modification assurance succès -->
            <div class="modal fade" id="SuccessUpAssurance" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Assurance</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upassurance_success"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Model alert modification assurance succès -->
            <!-- Model alert modification assurance echec -->
            <div class="modal fade" id="EchecUpAssurance" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Assurance</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upassurance_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Model alert modification assurance echec -->


            <!-- Model alert modification visite succès -->
            <div class="modal fade" id="SuccessUpVisite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Visite
                                Technique</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upvisite_success"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Model alert modification visite succès -->
            <!-- Model alert modification visite echec -->
            <div class="modal fade" id="EchecUpVisit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Visite
                                Technique</h5>
                            <button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upavisite_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Model alert modification visite echec -->

            <!-- Model alert ajout succès -->
            <div class="modal fade" id="SuccessAddVoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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
            <div class="modal fade" id="EchecAddVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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