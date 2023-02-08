<?php
include("Gestion_location/inc/connect_pdo.php");
session_start();
$id_role = $_SESSION['Role'];
$id_agence = $_SESSION['Agence'];
$name_agence = $_SESSION['NomAgence'];
global $conn;
$date = date('d/m/Y');
$datetime = date('d/m/Y_His');
$filname = "Stock_Voiture_K2Rent_$datetime";
if($id_role == 2){
    $filname = "Stock_Voiture_K2Rent_".$name_agence."_".$datetime;
}

header('Content-Encoding: UTF-8');
header('Content-type:application/csv; charset=UTF-8');
header("Content-Disposition: attachment; filename=$filname.csv");

$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
if($id_role == 2){
    $stmt = $bdd->prepare("SELECT *
    FROM voiture as V 
    LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
    LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
    LEFT JOIN agence AS A on V.id_agence = A.id_agence
    WHERE action_voiture = '1'
    AND V.id_agence = $id_agence
    ORDER BY id_voiture ASC");
}else{
    $stmt = $bdd->prepare("SELECT *
    FROM voiture as V 
    LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
    LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
    LEFT JOIN agence AS A on V.id_agence = A.id_agence
    WHERE action_voiture = '1'
    ORDER BY id_voiture ASC");
}
$stmt->execute();
$data = $stmt->fetchAll();
echo '"'."PIMM".'";"'."Marque".'";"'.utf8_decode("Modèle").'";"'."type_carburant".'";"'."boite_vitesse".'";"'."Localisation".'";"'."Disponibilite".'"
;';
echo "\n";
foreach($data as $d){
    $d->etat_voiture = "DISPONIBLE";
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $del = $bdd->prepare("SELECT * 
    FROM contrat As C,client AS CL
    WHERE C.id_voiture ='$d->id_voiture' 
    AND C.id_client = CL.id_client
    AND C.action_contrat = '1' 
    AND ((datedebut_contrat <= DATE(NOW()) and datefin_contrat >=DATE(NOW())))");
    $del->execute();
    $data1 = $del->fetchAll();
    if ($del->rowCount() > 0){
        $d->etat_voiture = "Loué";
        foreach($data1 as $d1){
            $d->nom_agence = $d1->nom_client." ( Téléphone : ".$d1->tel_client." )";
        }
    }
    echo '"'.utf8_decode($d->pimm_voiture).'";"'.utf8_decode($d->marque).'";"'.utf8_decode($d->model).'";"'.utf8_decode($d->boitevitesse_voiture).'";"'.utf8_decode($d->label_carburant).'";"'.utf8_decode($d->nom_agence).'";"'.utf8_decode($d->etat_voiture).'";'."\n";
}
?>
