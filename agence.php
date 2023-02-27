<?php
session_start();
if ((($_SESSION['Role']) == "0")) {
    include('Gestion_location/inc/header_sidebar.php');
} else {
    header("Location:login.php");
}
?>
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Agence</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des agences</div>
				</ol>
			</nav>
			<div class="ms-auto">

                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchAgence"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
                <div id="div2">
                    <button class="btn btn-add-vert" id="show_form_agence" title="Ajouter une agence">Ajouter une agence <i class="bx bx-plus" style="color:white"></i></button>
                </div>
                <div id="div3">
                    <button class="btn btn-add-bleu" id="show_form_horaire_agence" title="Ajouter l'horaire">Ajouter Horaire Agence <i class="bx bx-plus" style="color:white"></i></button>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des agences -->
			<div class="table-responsive-xxl" id="agence-list"></div>
			<!-- end  Liste des agences -->
			<!-- Model suppression agence -->
			<div class="modal fade" id="deleteAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer l'agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous supprimer l'agence ?</p>
							<br>
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_delete_agence">Supprimer</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model suppression agence -->
            <!-- Model alert suppression succès -->
			<div class="modal fade" id="SuccessDeleteAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer l'agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deleteagence_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression succès -->
            <!-- Model alert suppression echec -->
			<div class="modal fade" id="EchecDeleteAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer l'agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deleteagence_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression echec -->
			<!-- Model suppression horaire agence -->
            
			<div class="modal fade" id="deleteAgenceHeur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="static"  >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer l'horaire</h5>
							<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous supprimer cet horaire ?</p>
							<br>
							<div style="float: right;">
                            	<button class="buttonvalidate" id="btn-delete-confirm-agence-heur">Supprimer</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model suppression horaire agence -->
            <!-- Model alert suppression succès -->
			<div class="modal fade" id="SuccessDeleteHorAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer l'horaire</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletehoragence_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression succès -->
            <!-- Model alert suppression echec -->
			<div class="modal fade" id="EchecDeleteHorAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer l'horaire</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deletehoragence_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression echec -->
			<!-- Model modification agence -->
			<div class="modal fade" id="updateAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier agence</h5>
                            <button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-agenceForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4"><input type="hidden" id="up_idagence"></div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Lieu<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="up_agenceLieu" placeholder="Agence adresse" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Email<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="email" id="up_agenceEmail" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Téléphone<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="up_agenceTel" placeholder=" 0213555" class="form-control p-0 border-0">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_update_agence">Modifier</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification agence -->
            <!-- Model alert modification succès -->
			<div class="modal fade" id="SuccessUpAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upagence_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
			<div class="modal fade" id="EchecUpAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upagence_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
            <!-- Model ajout agence -->
            <div class="modal fade" id="Registration-Agence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Agence</h5>
							<button class="button-close" id="btn-close-x">X</button>
						</div>
                        <div class="modal-body">
                            <p id="message"></p>
                            <form id="agenceForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Lieu<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="agenceLieu" class="form-control p-0 border-0" required>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Email<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="email" id="agenceEmail" placeholder="pascal@gmail.com" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Téléphone<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="agenceTel" placeholder="425183" class="form-control p-0 border-0">
                                    </div>
                                </div>
								<div class="form-group mb-2">
                                    <label class="col-md-12 p-0">Horaire</label>
                                    <div class="table-responsive">
                                        <table class="table" id="dynamic_field">
                                            <tr>
                                                <td>
                                                    <select class="jour" name="jour" id="jour1">
                                                        <option value="lundi">lundi</option>
                                                        <option value="mardi">mardi</option>
                                                        <option value="mercredi">mercredi</option>
                                                        <option value="jeudi">jeudi</option>
                                                        <option value="vendredi">vendredi</option>
                                                        <option value="samedi">samedi</option>
                                                        <option value="dimanche">dimanche</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="time" name="skill[]" id="fetch-heurdebut1" placeholder="date debut" class="form-control heur-list-debut">
                                                </td>
                                            	<td> 
													<input type="time" id="fetch-heurfin1" placeholder="date fin" class="form-control heur-list-fin" required>
                                            	</td>
                                                <td><button type="button" name="add" id="add" class="form-control btn btn-success" style="border-radius: 5px; padding: 5px; font-size: 8px !important;">+</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn-register-agence">Ajouter</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout agence -->
            <!-- Model alert ajout succès -->
			<div class="modal fade" id="SuccessAddAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addagence_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout succès -->
            <!-- Model alert ajout echec -->
			<div class="modal fade" id="EchecAddAgence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addagence_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout echec -->
            <!-- Model ajout horaire agence -->
            <div class="modal fade" id="Registration-Agence-Heur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Horaire Agence</h5>
							<button class="button-close" id="btn-close-x">X</button>
						</div>
                        <div class="modal-body">
                            <p id="message_heure"></p>
                            <form id="agenceFormHeur" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <?php
                                    include('Gestion_location/inc/connect_db.php');
                                    $query = "SELECT * FROM agence where id_agence != 0 AND action_agence = '1' ORDER BY id_agence ASC";
                                    $result = mysqli_query($conn, $query);
                                    ?>
                                    <label class="col-md-12 p-0"> Agence<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select id="IdAgence" name="IdAgence" placeholder="agence" class="form-control p-0 border-0" required>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                $i = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_agence'] . '">' . $i . ' - ' . $row['nom_agence'] . '</option>';
                                                    $i += 1;
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <label class="col-md-12 p-0"> Horaire<span class="text-danger">*</span></label>
                                    <div class="table-responsive">
                                        <table class="table" id="dynamic_fieldH">
                                            <tr>
                                                <td>
                                                    <select class="jourH" name="jourH" id="jourH">
                                                        <option value="lundi">lundi</option>
                                                        <option value="mardi">mardi</option>
                                                        <option value="mercredi">mercredi</option>
                                                        <option value="jeudi">jeudi</option>
                                                        <option value="vendredi">vendredi</option>
                                                        <option value="samedi">samedi</option>
                                                        <option value="dimanche">dimanche</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="time" name="skillH[]" id="fetch-heurdebutH" placeholder="date debut" class="form-control heur-list-debutH">
                                                </td>
                                                <td> 
													<input type="time" id="fetch-heurfinH" placeholder="date fin" class="form-control heur-list-finH" required>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn-register-agence-heur">Ajouter</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout horaire agence -->
            <!-- Model alert ajout succès -->
			<div class="modal fade" id="SuccessAddAgence-Heur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Horaire Agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addagencehoraire_success"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout succès -->
            <!-- Model alert ajout echec -->
			<div class="modal fade" id="EchecAddAgence-Heur" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Horaire Agence</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="addagencehoraire_echec"></center>
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

<script>
$(document).ready(function() {
    var i = 1;
    $('#add').click(function() {
        // alert('cvbcvb');
        i++;
        $('#dynamic_field').append('<tr id="row' + i +'"> <td> <select class="jour"  name="jour" id="jour' + i +
            '" ><option value="lundi">lundi</option><option value="mardi">mardi</option> <option value="mercredi">mercredi </option> <option value="jeudi">jeudi</option><option value="vendredi">vendredi</option><option value="samedi">samedi</option><option value="dimanche">dimanche  </option>   </select></td>    <td> <input "name="skill[]"  type="time" id="fetch-heurdebut' +
            i +
            '" placeholder="designation" class="form-control heur-list-debut" required></td><td> <input type="time" id="fetch-heurfin' +
            i +
            '" placeholder="" class="form-control heur-list-fin" required></td><td><button type="button" name="remove" id="' +
            i + '" class="form-control btn btn-danger btn_remove" style="border-radius: 5px; padding: 5px; font-size: 8px !important;">X</button></td></tr>');
    });
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
    });
});
</script>

</body>
</html>