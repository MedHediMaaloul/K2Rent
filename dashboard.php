<?php
session_start();
$id_agence = $_SESSION['id_agence'];
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
			<div class="col-2 col-lg-7">
				<div style="height:360px;" class="card radius-10">
					<div class="light">
						<div style="width:auto;" class="calendar">
        					<div class="calendar-header">
        					    <span class="month-picker" id="month-picker">February</span>
        					    <div class="year-picker">
        					        <span class="year-change" id="prev-year">
        					            <pre><</pre>
        					        </span>
        					        <span id="year">2021</span>
        					        <span class="year-change" id="next-year">
        					            <pre>></pre>
        					        </span>
        					    </div>
        					</div>
        					<div class="calendar-body">
        					    <div class="calendar-week-day">
        					        <div>Di</div>
        					        <div>Lu</div>
        					        <div>Ma</div>
        					        <div>Me</div>
        					        <div>Je</div>
        					        <div>Ve</div>
        					        <div>Sa</div>
        					    </div>
        					    <div class="calendar-days"></div>
        					</div>
        					<div class="month-list"></div>
    					</div>
					</div>
				</div>
			</div>
			<div class="col-2 col-lg-5">
				<div style="height:435px;" class="card radius-10">
					<div class="card-body">
						<p class="mb-4"></p>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>

</body>

</html>