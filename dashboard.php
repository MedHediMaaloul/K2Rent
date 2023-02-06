<?php
session_start();
$id_agence = $_SESSION['id_agence'];
include('Gestion_location/inc/header_sidebar.php');
include('Gestion_location/inc/connect_db.php');
?>

<?php
$apiKey = "1c029914f12a9f92473dac34056ac80f";
$cityId = "2470384";
$googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=fr&units=metric&APPID=" . $apiKey;

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);

curl_close($ch);
$data = json_decode($response);
$currentTime = date('Y-m-d'); // Date du jour


// $currentTime = time();
?>
<style>
.weather-icon {
    vertical-align: middle;
    margin-right: 20px;
}

.weather-forecast {
    color: #212121;
    font-size: 1.2em;
    font-weight: bold;
    margin: 20px 0px;
}

span.min-temperature {
    margin-left: 15px;
    color: #929292;
}

.time {
    line-height: 25px;
}
</style>

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
			<div class="col-2 col-lg-5">
				<div style="height:435px; background-color:#bedff7;" class="card radius-10">
					<div class="light">
						<p class="mb-4"></p>
						<center>
							<p class="mb-5"></p>
    						<h2>MÉTÉO <?php echo $data->name; ?></h2>
							<h4>Ville , Médenine , Tunisie</h4>
							<p class="mb-4"></p>
    						<div class="time">
								<div>MÉTÉO DU JOUR - <?php setlocale(LC_TIME, "fr_FR", "French"); echo utf8_encode(strftime("%A %d %B %G", strtotime($currentTime))); echo " - ". date('H')."H";?></div>
    						    <div><?php echo ucwords($data->weather[0]->description); ?></div>
    						</div>
    						<div class="weather-forecast">
    						    <img
    						        src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
    						        class="min-temperature"><?php echo $data->main->temp_max." "; ?>&deg;C</span>
    						</div>
    						<div class="time">
    						    <div>Humidité : <?php echo $data->main->humidity; ?> %</div>
    						    <div>Vent : <?php echo $data->wind->speed; ?> km/h</div>
    						</div>
						</center>
					</div>
				</div>
			</div>
			<div class="col-2 col-lg-7">
				<div style="height:435px;" class="card radius-10">
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
			<div class="col-2 col-lg-6">
				<?php
				$apikey = 'AIzaSyBS1I5ulR-2sQLaX1fpPXqkImsD4MrcGRc';
				$videoId = 'QO4qI6z7XvE';
			    $googleApiUrl = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoId . '&key=' . $apikey . '&part=snippet';

				$ch = curl_init();

			    curl_setopt($ch, CURLOPT_HEADER, 0);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
			    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			    curl_setopt($ch, CURLOPT_VERBOSE, 0);
			    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			    $response = curl_exec($ch);

			    curl_close($ch);

			    $data = json_decode($response);

			    $value = json_decode(json_encode($data), true);

			    $title = $value['items'][0]['snippet']['title'];
			    $description = $value['items'][0]['snippet']['description'];
				?>
				<div style="height:435px;" class="card radius-10">
					<div>
					    <div id="videoDiv">
					        <iframe id="iframe" style="width: 100%; height: 435px" src="//www.youtube.com/embed/<?php echo $videoId; ?>"
							data-autoplay-src="//www.youtube.com/embed/<?php echo $videoId; ?>?autoplay=1"></iframe>
					    </div>
					</div>
				</div>
			</div>
			<div class="col-2 col-lg-6">
				
			</div>
		</div>
	</div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>

</body>

</html>