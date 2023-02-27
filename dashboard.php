<?php
session_start();
$id_agence = $_SESSION['Agence'];
include('Gestion_location/inc/header_sidebar.php');
include('Gestion_location/inc/connect_db.php');
?>
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb mb-0 p-0">
						<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Tableau De Board</div>
						<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Tableau De Board</div>
					</ol>
				</nav>
		</div>
		<div class="row">
			<div class="col-lg-4">
				<div style="height:130px;" class="card radius-3">
                    <div style="margin-top:35px; margin-left: 40px;">
                        <img src="assets/images/img_dashboard/client.png"  width="64px" height="64px">
                    </div>
                    <div style="margin-top:-60px; margin-left: 130px; font-weight:bold; font-size: 24px;line-height: 31px;color: #7C7C7C;">
                        Clients <br>
                        <span style="font-weight:bold; font-size:30px; color:#000000;">
                        <?php
                            $query_clienttotal = "SELECT COUNT(*)
                            FROM client 
                            WHERE action_client = '1'";
                            $result_clienttotal = mysqli_query($conn, $query_clienttotal);
                            $row_clienttotal = mysqli_fetch_row($result_clienttotal);
                            echo $client_total = $row_clienttotal[0];
                        ?>
                        </span>
                    </div>
                    <div style="margin-top:-50px; margin-left: 240px;">
                        <button id="show_form_client" title="Ajouter un client" style="height:0px; width:0px; border:0px;"><img src="assets/images/img_dashboard/ajout.png" width="36px" height="36px" /></button>
                    </div>
                </div>
			</div>

            <div class="col-lg-4">
				<div style="height:130px;" class="card radius-3">
                    <div style="margin-top:35px; margin-left: 40px;">
					    <img src="assets/images/img_dashboard/voiture.png" width="64px" height="64px">
                    </div>
                    <div style="margin-top:-60px; margin-left: 130px; font-weight:bold; font-size: 24px;line-height: 31px;color: #7C7C7C;">
					    Voitures<br> 
                        <span style="font-weight:bold; font-size:30px; color:#000000;">
                        <?php
                        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                            $query_voituretotal = "SELECT COUNT(*)
                            FROM voiture 
                            WHERE action_voiture = '1'";
                        }else{
                            $query_voituretotal = "SELECT COUNT(*)
                            FROM voiture 
                            WHERE action_voiture = '1'
                            AND id_agence = $id_agence";
                        }
                        $result_voituretotal = mysqli_query($conn, $query_voituretotal);
                        $row_voituretotal = mysqli_fetch_row($result_voituretotal);
                        echo $voiture_total = $row_voituretotal[0];
                        ?>
						</span>
					</div>
                    <div style="margin-top:-50px; margin-left: 240px;">
					    <button id="show_form_voiture" title="Ajouter une voiture" style="height:0px; width:0px; border:0px;"><img src="assets/images/img_dashboard/ajout.png" width="36px" height="36px" /></button>
                    </div>
                </div>
			</div>

            <div class="col-lg-4">
				<div style="height:130px;" class="card radius-3">
                    <div style="margin-top:35px; margin-left: 40px;">
					    <img src="assets/images/img_dashboard/voiture.png" width="64px" height="64px">
                    </div>
                    <div style="margin-top:-60px; margin-left: 130px; font-weight:bold; font-size: 24px;line-height: 31px;color: #7C7C7C;">
					    Marques<br> 
                        <span style="font-weight:bold; font-size:30px; color:#000000;">
                        <?php
                            $query_marquevoituretotal = "SELECT COUNT(*)
                            FROM marque_voiture 
                            WHERE action_marquevoiture = '1'";
                            $result_marquevoituretotal = mysqli_query($conn, $query_marquevoituretotal);
                            $row_marquevoituretotal = mysqli_fetch_row($result_marquevoituretotal);
                            echo $marquevoiture_total = $row_marquevoituretotal[0];
                        ?>
                        </span>
					</div>
                    <div style="margin-top:-50px; margin-left: 240px;">
					    <button id="show_form_marquevoiture" title="Ajouter une marque" style="height:0px; width:0px; border:0px;"><img src="assets/images/img_dashboard/ajout.png" width="36px" height="36px" /></button>
                    </div>
                </div>
			</div>

            <div class="col-2 col-lg-4">
				<div class="d-flex align-items-center">
					<div>
						<div class="text-blackbold">États de voitures</div>
					</div>
				</div>
                <p class="mb-2"></p>
				<div style="height:185px;" class="card radius-3">
					<div id="charttauxvehicule">
						<input type=hidden id="vehicule_loue" value=
                            <?php 
                            $date = date('Y-m-d');
                            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                                $query_numbervehicule = "SELECT count(*) as number FROM voiture Where action_voiture = '1'";
                                
								$query_numbervehicule_loue = "SELECT count(*) as number 
                                FROM voiture AS V
                                LEFT JOIN contrat AS C ON V.id_voiture = C.id_voiture
                                Where V.action_voiture = '1'
                                and ((C.datedebut_contrat <='$date' and C.datefin_contrat >='$date'))
                                AND C.action_contrat != '0'";
                                
                            }else{
                                $query_numbervehicule = "SELECT count(*) as number FROM voiture Where action_voiture = '1' AND id_agence = $id_agence";

                                $query_numbervehicule_loue = "SELECT count(*) as number 
                                FROM voiture AS V
                                LEFT JOIN contrat AS C ON V.id_voiture = C.id_voiture
                                Where V.action_voiture = '1'
                                AND V.id_agence = $id_agence
                                and ((C.datedebut_contrat <='$date' and C.datefin_contrat >='$date'))
                                AND C.action_contrat != '0'";
                            }
                            $result_numbervehicule = mysqli_query($conn, $query_numbervehicule);
							$vehicule = mysqli_fetch_array($result_numbervehicule);
                            $result_numbervehicule_loue = mysqli_query($conn, $query_numbervehicule_loue);
							$vehicule_loue = mysqli_fetch_array($result_numbervehicule_loue);
                            $vehiculeloué = $vehicule_loue['number'];
							$vehicule_loue = 100 * $vehicule_loue['number'] / $vehicule['number'];
							echo round($vehicule_loue);
                            ?>
                        />
                        <input type=hidden id="vehicule_dispo" value=
                            <?php 
								$vehicule_dispo = $vehicule['number'] - $vehiculeloué;
								$vehicule_dispo = 100 * $vehicule_dispo / $vehicule['number'];
								echo round($vehicule_dispo);
                            ?>
                        />
					</div>
				</div>
			</div>
            
			<div class="col-2 col-lg-4">
                <div class="d-flex align-items-center">
					<div>
						<div class="text-blackbold">Nombre de location/mois</div>
					</div>
				</div>
                <p class="mb-2"></p>
                <div style="height:185px;" class="card radius-3">
                    <div id="chartcourbe">
                        <?php 
                            function Nbrelocation($month){
                                global $conn;
                                $id_agence = $_SESSION['Agence'];
                                $year = date('Y');
                                if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                                    $query_numbercontrat = "SELECT id_contrat FROM contrat 
                                    WHERE action_contrat = '1'
                                    AND ((datedebut_contrat <= '$year-$month-01' AND datefin_contrat >= '$year-$month-01' )
                                    OR (datedebut_contrat <= '$year-$month-31' AND datefin_contrat >= '$year-$month-31' ) 
                                    OR (datedebut_contrat >= '$year-$month-01' AND datefin_contrat <= '$year-$month-31' ))
                                    ORDER BY id_contrat";
                                }else{
                                    $query_numbercontrat = "SELECT id_contrat FROM contrat 
                                    WHERE action_contrat = '1'
                                    AND id_agence = $id_agence
                                    AND ((datedebut_contrat <= '$year-$month-01' AND datefin_contrat >= '$year-$month-01' )
                                    OR (datedebut_contrat <= '$year-$month-31' AND datefin_contrat >= '$year-$month-31' ) 
                                    OR (datedebut_contrat >= '$year-$month-01' AND datefin_contrat <= '$year-$month-31' ))
                                    ORDER BY id_contrat";
                                }
                                $query_numbercontrat = mysqli_query($conn, $query_numbercontrat);
                                return $numbercontrat = mysqli_num_rows($query_numbercontrat);
                            }
                        ?>
						<input type=hidden id="janvier" value=<?php echo Nbrelocation('01');?>/>

                        <input type=hidden id="février" value=<?php echo Nbrelocation('02');?>/>

                        <input type=hidden id="mars" value=<?php echo Nbrelocation('03');?>/>

                        <input type=hidden id="avril" value=<?php echo Nbrelocation('04');?>/>

                        <input type=hidden id="mai" value=<?php echo Nbrelocation('05');?>/>

                        <input type=hidden id="juin" value=<?php echo Nbrelocation('06');?>/>

                        <input type=hidden id="juillet" value=<?php echo Nbrelocation('07');?>/>

                        <input type=hidden id="aout" value=<?php echo Nbrelocation('08');?>/>

                        <input type=hidden id="septembre" value=<?php echo Nbrelocation('09');?>/>

                        <input type=hidden id="octobre" value=<?php echo Nbrelocation('10');?>/>

                        <input type=hidden id="novembre" value=<?php echo Nbrelocation('11');?>/>

                        <input type=hidden id="decembre" value=<?php echo Nbrelocation('12');?>/>
					</div>
                </div>
			</div>

            <div class="col-2 col-lg-4">
                <div class="d-flex align-items-center">
					<div>
						<div class="text-blackbold">Chiffre d'affiare/mois</div>
					</div>
				</div>
                <p class="mb-2"></p>
                <div style="height:185px;" class="card radius-3">
                    <div id="chiffreaffaire">
                        <?php 
                            function Chiffreaffaire($month){
                                global $conn;
                                $id_agence = $_SESSION['Agence'];
                                $year = date('Y');
                                if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                                    $query_chiffreaffaire = "SELECT SUM(prix_contrat)/1000 AS number FROM contrat 
                                    WHERE action_contrat = '1'
                                    AND datefin_contrat BETWEEN '$year-$month-01' AND LAST_DAY('$year-$month-01')
                                    ORDER BY id_contrat";
                                }else{
                                    $query_chiffreaffaire = "SELECT SUM(prix_contrat)/1000 AS number FROM contrat 
                                    WHERE action_contrat = '1'
                                    AND id_agence = $id_agence
                                    AND datefin_contrat BETWEEN '$year-$month-01' AND LAST_DAY('$year-$month-01')
                                    ORDER BY id_contrat";
                                }
                                $query_chiffreaffaire = mysqli_query($conn, $query_chiffreaffaire);
                                $chiffreaffaire = mysqli_fetch_row($query_chiffreaffaire);
                                if($chiffreaffaire[0] == NULL){
                                    return 0;
                                }else{
                                    return $chiffreaffaire[0];
                                }  
                            }
                        ?>
						<input type=hidden id="chiffrejanvier" value=<?php echo Chiffreaffaire('01');?>/>

                        <input type=hidden id="chiffrefévrier" value=<?php echo Chiffreaffaire('02');?>/>

                        <input type=hidden id="chiffremars" value=<?php echo Chiffreaffaire('03');?>/>

                        <input type=hidden id="chiffreavril" value=<?php echo Chiffreaffaire('04');?>/>

                        <input type=hidden id="chiffremai" value=<?php echo Chiffreaffaire('05');?>/>

                        <input type=hidden id="chiffrejuin" value=<?php echo Chiffreaffaire('06');?>/>

                        <input type=hidden id="chiffrejuillet" value=<?php echo Chiffreaffaire('07');?>/>

                        <input type=hidden id="chiffreaout" value=<?php echo Chiffreaffaire('08');?>/>

                        <input type=hidden id="chiffreseptembre" value=<?php echo Chiffreaffaire('09');?>/>

                        <input type=hidden id="chiffreoctobre" value=<?php echo Chiffreaffaire('10');?>/>

                        <input type=hidden id="chiffrenovembre" value=<?php echo Chiffreaffaire('11');?>/>

                        <input type=hidden id="chiffredecembre" value=<?php echo Chiffreaffaire('12');?>/>
                    </div>
                </div>
			</div>

            <div class="col-2 col-lg-12">
                <div class="d-flex align-items-center">
					<div>
						<div class="text-blackbold">Locations du jour</div>
					</div>
				</div>
                <p class="mb-2"></p>
                <div style="height:auto;" class="card radius-3">
                    <?php
                        $value = '<table class="table table-hover">
                            <tr style="height:40px; vertical-align: center;">
                                <th class="border-top-0">Nom & prénom du client</th>
                                <th class="border-top-0">Date de retour</th>
                                <th class="border-top-0">Voiture</th>
                                <th class="border-top-0">Immatriculation</th>
                                <th class="border-top-0">Montant</th>
                            </tr>';
                        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                            $query = "SELECT *
                            FROM voiture as V 
                            left JOIN marque_voiture as M on V.id_marquemodel= M.id_marquevoiture
                            left JOIN contrat as C on V.id_voiture = C.id_voiture
                            left JOIN client as CL on C.id_client = CL.id_client
                            WHERE C.action_contrat = '1'
                            AND ((C.datedebut_contrat <= DATE(NOW()) and C.datefin_contrat >= DATE(NOW())))
                            ORDER BY C.id_contrat";
                        }else{
                            $query = "SELECT *
                            FROM voiture as V 
                            left JOIN marque_voiture as M on V.id_marquemodel= M.id_marquevoiture
                            left JOIN contrat as C on V.id_voiture = C.id_voiture
                            left JOIN client as CL on C.id_client = CL.id_client
                            WHERE C.action_contrat = '1'
                            AND C.id_agence = $id_agence
                            AND ((C.datedebut_contrat <= DATE(NOW()) and C.datefin_contrat >= DATE(NOW())))
                            ORDER BY C.id_contrat";
                        }
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            $value .= '<tr>
                                <td>' . $row['nom_client'] . '</td>
                                <td>' . $row['datefin_contrat'] . '</td>
                                <td>' . $row['marque'] . " " .$row['model'] . '</td>
                                <td>' . $row['pimm_voiture'] . '</td>
                                <td>' . $row['prix_contrat']/1000 . " DT". '</td>
                            </tr>';
                        }
                        $value .= '</table>';
                        echo $value;
                    ?>
                </div>
            </div>
		</div>
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
                                        <input type="radio" id="voitureavecclim" name="voitureclim" value="1" checked>
                                        <label for="voitureavecclim">OUI</label>
                                    </div>
                                    <div>
                                        <input type="radio" id="voituresansclim" name="voitureclim" value="0">
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
                            if($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1"){
                            include_once('Gestion_location/inc/connect_db.php');
                            $query = "SELECT * FROM agence WHERE id_agence != '0' AND action_agence = '1' ORDER BY nom_agence ASC";
                            $result = mysqli_query($conn, $query);
                            ?>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Agence<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select name="voitureagence" id="voitureagence"
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
                            <?php
                            }
                            ?>
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
        <!-- Model ajout Client -->
        <div class="modal fade" id="Registration-Client" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #D71218;">
                        <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Client</h5>
						<button class="button-close" id="btn-close-x">X</button>
					</div>
                    <div class="modal-body">
                        <p id="message_client"></p>
                        <form id="clientForm" autocomplete="off" class="form-horizontal form-material">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Nom<span class="text-danger">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" id="clientName" class="form-control p-0 border-0">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Email<span class="text-danger">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="email" id="clientEmail" class="form-control p-0 border-0">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Téléphone<span class="text-danger">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" id="clientPhone" class="form-control p-0 border-0">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Adresse<span class="text-danger">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" id="clientAdresse" class="form-control p-0 border-0">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">CIN<span class="text-danger">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="file" id="clientCIN" class="form-control p-0 border-0">
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Permis<span class="text-danger">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="file" id="clientPermis" class="form-control p-0 border-0">
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
    					<button class="button-close" id="btn-close-x">X</button>
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
    					<button class="button-close" id="btn-close-x">X</button>
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

        <!-- Model ajout MarqueVoiture -->
        <div class="modal fade" id="Registration-MarqueVoiture" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background: #D71218;">
                        <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Marque</h5>
						<button class="button-close" id="btn-close-x">X</button>
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
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Prix Location<span class="text-danger">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" id="voitureprix" placeholder="70000" class="form-control p-0 border-0">
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

<?php
include('Gestion_location/inc/footer.php')
?>

</body>

</html>