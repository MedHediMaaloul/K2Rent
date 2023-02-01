<?php
session_start();
if ((($_SESSION['Role']) == "superadmin")) {
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
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Utilisateur</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des utilisateurs</div>
				</ol>
			</nav>
			<div class="ms-auto">

                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchUser"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
                <div id="div2">
                    <button class="btn btn-add-vert" id="show_form_user" title="Ajouter un utilisateur">Ajouter un utilisateur<i class="bx bx-plus" style="color:white"></i></button>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des utilisateurs -->
			<div class="table-responsive-xxl" id="user-list"></div>
			<!-- end  Liste des utilisateurs -->
			<!-- Model suppression utilisateur -->
			<div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer utilisateur</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p>Voulez-vous supprimer l'utilisateur ?</p>
							<br>
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_delete_user">Supprimer</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model suppression utilisateur -->
            <!-- Model alert suppression succès -->
			<div class="modal fade" id="SuccessDeleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer utilisateur</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deleteuser_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression succès -->
            <!-- Model alert suppression echec -->
			<div class="modal fade" id="EchecDeleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Supprimer utilisateur</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="deleteuser_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert suppression echec -->
			<!-- Model modification utilisateur -->
			<div class="modal fade" id="updateUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Utilisateur</h5>
                            <button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div class="modal-body">
                            <p id="up_message"></p>
                            <form id="up-userForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4"><input type="hidden" id="up_iduser"></div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Nom<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="up_userName" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Login<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="up_userLogin" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Mot de passe<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="password" id="up_userPassword" value="*****" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Email</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="up_userEmail" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Etat<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select name="duree" id="updateuseretat" class="form-control p-0 border-0">
                                            <option value="T">Active</option>
                                            <option value="F">désactiver</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
							<div style="float: right;">
								<button class="buttonvalidate" id="btn_update_user">Modifier</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model modification utilisateur -->
            <!-- Model alert modification succès -->
			<div class="modal fade" id="SuccessUpUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Utilisateur</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upuser_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification succès -->
            <!-- Model alert modification echec -->
			<div class="modal fade" id="EchecUpUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Modifier Utilisateur</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="upuser_echec"></center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert modification echec -->
            <!-- Model ajout utilisateur -->
            <div class="modal fade" id="Registration-User" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background: #D71218;">
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Utilisateur</h5>
							<button class="button-close" id="btn-close-x">X</button>
						</div>
                        <div class="modal-body">
                            <p id="message"></p>
                            <form id="userForm" autocomplete="off" class="form-horizontal form-material">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Nom<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="userName" class="form-control p-0 border-0" required>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Login<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="userLogin" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Mot de passe<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="password" id="userPassword" placeholder="********" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Email</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" id="userEmail" placeholder="k2location@k2group.fr" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                
								<div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Rôle<span class="text-danger">*</span></label>
                                    <select id="roletype" name="roletype" onchange="selectrole(this.value)" placeholder="roletype" class="form-control p-0 border-0" required="">
                                        <option value="Selectionner" disabled selected> Selectionner Le Role</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Agent</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4" id="cont_UserAgence" style="display:none">
                                    <?php
                                    include('Gestion_location/inc/connect_db.php');
                                    $query = "SELECT * FROM agence where action_agence != 0 AND id_agence != 0 ORDER BY id_agence ASC";
                                    $result = mysqli_query($conn, $query);
                                    ?>
                                    <label class="col-md-12 p-0">Agence<span class="text-danger">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <select id="UserAgence" name="UserAgence" placeholder="agence" class="form-control p-0 border-0" required>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_agence'] . '">' . $row['id_agence'] . ' - ' . $row['nom_agence'] . '</option>';
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
								<button class="buttonvalidate" id="btn-register-user">Ajouter</button>
                            	<button class="buttonechec" id="btn-close">Annuler</button>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model ajout utilisateur -->
            <!-- Model alert ajout succès -->
			<div class="modal fade" id="SuccessAddUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Utilisateur</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circlechecked">
                                <i class="bx bx-check"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="adduser_success"></center>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Model alert ajout succès -->
            <!-- Model alert ajout echec -->
			<div class="modal fade" id="EchecAddUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="exampleModalLabel">Ajouter Utilisateur</h5>
    						<button class="button-close" id="btn-close-x">X</button>
                        </div>
                        <div>
                            <div class="circleerror">
                                <i class="bx bx-x"></i>
                            </div>
                            <div style="font-size:20px; margin:80px;">
                                <center id="adduser_echec"></center>
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