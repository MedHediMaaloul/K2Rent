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
    ;
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
    justify-content: space-around;
    margin-top: 10%;
    margin-bottom: 10%;
}
</style>
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Stock</div>
                    <div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;"
                        aria-current="page">Liste stock voiture</div>
                </ol>
            </nav>
            <div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" style="border: 1px solid #ffffff;" placeholder="Que recherchez-vous?"
                            id="searchStockVoiture">
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
            <!-- Liste des marques -->
            <div class="table-responsive-xxl" id="stockvoiture-list"></div>
            <!-- end  Liste des marques -->
            <!-- Model ajout Voiture -->
            <div class="modal fade bd-example-modal-lg" id="Registration-Voiture" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
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
                                            <select style="border: 0.5px solid #B6B6B6;" class="form-select"
                                                id="voitureMarqueModele" name="voitureMarqueModele" required>
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
                                                class="form-control" required>
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
                                            <select name="type" id="voituretypecarburant" class="form-control" required>
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
                                            <select type="text" name="type" id="voiturenbrevalise" class="form-control"
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
            <!-- Model modification Voiture -->
            <div class="modal fade" id="updateagencevoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Transférer Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-transfertvoitureForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <input type="hidden" id="up_idvoiture">
                                </div>
                                <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT * FROM agence WHERE id_agence != '0' AND action_agence = '1' ORDER BY nom_agence ASC";
                                $result = mysqli_query($conn, $query);
                                ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Agence<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select name="type" id="up_voitureAgence" class="form-control p-0 border-0"
                                            required>
                                            <option value="0" disabled selected>Selectionner l'agence</option>
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
                                <button class="buttonvalidate" id="btn_update_agencevoiture">Transférer</button>
                                <button class="buttonechec" id="btn-close">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification Voiture -->
            <!-- Model alert modification succès -->
            <div class="modal fade" id="SuccessUpAgenceVoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Transférer Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upagencevoiture_success"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
            <div class="modal fade" id="EchecUpAgenceVoiture" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Transférer Voiture</h5>
                            <button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png"
                                    alt=""></button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upagencevoiture_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
        </div>
        <button class="btn btn-add-bleu" id="export_stock" title="Exporter le stock">Exporter<i
                class="bx bx-arrow-to-bottom" style="color:white"></i></button>
    </div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>
</body>

</html>