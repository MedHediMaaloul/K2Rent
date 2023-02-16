<footer class="page-footer">Copyright © <?php echo date("Y"); ?>. All rights reserved.</footer>

<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>
<!--plugins-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/plugins/highcharts/js/highcharts.js"></script>
<script src="assets/plugins/highcharts/js/exporting.js"></script>
<script src="assets/plugins/highcharts/js/variable-pie.js"></script>

<script src="assets/plugins/highcharts/js/highcharts-more.js"></script>
<script src="assets/plugins/highcharts/js/variable-pie.js"></script>
<script src="assets/plugins/highcharts/js/solid-gauge.js"></script>
<script src="assets/plugins/highcharts/js/highcharts-3d.js"></script>
<script src="assets/plugins/highcharts/js/cylinder.js"></script>
<script src="assets/plugins/highcharts/js/funnel3d.js"></script>
<script src="assets/plugins/highcharts/js/exporting.js"></script>

<script src="assets/plugins/highcharts/js/export-data.js"></script>
<script src="assets/plugins/highcharts/js/accessibility.js"></script>
<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>

<script src="assets/js/app.js"></script>
<script src="app.js"></script>
<script src="assets/js/myjs.js"></script>
<script src="https://unpkg.com/feather-icons"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 
<!--Calendar-->
<script src="assets/js/index.global.js"></script>
<script>
	feather.replace()
</script>
<script>
	$(document).ready(function() {
		$('#example').DataTable();
	  } );
	  
	$(document).ready(function() {
		var table = $('#example2').DataTable( {
			lengthChange: false,
			buttons: [ 'copy', 'excel', 'pdf', 'print']
		} );
	 
		table.buttons().container()
			.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
	} );
</script>
<script>
	function startTime() {
    	var today = new Date();
    	var h = today.getHours();
    	var m = today.getMinutes();
    	var s = today.getSeconds();
    	m = checkTime(m);
    	s = checkTime(s);
    	document.getElementById('time').innerHTML =
    	h + ":" + m + ":" + s;
    	var t = setTimeout(startTime, 500);
	}
	function checkTime(i) {
    	if (i < 10) {i = "0" + i};
    	return i;
	}
</script>
<script>
	new PerfectScrollbar('.chat-list');
	new PerfectScrollbar('.chat-content');
	new PerfectScrollbar('.customers-list');
	new PerfectScrollbar('.store-metrics');
	new PerfectScrollbar('.product-list');
	new PerfectScrollbar('.dashboard-top-countries');
	new PerfectScrollbar('.best-selling-products');
	new PerfectScrollbar('.recent-reviews');
	new PerfectScrollbar('.support-list');
</script>
</body>
</html>

    
