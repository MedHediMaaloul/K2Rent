<?php
session_start();
include('Gestion_location/inc/header_sidebar.php');
?>
<div class="page-wrapper">
	<div class="page-content">
		<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb mb-0 p-0">
					<div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Notifications</div>
					<div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;" aria-current="page">Liste des notifications</div>
				</ol>
			</nav>
            <div class="ms-auto">
                <div id="div1">
                    <div class="box">
                        <select id="search_entretien" name="search_entretien" onchange="view_notification_record()" required>
                            <option value="0">Tous</option>
                            <option value="1">Contrat crée</option>
                            <option value="2">Contrat qui prendront fin</option>
                        </select>
                    </div>
                </div>
			</div>
		</div>
		<div class="row">
			<!-- Liste des notifications -->
			<div class="table-responsive-xxl" id="notification-list"></div>
			<!-- End Liste des notifications -->
		</div>
	</div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>

</body>
</html>