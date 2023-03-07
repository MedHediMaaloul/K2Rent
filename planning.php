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
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Planning</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Planning</div>
				</ol>
			</nav>
            </div> 
            <!-- calendrier   -->
<div id='calendar'>
</div>
            <!-- end calendrier   -->

        <!-- modal contrat list -->
        <div class="modal fade" id="ContratListePopup"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="min-height: 440px;">
                        <div class="modal-header"  style="background-color: #D71218;" >
                            <h5 class="modal-title" style="color: white;" id="ModalTitleContratByDay"></h5>
    						<button class="button-close" id="btn-close-x"><img src="assets/images/close_ring.png" alt=""></button>
                        </div>
                        <div class="modal-body" id="PlanningContratDayListe">			

                                </div>
                    </div>
                </div>
            </div>
        <!-- end modal contrat list -->

<?php
include('Gestion_location/inc/footer.php')
?>
</body>
</html>