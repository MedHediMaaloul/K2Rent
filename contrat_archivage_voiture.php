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
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Contrat</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste archivage des Contrats</div>
				</ol>
			</nav>
			<div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?" id="searchContratArchive"> 
                        <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                    </div>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des contrats -->
			<div class="table-responsive-xxl" id="contrat-archive"></div>
			<!-- end  Liste des contrats -->
		</div>
	</div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>

</body>
</html>