<?php

include("Gestion_location/inc/connect_db.php");

global $conn;

if (isset($_GET["id_contrat_fin"])){
    $query = "UPDATE contrat SET view_fin = '0' WHERE id_contrat = ".$_GET['id_contrat_fin'];
    if (mysqli_query($conn, $query)) {
		header('Location: fpdf/contratlocation.php?N='.$_GET['id_contrat_fin']); 
    } 	
} 
if (isset($_GET["id_contrat_create"])){
	$query = "UPDATE contrat SET view_create = '0' WHERE id_contrat = ".$_GET['id_contrat_create'];
    if (mysqli_query($conn, $query)) {
		header('Location: fpdf/contratlocation.php?N='.$_GET['id_contrat_create']); 
    }
}  
?>