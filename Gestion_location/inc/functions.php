<?php

use PhpMyAdmin\Console;

session_start();
require_once('connect_db.php');

// Message des alertes

$msg_echec = "Vous avez rencontré un problème lors de";
$msg_success = "avec succés !";
$msg_insert_succés = "a été ajouté $msg_success";
$msg_insert_echec = "$msg_echec l'ajout de";
$msg_update_succés = "a été mis à jour $msg_success";
$msg_update_echec = "$msg_echec la mise à jour de";
$msg_delete_succés = "a été supprimé $msg_success";
$msg_delete_echec = "$msg_echec la suppression de";
// Gestion Voiture Loué

function disponibilite_Vehicule($id_voiture)
{
    global $conn;
    $query = "SELECT * 
                FROM contrat 
                WHERE id_voiture ='$id_voiture' 
                AND action_contrat = '1' 
                AND ((datedebut_contrat <= DATE(NOW()) and datefin_contrat >=DATE(NOW())))";
    $result = mysqli_query($conn, $query);
    $nb_res = mysqli_num_rows($result);

    $queryEntretien = "SELECT * 
                        FROM entretien_voiture
                        WHERE id_voiture ='$id_voiture' 
                        AND (datedebut_entretien <= DATE(NOW()) and datefin_entretien >= DATE(NOW()))
                        AND action_entretien = '1'";
    $result_entretien = mysqli_query($conn, $queryEntretien);
    $nb_res_entretien = mysqli_num_rows($result_entretien);

    if ($nb_res == 0) {
        if ($nb_res_entretien != 0) {
            return "En entretien";
        } else {
            return "Disponible";
        }
    } else {
        return "Loué";
    }
}
function localisation_Vehicule($id_voiture)
{
    global $conn;
    $query = "SELECT CL.nom_client,CL.tel_client 
                FROM contrat As C,client AS CL
                WHERE C.id_voiture ='$id_voiture' 
                AND C.id_client = CL.id_client 
                AND C.action_contrat = '1' 
                AND (C.datedebut_contrat <= DATE(NOW()) and C.datefin_contrat >=DATE(NOW()))";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);
    
    $nom_client = $row[0]." ( Téléphone : ".$row[1]." )";
    return $nom_client;
}

//compress image 
function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    }
    imagejpeg($image, $destination, $quality);
}

// Login

function login(){
    global $conn;
    $erreurlogin = '';
    $erreurpassword = '';
    $erreur = '';
    $ressaye = '';
    if (empty($_POST['login'])) {
        $erreurlogin = "Login est obligatoire!";
    } else if (empty($_POST['password'])){
        $erreurpassword = "Mot de passe est obligatoire!";
    }else {
        $query = "SELECT * 
					FROM user AS U ,role_user AS R ,agence AS A
					WHERE U.role_user = R.id_roleuser
					AND U.id_agence = A.id_agence
					AND U.login_user='" . $_POST['login'] . "' and U.motdepasse_user='" . md5($_POST['password']) . "'";
        $result = mysqli_query($conn, $query);
        if ($row = mysqli_fetch_assoc($result)) {
			if($row['etat_user'] == "F"){
                echo json_encode (['status' => 'disable']);
			}else{
				$_SESSION['Nom'] = $row['nom_user'];
				$_SESSION['Login'] = $row['login_user'];
				$_SESSION['Role'] = $row['role_user'];
				$_SESSION['RoleLabel'] = $row['label_roleuser'];
				$_SESSION['Agence'] = $row['id_agence'];
				$_SESSION['NomAgence'] = $row['nom_agence'];
                echo json_encode (['status' => 'success']);
			}
        } else {
            echo json_encode (['status' => 'failed']);
        }
    }
}

// Notification 

function AssuranceNotification()
{
    global $conn;
    $id_agence = $_SESSION['Agence'];
    
    if (isset($_POST["view"])) {
        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query = "SELECT V.id_voiture,V.pimm_voiture,AV.id_assurancevoiture,AV.prix_assurance,AV.view_notif
            FROM assurance_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_assurance='1'
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))
            ORDER BY AV.view_notif DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM assurance_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_assurance='1'
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))";
        }else{
            $query = "SELECT V.id_voiture,V.pimm_voiture,AV.id_assurancevoiture,AV.prix_assurance,AV.view_notif
            FROM assurance_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_assurance='1'
            AND V.id_agence = $id_agence
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))
            ORDER BY AV.view_notif DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM assurance_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_assurance='1'
            AND V.id_agence = $id_agence
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))";
        }
        $result = mysqli_query($conn, $query);
        $result_total = mysqli_query($conn, $query_total);
        $row_total = mysqli_fetch_row($result_total);
        $number_notif = $row_total[0] - 4;
        $output = '';

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $str1 = "La validité de l'assurance du véhicule avec matricule ";
                $str2 = " se terminera bientôt";

                if ($row['view_notif'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }

                $output .= '
                    <li>
                        <div class="border-bottom border-dark p-3" style="'. $style .'">
                            <div class="text-secondary">
                                <a onClick="reply_click(this.id)" id="'.$row["id_voiture"].'" target="_blank" att="'.$row["id_voiture"].'" 
                                    href="update_notification.php?id_assurance_fin='.$row["id_assurancevoiture"].'">'.$str1.$row["pimm_voiture"].''.$str2.'</a>
                            </div>
                        </div>
                    </li>
                    <li class="divider"></li>';
            }
            if($number_notif > 0){
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_controle_papier.php">Voir Tous ('.$number_notif.' autres)</a></div>';
            }else{
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_controle_papier.php">Voir Tous</a></div>';
            }
        } else {
            $output .= '<li class="text-bold text-italic">Aucune notification trouvée</li>';
        }

        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query_count = "SELECT COUNT(*) AS count
                FROM assurance_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                WHERE V.action_voiture = '1'
                AND AV.etat_assurance='1'
                AND AV.view_notif = '1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))";
        }else{
            $query_count = "SELECT COUNT(*) AS count
                FROM assurance_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                WHERE V.action_voiture = '1'
                AND AV.etat_assurance='1'
                AND AV.view_notif = '1'
                AND V.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))";
        }
        $result_count = mysqli_query($conn, $query_count);
        $row = mysqli_fetch_row($result_count);
        $count = $row[0];
        $data = array(
            'notification_controle_assurance' => $output,
            'count_fin_assurance' => $count
        );
        echo json_encode($data);
    }
}

function VisiteTechniqueNotification()
{
    global $conn;
    $id_agence = $_SESSION['Agence'];
    
    if (isset($_POST["view"])) {
        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query = "SELECT V.id_voiture,V.pimm_voiture,AV.id_visitevoiture,AV.prix_visite,AV.view_notif
            FROM visite_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_visite='1'
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_visite, INTERVAL 0 DAY))
            ORDER BY AV.view_notif DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM visite_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_visite='1'
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_visite, INTERVAL 0 DAY))";
        }else{
            $query = "SELECT V.id_voiture,V.pimm_voiture,AV.id_visitevoiture,AV.prix_visite,AV.view_notif
            FROM visite_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_visite='1'
            AND V.id_agence = $id_agence
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_visite, INTERVAL 0 DAY))
            ORDER BY AV.view_notif DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM visite_voiture AS AV
            left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
            WHERE V.action_voiture = '1'
            AND AV.etat_visite='1'
            AND V.id_agence = $id_agence
            AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_visite, INTERVAL 0 DAY))";
        }
        $result = mysqli_query($conn, $query);
        $result_total = mysqli_query($conn, $query_total);
        $row_total = mysqli_fetch_row($result_total);
        $number_notif = $row_total[0] - 4;
        $output = '';

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $str1 = "La validité de visite technique du véhicule avec matricule ";
                $str2 = " se terminera bientôt";

                if ($row['view_notif'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }

                $output .= '
                    <li>
                        <div class="border-bottom border-dark p-3" style="'. $style .'">
                            <div class="text-secondary">
                                <a onClick="reply_click(this.id)" id="'.$row["id_voiture"].'" target="_blank" att="'.$row["id_voiture"].'" 
                                    href="update_notification.php?id_visite_fin='.$row["id_visitevoiture"].'">'.$str1.$row["pimm_voiture"].''.$str2.'</a>
                            </div>
                        </div>
                    </li>
                    <li class="divider"></li>';
            }
            if($number_notif > 0){
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_controle_papier.php">Voir Tous ('.$number_notif.' autres)</a></div>';
            }else{
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_controle_papier.php">Voir Tous</a></div>';
            }
        } else {
            $output .= '<li class="text-bold text-italic">Aucune notification trouvée</li>';
        }

        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query_count = "SELECT COUNT(*) AS count
                FROM visite_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                WHERE V.action_voiture = '1'
                AND AV.etat_visite='1'
                AND AV.view_notif = '1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_visite, INTERVAL 0 DAY))";
        }else{
            $query_count = "SELECT COUNT(*) AS count
                FROM visite_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                WHERE V.action_voiture = '1'
                AND AV.etat_visite='1'
                AND AV.view_notif = '1'
                AND V.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_visite, INTERVAL 0 DAY))";
        }
        $result_count = mysqli_query($conn, $query_count);
        $row = mysqli_fetch_row($result_count);
        $count = $row[0];
        $data = array(
            'notification_controle_visite' => $output,
            'count_fin_visite' => $count
        );
        echo json_encode($data);
    }
}

function ContratNotification()
{
    global $conn;
    $id_agence = $_SESSION['Agence'];
    
    if (isset($_POST["view"])) {
        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query = "SELECT C.id_contrat,C.datefin_contrat,C.view_fin,CL.nom_client,CL.email_client,CL.tel_client,CL.adresse_client
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client = CL.id_client
            WHERE C.action_contrat = '1'
            AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))
            ORDER BY C.view_fin DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client = CL.id_client
            WHERE C.action_contrat = '1'
            AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))";
        }else{
            $query = "SELECT C.id_contrat,C.datefin_contrat,C.view_fin,CL.nom_client,CL.email_client,CL.tel_client,CL.adresse_client
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client=CL.id_client
            WHERE C.action_contrat = '1'
            AND C.id_agence = $id_agence
            AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))
            ORDER BY C.view_fin DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client=CL.id_client
            WHERE C.action_contrat = '1'
            AND C.id_agence = $id_agence
            AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))";
        }
        $result = mysqli_query($conn, $query);
        $result_total = mysqli_query($conn, $query_total);
        $row_total = mysqli_fetch_row($result_total);
        $number_notif = $row_total[0] - 4;
        $output = '';

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $str1 = "Le contrat N° ";
                $str2 = " du client ";
                $str3 = " se terminera bientôt";

                if ($row['view_fin'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }

                $output .= '
                    <li>
                        <div class="border-bottom border-dark p-3" style="'. $style .'">
                            <div class="text-secondary">
                                <a onClick="reply_click(this.id)" id="'.$row["id_contrat"].'" target="_blank" att="'.$row["id_contrat"].'" 
                                    href="update_notification.php?id_contrat_fin='.$row["id_contrat"].'">'.$str1.$row["id_contrat"].''.$str2.''.$row["nom_client"] . '' . $str3 .'</a>
                            </div>
                        </div>
                    </li>
                    <li class="divider"></li>';
            }
            if($number_notif > 0){
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_contrat.php">Voir Tous ('.$number_notif.' autres)</a></div>';
            }else{
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_contrat.php">Voir Tous</a></div>';
            }
        } else {
            $output .= '<li class="text-bold text-italic">Aucune notification trouvée</li>';
        }

        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query_count = "SELECT COUNT(*) AS count
                FROM contrat AS C
                LEFT JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.view_fin = '1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))"; 
        }else{
            $query_count = "SELECT COUNT(*) AS count
                FROM contrat AS C
                LEFT JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.view_fin = '1'
                AND C.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))";
        }
        $result_count = mysqli_query($conn, $query_count);
        $row = mysqli_fetch_row($result_count);
        $count = $row[0];
        $data = array(
            'notification_fin_contrat' => $output,
            'count_fin_contrat' => $count
        );
        echo json_encode($data);
    }
}

function notification_create_contrat()
{
    global $conn;
    $id_agence = $_SESSION['Agence'];
    
    if (isset($_POST["view"])) {
        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query = "SELECT C.id_contrat,C.date_created_contrat,C.view_create,CL.nom_client
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client = CL.id_client
            WHERE C.action_contrat = '1'
            AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))
            ORDER BY C.view_create DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client = CL.id_client
            WHERE C.action_contrat = '1'
            AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))";
        }else{
            $query = "SELECT C.id_contrat,C.date_created_contrat,C.view_create,CL.nom_client
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client=CL.id_client
            WHERE C.action_contrat = '1'
            AND C.id_agence = $id_agence
            AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))
            ORDER BY C.view_create DESC
            LIMIT 4";

            $query_total = "SELECT COUNT(*)
            FROM contrat AS C
            left JOIN client AS CL ON C.id_client=CL.id_client
            WHERE C.action_contrat = '1'
            AND C.id_agence = $id_agence
            AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))";
        }
        $result = mysqli_query($conn, $query);
        $result_total = mysqli_query($conn, $query_total);
        $row_total = mysqli_fetch_row($result_total);
        $number_notif = $row_total[0] - 4;
        $output = '';

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $str1 = "Le client ";
                $str2 = " a crée un contrat le ";

                if ($row['view_create'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }

                $output .= '
                    <li>
                        <div class="border-bottom border-dark p-3" style="'. $style .'">
                            <div class="text-secondary">
                                <a onClick="reply_click(this.id)" id="'.$row["id_contrat"].'" target="_blank" att="'.$row["id_contrat"].'" 
                                    href="update_notification.php?id_contrat_create='.$row["id_contrat"].'">'.$str1.$row["nom_client"].''.$str2.''.$row["date_created_contrat"] .'</a>
                            </div>
                        </div>
                    </li>
                    <li class="divider"></li>';
            }
            if($number_notif > 0){
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_contrat.php">Voir Tous ('.$number_notif.' autres)</a></div>';
            }else{
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notification_contrat.php">Voir Tous</a></div>';
            }
        } else {
            $output .= '<li class="text-bold text-italic">Aucune notification trouvée</li>';
        }

        if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
            $query_count = "SELECT COUNT(*) AS count
                FROM contrat AS C
                LEFT JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.view_create = '1'
                AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))";
        }else{
            $query_count = "SELECT COUNT(*) AS count
                FROM contrat AS C
                LEFT JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.view_create = '1'
                AND C.id_agence = $id_agence
                AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))";
        }
        $result_count = mysqli_query($conn, $query_count);
        $row = mysqli_fetch_row($result_count);
        $count = $row[0];
        $data = array(
            'notification_create_contrat' => $output,
            'count_create_contrat' => $count
        );
        echo json_encode($data);
    }
}

function view_notification_record()
{
    global $conn;
    $id_agence = $_SESSION['Agence'];
    $search = $_POST['querytype'];
    $value = '<table class="table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Le contrat</th>
                <th>Client</th>
                <th>Date</th>
                <th>Etat</th>
            </tr>
        </thead>
		<tbody>';
        $i = 1;
        if($search == "0"){
            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_create = "SELECT C.id_contrat,C.date_created_contrat,C.view_create,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))
                ORDER BY C.view_create DESC";
            }else{
                $query_create = "SELECT C.id_contrat,C.date_created_contrat,C.view_create,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.id_agence = $id_agence
                AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))
                ORDER BY C.view_create DESC";
            }
            
            $result_create = mysqli_query($conn, $query_create);
            $i = 1;
            while ($row = mysqli_fetch_assoc($result_create)) {
                if ($row['view_create'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $etat = "CREATION";
                $class = "etat etatactif";
                $value .= '<tr style="'.$style.'">
		    		<td>' . $i . '</td>
		    		<td><a href="fpdf/contratlocation.php?N='.$row["id_contrat"].'">'."Contrat N°".$row["id_contrat"].'</a></td>
		    		<td>' . $row['nom_client'] . '</td>
                    <td>' . $row['date_created_contrat'] . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">' . $etat . '</div></center></td>
                </tr>';
                $i += 1;
            }

            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_fin = "SELECT C.id_contrat,C.datefin_contrat,C.view_fin,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))
                ORDER BY C.view_fin DESC";
            }else{
                $query_fin = "SELECT C.id_contrat,C.datefin_contrat,C.view_fin,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))
                ORDER BY C.view_fin DESC";
            }
            
            $result_fin = mysqli_query($conn, $query_fin);
            while ($row_fin = mysqli_fetch_assoc($result_fin)) {
                if ($row_fin['view_fin'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $etat = "Fin";
                $class = "etat etatinactif";
                $value .= '<tr style="'.$style.'">
		    		<td>' . $i . '</td>
		    		<td><a href="fpdf/contratlocation.php?N='.$row_fin["id_contrat"].'">'."Contrat N°".$row_fin["id_contrat"].'</a></td>
		    		<td>' . $row_fin['nom_client'] . '</td>
                    <td>' . $row_fin['datefin_contrat'] . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">' . $etat . '</div></center></td>
                </tr>';
                $i += 1;
            }
        } else if ($search == "1"){

            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_create = "SELECT C.id_contrat,C.date_created_contrat,C.view_create,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))
                ORDER BY C.view_create DESC";
            }else{
                $query_create = "SELECT C.id_contrat,C.date_created_contrat,C.view_create,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.id_agence = $id_agence
                AND (C.date_created_contrat BETWEEN DATE_SUB(DATE(NOW()), INTERVAL 2 DAY) AND DATE_SUB(DATE(NOW()), INTERVAL -2 DAY))
                ORDER BY C.view_create DESC";
            }
            
            $result_create = mysqli_query($conn, $query_create);
            while ($row = mysqli_fetch_assoc($result_create)) {
                if ($row['view_create'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $etat = "CREATION";
                $class = "etat etatactif";
                $value .= '<tr style="'.$style.'">
                    <td>' . $i . '</td>
                    <td><a href="fpdf/contratlocation.php?N='.$row["id_contrat"].'">'."Contrat N°".$row["id_contrat"].'</a></td>
                    <td>' . $row['nom_client'] . '</td>
                    <td>' . $row['date_created_contrat'] . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">' . $etat . '</div></center></td>
                </tr>';
                $i += 1;
            }
        } else if ($search == "2"){

            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_fin = "SELECT C.id_contrat,C.datefin_contrat,C.view_fin,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))
                ORDER BY C.view_fin DESC";
            }else{
                $query_fin = "SELECT C.id_contrat,C.datefin_contrat,C.view_fin,CL.nom_client
                FROM contrat AS C
                left JOIN client AS CL ON C.id_client = CL.id_client
                WHERE C.action_contrat = '1'
                AND C.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(C.datefin_contrat, INTERVAL 7 DAY) AND DATE_SUB(C.datefin_contrat, INTERVAL 0 DAY))
                ORDER BY C.view_fin DESC";
            }
            
            $result_fin = mysqli_query($conn, $query_fin);
            while ($row_fin = mysqli_fetch_assoc($result_fin)) {
                if ($row_fin['view_fin'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $etat = "Fin";
                $class = "etat etatinactif";
                $value .= '<tr style="'.$style.'">
                    <td>' . $i . '</td>
                    <td><a href="fpdf/contratlocation.php?N='.$row_fin["id_contrat"].'">'."Contrat N°".$row_fin["id_contrat"].'</a></td>
                    <td>' . $row_fin['nom_client'] . '</td>
                    <td>' . $row_fin['datefin_contrat'] . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">' . $etat . '</div></center></td>
                </tr>';
                $i += 1;
            }
        }
        $value .= '</tbody>';
    echo $value;
}

function view_notification_controle_papier_record()
{
    global $conn;
    $id_agence = $_SESSION['Agence'];
    $search = $_POST['search_controle_papier'];
    $value = '<table class="table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Voiture</th>
                <th>Le papier</th>
                <th>Prix (DT)</th>
                <th>Date fin</th>
            </tr>
        </thead>
		<tbody>';
        $i = 1;
        if($search == "0"){
            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_create = "SELECT V.pimm_voiture,AV.date_fin_assurance,AV.view_notif,AV.prix_assurance,MV.marque,MV.model
                FROM assurance_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND AV.etat_assurance='1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))
                ORDER BY AV.view_notif DESC";
            }else{
                $query_create = "SELECT V.pimm_voiture,AV.date_fin_assurance,AV.view_notif,AV.prix_assurance,MV.marque,MV.model
                FROM assurance_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND AV.etat_assurance='1'
                AND V.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))
                ORDER BY AV.view_notif DESC";
            }
            
            $result_create = mysqli_query($conn, $query_create);
            while ($row = mysqli_fetch_assoc($result_create)) {
                if ($row['view_notif'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $value .= '<tr style="'.$style.'">
                    <td>' . $i . '</td>
                    <td>' .$row["pimm_voiture"]. " " .$row["marque"]. " " .$row["model"]. '</td>
                    <td>' . "Assurance" . '</td>
                    <td>' . $row['prix_assurance'] . '</td>
                    <td>' . $row['date_fin_assurance'] . '</td>
                </tr>';
                $i += 1;
            }

            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_fin = "SELECT V.pimm_voiture,VV.date_fin_visite,VV.view_notif,VV.prix_visite,MV.marque,MV.model
                FROM visite_voiture AS VV
                left JOIN voiture AS V ON VV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND VV.etat_visite='1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(VV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(VV.date_fin_visite, INTERVAL 0 DAY))
                ORDER BY VV.view_notif DESC";
            }else{
                $query_fin = "SELECT V.pimm_voiture,VV.date_fin_visite,VV.view_notif,VV.prix_visite,MV.marque,MV.model
                FROM visite_voiture AS VV
                left JOIN voiture AS V ON VV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND VV.etat_visite='1'
                AND V.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(VV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(VV.date_fin_visite, INTERVAL 0 DAY))
                ORDER BY VV.view_notif DESC";
            }
            
            $result_fin = mysqli_query($conn, $query_fin);
            while ($row_fin = mysqli_fetch_assoc($result_fin)) {
                if ($row_fin['view_notif'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $value .= '<tr style="'.$style.'">
                    <td>' . $i . '</td>
                    <td>' .$row_fin["pimm_voiture"]. " " .$row_fin["marque"]. " " .$row_fin["model"]. '</td>
                    <td>' . "Visite technique" . '</td>
                    <td>' . $row_fin['prix_visite'] . '</td>
                    <td>' . $row_fin['date_fin_visite'] . '</td>
                </tr>';
                $i += 1;
            }
        } else if ($search == "1"){

            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_create = "SELECT V.pimm_voiture,AV.date_fin_assurance,AV.view_notif,AV.prix_assurance,MV.marque,MV.model
                FROM assurance_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND AV.etat_assurance='1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))
                ORDER BY AV.view_notif DESC";
            }else{
                $query_create = "SELECT V.pimm_voiture,AV.date_fin_assurance,AV.view_notif,AV.prix_assurance,MV.marque,MV.model
                FROM assurance_voiture AS AV
                left JOIN voiture AS V ON AV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND AV.etat_assurance='1'
                AND V.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(AV.date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(AV.date_fin_assurance, INTERVAL 0 DAY))
                ORDER BY AV.view_notif DESC";
            }
            
            $result_create = mysqli_query($conn, $query_create);
            while ($row = mysqli_fetch_assoc($result_create)) {
                if ($row['view_notif'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $value .= '<tr style="'.$style.'">
                    <td>' . $i . '</td>
                    <td>' .$row["pimm_voiture"]. " " .$row["marque"]. " " .$row["model"]. '</td>
                    <td>' . "Assurance" . '</td>
                    <td>' . $row['prix_assurance'] . '</td>
                    <td>' . $row['date_fin_assurance'] . '</td>
                </tr>';
                $i += 1;
            }
        } else if ($search == "2"){

            if ($_SESSION['Role'] == "0" || $_SESSION['Role'] == "1") {
                $query_fin = "SELECT V.pimm_voiture,VV.date_fin_visite,VV.view_notif,VV.prix_visite,MV.marque,MV.model
                FROM visite_voiture AS VV
                left JOIN voiture AS V ON VV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND VV.etat_visite='1'
                AND (DATE(NOW()) BETWEEN DATE_SUB(VV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(VV.date_fin_visite, INTERVAL 0 DAY))
                ORDER BY VV.view_notif DESC";
            }else{
                $query_fin = "SELECT V.pimm_voiture,VV.date_fin_visite,VV.view_notif,VV.prix_visite,MV.marque,MV.model
                FROM visite_voiture AS VV
                left JOIN voiture AS V ON VV.id_voiture = V.id_voiture
                left JOIN marque_voiture AS MV ON V.id_marquemodel = MV.id_marquevoiture
                WHERE V.action_voiture = '1'
                AND VV.etat_visite='1'
                AND V.id_agence = $id_agence
                AND (DATE(NOW()) BETWEEN DATE_SUB(VV.date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(VV.date_fin_visite, INTERVAL 0 DAY))
                ORDER BY VV.view_notif DESC";
            }
            
            $result_fin = mysqli_query($conn, $query_fin);
            while ($row_fin = mysqli_fetch_assoc($result_fin)) {
                if ($row_fin['view_notif'] == 1) {
                    $style = 'background-color: #FEE2E9;';
                } else {
                    $style = 'background-color: #E8E8E8;';
                }
                $value .= '<tr style="'.$style.'">
                    <td>' . $i . '</td>
                    <td>' .$row_fin["pimm_voiture"]. " " .$row_fin["marque"]. " " .$row_fin["model"]. '</td>
                    <td>' . "Visite technique" . '</td>
                    <td>' . $row_fin['prix_visite'] . '</td>
                    <td>' . $row_fin['date_fin_visite'] . '</td>
                </tr>';
                $i += 1;
            }
        }
        $value .= '</tbody>';
    echo $value;
}

// Agence

function display_agence_record()
{
    global $conn;
    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Agence</th>
                <th>Lieu</th>
                <th>E-mail</th>
                <th>Téléphone</th>
                <th>Horaire</th>
                <th>Actions</th>
            </tr>
        </thead>
		<tbody>';

        $query = " SELECT * FROM agence
            WHERE id_agence != 0 
            AND action_agence = '1'";
        $result = mysqli_query($conn, $query);
        $i = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $agence = "K2 Rent ".$row['nom_agence'];
            $value .= '<tr>
				<td>' . $i . '</td>
                <td>' . $agence . '</td>
				<td>' . $row['nom_agence'] . '</td>
				<td>' . $row['email_agence'] . '</td>
				<td>' . $row['tel_agence'] . '</td>
                <td> <table class="table" style="border-radius: 6px !important; box-shadow: 2px 2px 2px 1px #00000029; margin-top: 10px; margin-bottom: 10px;">';

                $id_agence = $row['id_agence'];
                $queryhoraire = "SELECT * 
                                    FROM horaire_agence
                                    WHERE id_agence = $id_agence 
                                    ORDER BY jour_horaire ASC ";
                $resulthoraire = mysqli_query($conn, $queryhoraire);
                while ($horaire = mysqli_fetch_assoc($resulthoraire)) {
                    $value .= '<tr style="background-color: white;">
                        <td>' . $horaire['jour_horaire'] . '</td>
                        <td>' . $horaire['debut_horaire'] . ' / ' . $horaire['fin_horaire'] . '</td>
                        <td><a title="Supprimer l\'horaire" id="btn-delete-agence-heur" data-id4=' . $horaire['id_horaire'] . '><img src="assets/images/delete.png" style="margin-right: 0px; width:20px; height:20px;" ></a></div></td> 
                    </tr>';
                }
				$value .= '</table></td><td>
					<div class="btn-group" role="group">
                        <button type="button" title="Modifier l\'agence" class="btn" style="font-size: 2px;" id="btn-edit-agence" data-id=' . $row['id_agence'] . '>
                        <i class="lni lni-pencil-alt iconaction"></i></button> 
                        <button type="button" title="Supprimer l\'agence" class="btn" style="font-size: 2px;" id="btn-delete-agence" data-id1=' . $row['id_agence'] . '>
                        <i class="lni lni-trash iconaction"></i></button>
				  	</div>
				</td>
			</tr>';
            $i += 1;
        }
        $value .= '</tbody>';
    
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchAgence()
{
    global $conn;
    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Agence</th>
                <th>Lieu</th>
                <th>E-mail</th>
                <th>Téléphone</th>
                <th>Horaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';
    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        $sql = ("SELECT * 
                    FROM agence
                    WHERE id_agence != 0
                    AND action_agence = '1'
                    AND (nom_agence LIKE ('%" . $search . "%')       
                        OR email_agence LIKE ('%" . $search . "%')
                        OR tel_agence LIKE ('%" . $search . "%'))");
        $result = mysqli_query($conn, $sql);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $agence = "K2 Rent ".$row['nom_agence'];
                $value .= '<tr>
		    		<td>' . $i . '</td>
                    <td>' . $agence . '</td>
		    		<td>' . $row['nom_agence'] . '</td>
		    		<td>' . $row['email_agence'] . '</td>
		    		<td>' . $row['tel_agence'] . '</td>
                    <td> <table class="table" style="border-radius: 6px !important; box-shadow: 2px 2px 2px 1px #00000029; margin-top: 10px; margin-bottom: 10px;">';

                    $id_agence = $row['id_agence'];
                    $queryhoraire = "SELECT * 
                                    FROM horaire_agence
                                    WHERE id_agence = $id_agence 
                                    ORDER BY jour_horaire ASC ";
                    $resulthoraire = mysqli_query($conn, $queryhoraire);
                    while ($horaire = mysqli_fetch_assoc($resulthoraire)) {
		    		    $value .= '<tr style="background-color: white;">
                            <td>' . $horaire['jour_horaire'] . '</td>
                            <td>' . $horaire['debut_horaire'] . ' / ' . $horaire['fin_horaire'] . '</td>
                            <td><a title="Supprimer l\'horaire" id="btn-delete-agence-heur" data-id4=' . $horaire['id_horaire'] . '><img src="assets/images/delete.png" style="margin-right: 0px; width:20px; height:20px;" ></a></div></td> 
                        </tr>';
                    }
		    		$value .= '</table></td><td>
		    			<div class="btn-group" role="group">
                            <button type="button" title="Modifier l\'agence" class="btn" style="font-size: 2px;" id="btn-edit-agence" data-id=' . $row['id_agence'] . '>
                            <i class="lni lni-pencil-alt iconaction"></i></button> 
                            <button type="button" title="Supprimer l\'agence" class="btn" style="font-size: 2px;" id="btn-delete-agence" data-id1=' . $row['id_agence'] . '>
                            <i class="lni lni-trash iconaction"></i></button>
		    		  	</div>
		    		</td>
		    	</tr>';
                $i += 1;
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
		    	<td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_agence_record();
    }
}

function InsertAgence()
{
    global $conn;
    global $msg_insert_succés;
    global $msg_insert_echec;
    $errors = [];
    if (!empty($errors)) {
        echo json_encode(["error" => "Requête invalide", "data" => $errors]);
        return;
    }
    $agenceLieu = mysqli_real_escape_string($conn, $_POST['agenceLieu']);
    $agenceEmail = mysqli_real_escape_string($conn, $_POST['agenceEmail']);
    $agenceTele = mysqli_real_escape_string($conn, $_POST['agenceTel']);
    $JourListe = $_POST['JourListe'];
    $DateDebutListe = $_POST['DateDebutListe'];
    $DateFinListe = $_POST['DateFinListe'];
    $JourListe = explode(',', $JourListe);
    $DateDebutListe = explode(',', $DateDebutListe);
    $DateFinListe = explode(',', $DateFinListe);
    $count = count($JourListe);
    $sql_e = "SELECT * FROM agence WHERE nom_agence='$agenceLieu'";
    $res_e = mysqli_query($conn, $sql_e);
    if (mysqli_num_rows($res_e) > 0) {
        echo "<div class='text-echec'>Désolé ... l'agence $agenceLieu est déjà pris!</div>";
    } else {
        $query = "INSERT INTO 
            agence(nom_agence,email_agence,tel_agence) 
            VALUES ('$agenceLieu','$agenceEmail','$agenceTele')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            $query_get_max_id_agence = "SELECT max(id_agence) FROM agence";
            $result_get_max_id_agence = mysqli_query($conn, $query_get_max_id_agence);
            $row = mysqli_fetch_row($result_get_max_id_agence);
            $id_agence = $row[0];
            for ($i = 0; $i < $count; $i++) {
                if ($JourListe[$i] != "" && $DateDebutListe[$i] != "" && $DateFinListe[$i] != "") {
                    $query_insert_heur_list = "INSERT INTO horaire_agence(id_agence,jour_horaire,debut_horaire,fin_horaire)
                         VALUES ('$id_agence','$JourListe[$i]','$DateDebutListe[$i]','$DateFinListe[$i]') ";
                    $result_query_insert_heur_list = mysqli_query($conn, $query_insert_heur_list);
                }
            }
            echo "<div class='text-checked'>L'agence $agenceLieu $msg_insert_succés</div>";
        } else {
            echo "<div class='text-echec'>$msg_insert_echec l'agence</div>";
        }
    }
}

function InsertAgenceHeur()
{
    global $conn;
    global $msg_insert_succés;
    global $msg_insert_echec;
    $errors = [];
    if (!empty($errors)) {
        echo json_encode(["error" => "Requête invalide", "data" => $errors]);
        return;
    }
    $IdAgence = $_POST['IdAgence'];
    $jourH = $_POST['jourH'];
    $heurdebutH = $_POST['heurdebutH'];
    $heurfinH = $_POST['heurfinH']; 
    
    $query_verif = "SELECT * 
                        FROM horaire_agence
                        WHERE id_agence = '$IdAgence'
                        AND jour_horaire = '$jourH'";
    $result_verif = mysqli_query($conn, $query_verif);

    if(mysqli_num_rows($result_verif) == 0){
        $query = "INSERT INTO horaire_agence(id_agence,jour_horaire,debut_horaire,fin_horaire) 
        VALUES ('$IdAgence', '$jourH', '$heurdebutH', '$heurfinH')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<div class='text-checked'>L'horaire de l'agence $msg_insert_succés</div>";
        } else {
            echo "<div class='text-echec'>$msg_insert_echec l'horaire d'une agence</div>";
        }
    }else{
        echo "<div class='text-echec'>L'horaire de l'agence choisi est déja existe !</div>";
    }
      
}

function get_agence_record()
{
    global $conn;
    $AgenceId = $_POST['ClientID'];
    $query = "SELECT * FROM agence WHERE id_agence='$AgenceId'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $user_data = [];
        $user_data[0] = $row['id_agence'];
        $user_data[1] = $row['nom_agence'];
        $user_data[2] = $row['email_agence'];
        $user_data[3] = $row['tel_agence'];
    }
    echo json_encode($user_data);
}

function update_agence_value()
{
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;
    $date = date('Y-m-d H:i:s');
    if (!array_key_exists("up_idagence", $_POST)) {
        echo json_encode(["error" => "ID utilisateur manquant ", "data" => "ID utilisateur manquant"]);
        return;
    }
    $agence_id = $_POST["up_idagence"];
    $user_query = "SELECT * FROM  agence where id_agence = $agence_id";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    if (!$user) {
        echo json_encode(["error" => "Agence introuvable ", "data" => "user $agence_id not found."]);
        return;
    }
    $up_agenceLieu = $_POST["up_agenceLieu"];
    $up_agenceEmail = $_POST["up_agenceEmail"];
    $up_agenceTel = $_POST["up_agenceTel"];
    $update_query = "UPDATE agence SET 
        nom_agence='$up_agenceLieu',
        email_agence='$up_agenceEmail',
        tel_agence='$up_agenceTel',
        date_updated_agence='$date'
        WHERE id_agence = $agence_id";
    $update_result = mysqli_query($conn, $update_query);
    if ($update_result) {
        echo "<div class='text-checked'>L'gence $msg_update_succés</div>";
    }else{
        echo "<div class='text-echec'>$msg_update_echec l'agence !</div>";
    } 
}

function delete_agence_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;

    $date = date('Y-m-d H:i:s');
    $Del_ID = $_POST['Delete_AgenceID'];
    $query = "UPDATE agence SET action_agence = '0', date_updated_agence='$date' WHERE id_agence = '$Del_ID'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<div class='text-checked'>L'agence $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec l'agence !</div>";
    }
}

function delete_agence_heur_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;

    $Del_ID = $_POST['Delete_AgenceID'];
    $query = "DELETE FROM horaire_agence where id_horaire = '$Del_ID'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<div class='text-checked'>L'horaire de l'agence $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec l'horaire !</div>";
    }
}

//Utilisateur
function display_user_record()
{
    global $conn;
    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th class="border-top-0">#</th>
                <th class="border-top-0">Nom</th>
                <th class="border-top-0">Login</th>
                <th class="border-top-0">Email</th>
                <th class="border-top-0">Rôle</th>
                <th class="border-top-0">Lieu Agence</th>
                <th class="border-top-0">Etat</th>
                <th class="border-top-0">Actions</th>
            </tr>
        </thead>
		<tbody>';

    $query = "SELECT * FROM user
        LEFT JOIN agence ON user.id_agence = agence.id_agence
        LEFT JOIN role_user ON user.role_user = role_user.id_roleuser
        WHERE user.etat_user != 'S'
        ORDER BY user.role_user ASC";
    $result = mysqli_query($conn, $query);
    $i = 1;
    $agence ='';
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['etat_user'] == 'T'){
            $etat = "ACTIF";
            $class = "etat etatactif";
        } else{
            $etat = "INACTIF";
            $class = "etat etatinactif";
        }

        $agence ='K2 Rent';
        if ($row['role_user']=='2'){
            $agence = $row['nom_agence'];
        }

        $value .= '<tr>
			<td>' . $i . '</td>
            <td>' . $row['nom_user'] . '</td>
			<td>' . utf8_decode($row['login_user']) . '</td>
			<td>' . $row['email_user'] . '</td>
			<td>' . $row['label_roleuser'] . '</td>
			<td>' . $agence . '</td>
            <td style="height: 70px;"><center><div class="'.$class.'">' . $etat . '</div></center></td>
			<td>
				<div class="btn-group" role="group">
                    <button type="button" title="Modifier l\'utilisateur" class="btn" style="font-size: 2px;" id="btn-edit-user" data-id=' . $row['id_user'] . '>
                    <i class="lni lni-pencil-alt iconaction"></i></button>'; 
                    if ($row['role_user'] != '0') {
                        $value .= '<button type="button" title="Supprimer l\'utilisateur" class="btn" style="font-size: 2px;" id="btn-delete-user" data-id1=' . $row['id_user'] . '>
                        <i class="lni lni-trash iconaction"></i></button>';
                    }
                $value .= '</div>
			</td>
		</tr>';
        $i += 1;
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchUser()
{
    global $conn;
    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th class="border-top-0">#</th>
                <th class="border-top-0">Nom</th>
                <th class="border-top-0">Login</th>
                <th class="border-top-0">Email</th>
                <th class="border-top-0">Rôle</th>
                <th class="border-top-0">Lieu Agence</th>
                <th class="border-top-0">Etat</th>
                <th class="border-top-0">Actions</th>
            </tr>
        </thead>
		<tbody>';

    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        $query = "SELECT * FROM user
            LEFT JOIN agence ON user.id_agence = agence.id_agence
            LEFT JOIN role_user ON user.role_user = role_user.id_roleuser
            WHERE user.etat_user != 'S'
            AND (nom_user LIKE ('%" . $search . "%')       
                OR login_user LIKE ('%" . $search . "%')       
                OR email_user LIKE ('%" . $search . "%')       
                OR label_roleuser LIKE ('%" . $search . "%'))";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $i = 1;
            $agence ='';
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['etat_user'] == 'T') {
                    $etat = "ACTIF";
                    $class = "etat etatactif";
                } else {
                    $etat = "INACTIF";
                    $class = "etat etatinactif";
                }
            
                $agence ='K2 Rent';
                if ($row['role_user'] == '2'){
                    $agence = $row['nom_agence'];
                }
                    
                $value .= '<tr>
		    		<td>' . $i . '</td>
                    <td>' . $row['nom_user'] . '</td>
		    	    <td>' . utf8_decode($row['login_user']) . '</td>
		    	    <td>' . $row['email_user'] . '</td>
		    	    <td>' . $row['label_roleuser'] . '</td>
		    	    <td>' . $agence . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">' . $etat . '</div></center></td>
		    	    <td>
		    		    <div class="btn-group" role="group">
                            <button type="button" title="Modifier l\'utilisateur" class="btn" style="font-size: 2px;" id="btn-edit-user" data-id=' . $row['id_user'] . '>
                            <i class="lni lni-pencil-alt iconaction"></i></button>'; 
                            if ($row['role_user'] != '0') {
                                $value .= '<button type="button" title="Supprimer l\'utilisateur" class="btn" style="font-size: 2px;" id="btn-delete-user" data-id1=' . $row['id_user'] . '>
                                <i class="lni lni-trash iconaction"></i></button>';
                            }
                        $value .= '</div>
		    	    </td></tr>';
                $i += 1;
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value; 
    } else {
        display_user_record();
    }
}

function InsertUser()
{
    global $conn;
    global $msg_insert_succés;

    $typeuser = $_POST['typeuser'];
    $nom = $_POST['nom'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $id_user_agence = $_POST['id_user_agence'];

    $sql_e = "SELECT * FROM user WHERE (login_user='$login')";
    $res_e = mysqli_query($conn, $sql_e);
    if (mysqli_num_rows($res_e) > 0) {
        echo "<div class='text-echec'>Désolé ... login déjà utilisé !</div>";
    } else {
        if ($typeuser == "2") {
            $query = "INSERT INTO 
            user(nom_user,login_user,motdepasse_user,role_user,email_user,id_agence) 
            VALUES ('$nom','$login','$password','$typeuser','$email','$id_user_agence')";
        } else {
            $query = "INSERT INTO 
            user(nom_user,login_user,motdepasse_user,role_user,email_user,id_agence) 
            VALUES ('$nom','$login','$password','$typeuser','$email','0')";
        }
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<div class='text-checked'>L'utilisateur $msg_insert_succés</div>";
        } else {
            echo "<div class='text-echec'>Vous avez rencontré un problème lors de l'ajout d'utilisateur !</div>";
        }
    }
}

function get_user_record()
{
    global $conn;
    $UserId = $_POST['ClientID'];
    $query = "SELECT * FROM user WHERE id_user='$UserId'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $user_data = [];
        $user_data[0] = $row['id_user'];
        $user_data[1] = $row['nom_user'];
        $user_data[2] = $row['login_user'];
        $user_data[3] = $row['etat_user'];
        $user_data[4] = $row['email_user'];
    }
    echo json_encode($user_data);
}

function update_user_value()
{
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;
    $date = date('Y-m-d H:i:s');
    $user_id = $_POST["_id"];
    $user_query = "SELECT * FROM  user where id_user = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    if (!$user) {
        echo json_encode(["error" => "Client introuvable ", "data" => "user $user_id not found."]);
        return;
    }
    $mdp = $user['motdepasse_user'];
    if ($_POST["password"] == "*****"){
        $user_updated_password = $mdp;
    }else{
        $user_updated_password = md5($_POST["password"]);
    }
    $user_updated_nom = $_POST["nom"];
    $user_updated_email = $_POST["email"];
    $user_updated_login = $_POST["login"];
    $updateuseretat = $_POST["updateuseretat"];
    $update_query = "UPDATE user 
                        SET nom_user='$user_updated_nom',
                            login_user='$user_updated_login',
                            etat_user='$updateuseretat',
                            email_user='$user_updated_email',
                            motdepasse_user='$user_updated_password', 
                            date_updated_user = '$date'
                        WHERE id_user = $user_id";
    $update_result = mysqli_query($conn, $update_query);
    if (!$update_result) {
        echo "<div class='text-echec'>$msg_update_echec l'utilisateur !</div>";
    }else{
        echo "<div class='text-checked'>L'utilisateur $msg_update_succés</div>";
    }
}

function delete_user_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;

    $date = date('Y-m-d H:i:s');
    $Del_ID = $_POST['Delete_UserID'];
    $query = "UPDATE user SET etat_user = 'S', date_updated_user = '$date' WHERE id_user= '$Del_ID'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<div class='text-checked'>L'utilisateur $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec l'utilisateur !</div>";
    }
}

// Client

function display_client_record()
{
    global $conn;
    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Nom Conducteur</th>
            <th class="border-top-0">E-mail</th>
            <th class="border-top-0">Téléphone</th>
            <th class="border-top-0">Adresse</th>
            <th class="border-top-0">CIN</th>
            <th class="border-top-0">PERMIS</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT * FROM client where action_client = '1'";
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['nom_client'] . '</td>
            <td>' . $row['email_client'] . '</td>
            <td>' . $row['tel_client'] . '</td>
            <td>' . $row['adresse_client'] . '</td>
            <td><a href="uploadfile/client/cin/' . $row["cin_client"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/client/cin/' . $row["cin_client"] . '"></a></td>
            <td><a href="uploadfile/client/permis/' . $row["permis_client"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/client/permis/' . $row["permis_client"] . '"></a></td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Modifier le client" class="btn" style="font-size: 2px;" id="btn-edit-client" data-id=' . $row['id_client'] . '>
                    <i class="lni lni-pencil-alt iconaction"></i></button> 
                    <button type="button" title="Supprimer le client" class="btn" style="font-size: 2px;" id="btn-delete-client" data-id1=' . $row['id_client'] . '>
                    <i class="lni lni-trash iconaction"></i></button>
		        </div>
            </td>
        </tr>'; 
        $i += 1;     
    }
    $value .= '</tbody>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchClient()
{
    global $conn;
    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Nom Conducteur</th>
            <th class="border-top-0">E-mail</th>
            <th class="border-top-0">Téléphone</th>
            <th class="border-top-0">Adresse</th>
            <th class="border-top-0">CIN</th>
            <th class="border-top-0">PERMIS</th>
            <th class="border-top-0">Actions</th>   
        </tr>
    </thead>
    <tbody>';
    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        $query = ("SELECT * FROM client
         WHERE action_client = '1'
         AND (nom_client LIKE ('%" . $search . "%')
                OR email_client LIKE ('%" . $search . "%') 
                OR tel_client LIKE ('%" . $search . "%')       
                OR adresse_client LIKE ('%" . $search . "%'))");
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['nom_client'] . '</td>
                    <td>' . $row['email_client'] . '</td>
                    <td>' . $row['tel_client'] . '</td>
                    <td>' . $row['adresse_client'] . '</td>
                    <td><a href="uploadfile/client/cin/' . $row["cin_client"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/client/cin/' . $row["cin_client"] . '"></a></td>
                    <td><a href="uploadfile/client/permis/' . $row["permis_client"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/client/permis/' . $row["permis_client"] . '"></a></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" title="Modifier le client" class="btn" style="font-size: 2px;" id="btn-edit-client" data-id=' . $row['id_client'] . '>
                            <i class="lni lni-pencil-alt iconaction"></i></button> 
                            <button type="button" title="Supprimer le client" class="btn" style="font-size: 2px;" id="btn-delete-client" data-id1=' . $row['id_client'] . '>
                            <i class="lni lni-trash iconaction"></i></button>
		                </div>
                    </td>
                </tr>';
                $i += 1;
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_client_record();
    }
}

function InsertClient()
{
    global $conn;
    global $msg_insert_succés;

    $ClientName = $_POST['ClientName'];
    $ClientEmail = $_POST['ClientEmail'];
    $Namefile = md5($ClientEmail);
    $ClientPhone = $_POST['ClientPhone'];
    $ClientAdresse = $_POST['ClientAdresse'];
    $ClientCIN = isset($_FILES['ClientCIN']) ? $_FILES['ClientCIN'] : "";
    $ClientPermis = isset($_FILES['ClientPermis']) ? $_FILES['ClientPermis'] : "";

    $emplacement_cin = "uploadfile/client/cin/";
    $file_cin = $emplacement_cin . basename($_FILES["ClientCIN"]["name"]);
    $uploadOk_cin = 1;
    $type_cin = strtolower(pathinfo($file_cin,PATHINFO_EXTENSION));

    if($type_cin != "jpg" && $type_cin != "png" && $type_cin != "jpeg" && $type_cin != "gif" ) {
        echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        $uploadOk_cin = 0;
    }  
    if ($uploadOk_cin != 0) {
      if (move_uploaded_file($_FILES["ClientCIN"]["tmp_name"], $emplacement_cin .$Namefile.".".$type_cin)) {
        $ClientCIN = $Namefile.".".$type_cin;
      } else {
        echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
      }
    }

    $emplacement_permis = "uploadfile/client/permis/";
    $file_permis = $emplacement_permis . basename($_FILES["ClientPermis"]["name"]);
    $uploadOk_permis = 1;
    $type_permis = strtolower(pathinfo($file_permis,PATHINFO_EXTENSION));

    if($type_permis != "jpg" && $type_permis != "png" && $type_permis != "jpeg" && $type_permis != "gif" ) {
        echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        $uploadOk_permis = 0;
    }  
    if ($uploadOk_permis != 0) {
      if (move_uploaded_file($_FILES["ClientPermis"]["tmp_name"], $emplacement_permis .$Namefile.".".$type_permis)) {
        $ClientPermis = $Namefile.".".$type_permis;
      } else {
        echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
      }
    }
    if ($uploadOk_cin == 1 && $uploadOk_permis == 1) {
        $sql_e = "SELECT * FROM client WHERE email_client = '$ClientEmail' AND action_client = '1'";
        $res_e = mysqli_query($conn, $sql_e);
        if (mysqli_num_rows($res_e) > 0) {
            echo '<div class="text-echec">Désolé ... Email est déjà pris!</div>';
        } else {
            $date = date('Y-m-d H:i:s');
            $query = "INSERT INTO client(nom_client,email_client,tel_client,adresse_client,cin_client,permis_client,date_created_client,date_updated_client) 
                VALUES ('$ClientName','$ClientEmail','$ClientPhone','$ClientAdresse','$ClientCIN','$ClientPermis','$date','$date') ";

            $result = mysqli_query($conn, $query);
            if ($result) {
                echo "<div class='text-checked'>Le client $msg_insert_succés</div>";
            } else {
                echo "<div class='text-echec'>Vous avez rencontré un problème lors de l'ajout du client</div>";
            }
        }
    }
}

function get_client_record()
{
    global $conn;
    $ClientId = $_POST['ClientID'];
    $query = "SELECT * FROM client WHERE id_client= '$ClientId'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $client_data = [];
        $client_data[0] = $row['id_client'];
        $client_data[1] = $row['nom_client'];
        $client_data[2] = $row['email_client'];
        $client_data[3] = $row['tel_client'];
        $client_data[4] = $row['adresse_client'];  
        $client_data[5] = $row['cin_client'];
        $client_data[6] = $row['permis_client'];
    }
    echo json_encode($client_data);
}

function update_client_value()
{
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;

    $up_idclient = $_POST["up_idclient"];
    $up_clientName = $_POST["up_clientName"];
    $up_clientEmail = $_POST["up_clientEmail"];
    $Namefile = md5($up_clientEmail);
    $up_clientPhone = $_POST["up_clientPhone"];
    $up_clientAdresse = $_POST["up_clientAdresse"];
    $up_clientCIN = isset($_FILES['up_clientCIN']) ? $_FILES['up_clientCIN'] : "";
    $up_clientPermis = isset($_FILES['up_clientPermis']) ? $_FILES['up_clientPermis'] : "";

    $client_query = "SELECT * FROM  client where id_client = $up_idclient";
    $client_result = mysqli_query($conn, $client_query);
    $client = mysqli_fetch_assoc($client_result);
    
    if($up_clientCIN != ""){
        $emplacement_cin = "uploadfile/client/cin/";
        $file_cin = $emplacement_cin . basename($_FILES["up_clientCIN"]["name"]);
        $uploadOk_cin = 1;
        $type_cin = strtolower(pathinfo($file_cin,PATHINFO_EXTENSION));
        if($type_cin != "jpg" && $type_cin != "png" && $type_cin != "jpeg" && $type_cin != "gif" ) {
            $up_clientCIN = $client["cin_client"];
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_cin = 0;
        }  
        if ($uploadOk_cin != 0) {
          if (move_uploaded_file($_FILES["up_clientCIN"]["tmp_name"], $emplacement_cin .$Namefile.".".$type_cin)) {
            $up_clientCIN = $Namefile.".".$type_cin;
          } else {
            $up_clientCIN = $client["cin_client"];
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
          }
        }
    }else{
        $up_clientCIN = $client["cin_client"];
    }
    
    if($up_clientPermis != ""){
        $emplacement_permis = "uploadfile/client/permis/";
        $file_permis = $emplacement_permis . basename($_FILES["up_clientPermis"]["name"]);
        $uploadOk_permis = 1;
        $type_permis = strtolower(pathinfo($file_permis,PATHINFO_EXTENSION));

        if($type_permis != "jpg" && $type_permis != "png" && $type_permis != "jpeg" && $type_permis != "gif" ) {
            $up_clientPermis = $client["permis_client"];
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_permis = 0;
        }  
        if ($uploadOk_permis != 0) {
          if (move_uploaded_file($_FILES["up_clientPermis"]["tmp_name"], $emplacement_permis .$Namefile.".".$type_permis)) {
            $up_clientPermis = $Namefile.".".$type_permis;
          } else {
            $up_clientPermis = $client["permis_client"];
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
          }
        }
    }else{
        $up_clientPermis = $client["permis_client"];
    }

    $sql_e = "SELECT * FROM client WHERE id_client != '$up_idclient' AND email_client = '$up_clientEmail' AND action_client = '1'";
    $res_e = mysqli_query($conn, $sql_e);
    if (mysqli_num_rows($res_e) > 0) {
        echo '<div class="text-echec" role="alert">Désolé ... Email est déjà pris!</div>';
        return;
    } else {
        $date = date('Y-m-d H:i:s');
        $update_query = "UPDATE client 
                        SET 
                            nom_client='$up_clientName',
                            email_client='$up_clientEmail',
                            tel_client='$up_clientPhone',
                            adresse_client='$up_clientAdresse',
                            cin_client='$up_clientCIN',
                            permis_client='$up_clientPermis',
                            date_updated_client='$date'
                        WHERE id_client = $up_idclient";

        $update_result = mysqli_query($conn, $update_query);
        if (!$update_result) {
            echo "<div class='text-echec'>$msg_update_echec client !</div>";
            return;
        }
        echo "<div class='text-checked'>Le client $msg_update_succés</div>";

        return;
    }
}

function delete_client_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;

    $Del_ID = $_POST['Delete_ClientID'];
    $date = date('Y-m-d H:i:s');
    $query = "UPDATE client 
            SET 
                action_client='0',
                date_updated_client='$date'
            WHERE id_client = $Del_ID";

    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<div class='text-checked'>Le client $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec client !</div>";
    }
}

// Voiture

function testPapier($idVoiture, &$modalNumb){
    global $conn;

    // test papier assurance
    $queryAssur_lastid="SELECT id_assurancevoiture
            FROM assurance_voiture
            WHERE id_voiture='$idVoiture'
            ORDER BY id_assurancevoiture DESC LIMIT 1";
    $resultAssur_lastid = mysqli_query($conn, $queryAssur_lastid);
    $rowAssur_lastid = mysqli_fetch_row($resultAssur_lastid);
    $Assur_lastid = $rowAssur_lastid[0];
    $queryAssur="SELECT *
            FROM assurance_voiture
            WHERE (DATE(NOW()) BETWEEN DATE_SUB(date_fin_assurance, INTERVAL 7 DAY) AND DATE_SUB(date_fin_assurance, INTERVAL 0 DAY))
            AND id_voiture = '$idVoiture'
            AND id_assurancevoiture = '$Assur_lastid'";
    $resultAssur = mysqli_query($conn, $queryAssur);

    // test papier visite technique
    $queryVisit_lastid="SELECT id_visitevoiture
            FROM visite_voiture
            WHERE id_voiture='$idVoiture'
            ORDER BY id_visitevoiture DESC LIMIT 1";
    $resultVisit_lastid = mysqli_query($conn, $queryVisit_lastid);
    $rowVisit_lastid = mysqli_fetch_row($resultVisit_lastid);
    $Visit_lastid = $rowVisit_lastid[0];
    $queryVisit="SELECT *
        FROM visite_voiture
        WHERE (DATE(NOW()) BETWEEN DATE_SUB(date_fin_visite, INTERVAL 7 DAY) AND DATE_SUB(date_fin_visite, INTERVAL 0 DAY)) 
        AND id_voiture='$idVoiture'
        AND id_visitevoiture = '$Visit_lastid'";
    $resultVisit = mysqli_query($conn, $queryVisit);

    if (mysqli_num_rows($resultAssur) > 0 && mysqli_num_rows($resultVisit) > 0 ) 
    {
    return true;
    }else if(mysqli_num_rows($resultAssur) > 0){
        $modalNumb=1;
        return true;
     
    }
    else if(mysqli_num_rows($resultVisit) > 0){
    $modalNumb=2;
    return true;
 
}
    else return false;
}

function display_voiture_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];
    
    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">PIMM</th>
            <th class="border-top-0">Marque/Modèle</th>
            <th class="border-top-0">Type de carburant</th>
            <th class="border-top-0">Boite de vitesse</th>
            <th class="border-top-0">Puissance</th>
            <th class="border-top-0">Nombre de place</th>
            <th class="border-top-0">Nombre de valise</th>
            <th class="border-top-0">Air conditionné</th>
            <th class="border-top-0">Agence</th>
            <th class="border-top-0">Carte grise</th>
            <th class="border-top-0">Actions</th>      
        </tr>
    </thead>
    <tbody>';
    if($id_role == 2){
        $query = "SELECT * 
        FROM voiture as V 
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
        LEFT JOIN valise_voiture AS VV on V.valise_voiture = VV.id_valisevoiture
        LEFT JOIN agence AS A on V.id_agence = A.id_agence
        WHERE action_voiture = '1'
        AND V.id_agence = $id_agence
        ORDER BY id_voiture ASC";
    }else{
        $query = "SELECT * 
        FROM voiture as V 
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
        LEFT JOIN valise_voiture AS VV on V.valise_voiture = VV.id_valisevoiture
        LEFT JOIN agence AS A on V.id_agence = A.id_agence
        WHERE action_voiture = '1'
        ORDER BY id_voiture ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $class = "etat etatactif";
        $climatisation = "OUI";
        if ($row['climatisation_voiture'] == '0') {
            $class = "etat etatinactif";
            $climatisation = "NON";
        } 
        $modalNumb=0;
        $papierResult=testPapier($row['id_voiture'],$modalNumb )==false ?"hidden":"";
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['pimm_voiture'] . '</td>
            <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
            <td>' . $row['label_carburant'] . '</td>
            <td>' . $row['boitevitesse_voiture'] . '</td>
            <td>' . $row['puissance_voiture'] . ' CV' .'</td>
            <td>' . $row['nbreplace_voiture'] . ' places' .'</td>
            <td>' . $row['label_valise'] . '</td>
            <td style="height: 70px;"><center><div class="'.$class.'">' . $climatisation . '</div></center></td>
            <td>' . $row['nom_agence'] . '</td>
            <td><a href="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '" target="_blank"><img id="grise-'. $row['id_voiture'] .'" width="40px"height="40px" src="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '"></a></td>
            <td>
                <div class="btn-group" role="group">
                    <button '.$papierResult.' type="button" title="Modifier papier voiture" class="btn" style="font-size:2px; background:#FDA6A6;" id="btn-edit-papier" papier-id=' . $row['id_voiture'] . ' modal-Numb='.$modalNumb.'> 
                    <i id="iconpapiers" style="color: #BF1616;" class="lni lni-pencil-alt"></i></button>
                    <button type="button" title="Modifier la voiture" class="btn" style="font-size: 2px;" id="btn-edit-voiture" data-id=' . $row['id_voiture'] . '>
                    <i class="lni lni-pencil-alt iconaction"></i></button>
                    <button type="button" title="Supprimer la voiture" class="btn" style="font-size: 2px;" id="btn-delete-voiture" data-id1=' . $row['id_voiture'] . '>
                    <i class="lni lni-trash iconaction"></i></button>
                </div>
            </td> 
        </tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchVoiture()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">PIMM</th>
            <th class="border-top-0">Marque/Modèle</th>
            <th class="border-top-0">Type de carburant</th>
            <th class="border-top-0">Boite de vitesse</th>
            <th class="border-top-0">Puissance</th>
            <th class="border-top-0">Nombre de place</th>
            <th class="border-top-0">Nombre de valise</th>
            <th class="border-top-0">Air conditionné</th>
            <th class="border-top-0">Agence</th>
            <th class="border-top-0">Carte grise</th>
            <th class="border-top-0">Actions</th>       
        </tr>
    </thead>
    <tbody>';
    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = ($query = "SELECT * 
            FROM voiture as V 
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
            LEFT JOIN valise_voiture AS VV on V.valise_voiture = VV.id_valisevoiture
            LEFT JOIN agence AS A on V.id_agence = A.id_agence
            WHERE action_voiture = '1'
            AND V.id_agence = $id_agence
            AND (pimm_voiture LIKE ('%" . $search . "%')
                    OR marque LIKE ('%" . $search . "%') 
                    OR model LIKE ('%" . $search . "%')       
                    OR label_carburant LIKE ('%" . $search . "%')
                    OR boitevitesse_voiture LIKE ('%" . $search . "%')
                    OR nom_agence LIKE ('%" . $search . "%')
                    OR puissance_voiture LIKE ('%" . $search . "%') 
                    OR nbreplace_voiture LIKE ('%" . $search . "%')       
                    OR label_valise LIKE ('%" . $search . "%'))");
        }else{
            $query = ($query = "SELECT * 
            FROM voiture as V 
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
            LEFT JOIN valise_voiture AS VV on V.valise_voiture = VV.id_valisevoiture
            LEFT JOIN agence AS A on V.id_agence = A.id_agence
            WHERE action_voiture = '1'
            AND (pimm_voiture LIKE ('%" . $search . "%')
                    OR marque LIKE ('%" . $search . "%') 
                    OR model LIKE ('%" . $search . "%')       
                    OR label_carburant LIKE ('%" . $search . "%')
                    OR boitevitesse_voiture LIKE ('%" . $search . "%')
                    OR nom_agence LIKE ('%" . $search . "%')
                    OR puissance_voiture LIKE ('%" . $search . "%') 
                    OR nbreplace_voiture LIKE ('%" . $search . "%')       
                    OR label_valise LIKE ('%" . $search . "%'))");
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $class = "etat etatactif";
                $climatisation = "OUI";
                if ($row['climatisation_voiture'] == '0') {
                    $class = "etat etatinactif";
                    $climatisation = "NON";
                } 
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['pimm_voiture'] . '</td>
                    <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
                    <td>' . $row['label_carburant'] . '</td>
                    <td>' . $row['boitevitesse_voiture'] . '</td>
                    <td>' . $row['puissance_voiture'] . ' CV' .'</td>
                    <td>' . $row['nbreplace_voiture'] . ' places' .'</td>
                    <td>' . $row['label_valise'] . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">' . $climatisation . '</div></center></td>
                    <td>' . $row['nom_agence'] . '</td>
                    <td><a href="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '"></a></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" title="Modifier la voiture" class="btn" style="font-size: 2px;" id="btn-edit-voiture" data-id=' . $row['id_voiture'] . '>
                            <i class="lni lni-pencil-alt iconaction"></i></button>
                            <button type="button" title="Supprimer la voiture" class="btn" style="font-size: 2px;" id="btn-delete-voiture" data-id1=' . $row['id_voiture'] . '>
                            <i class="lni lni-trash iconaction"></i></button>
                        </div>
                    </td>
                </tr>';
                $i += 1; 
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_voiture_record();
    }
}

function InsertVoiture()
{
    global $conn;
    global $msg_insert_echec;
    global $msg_insert_succés;
    $id_agence = $_SESSION['Agence'];

    $voiturepimm = $_POST['voiturepimm1']." TU ".$_POST['voiturepimm2'];
    $Namefile = md5($voiturepimm);
    $voitureMarqueModel = $_POST['voitureMarqueModel'];
    $voituretypecarburant = $_POST['voituretypecarburant'];
    $voitureboitevitesse = $_POST['voitureboitevitesse'];
    $voiturenbreplace = $_POST['voiturenbreplace'];
    $voiturenbrevalise = $_POST['voiturenbrevalise'];
    $voiturepuissance = $_POST['voiturepuissance'];
    $voitureclimatisation = $_POST['voitureclimatisation'];
    $voiturecartegrise = isset($_FILES['voiturecartegrise']) ? $_FILES['voiturecartegrise'] : "";
    $voitureassurance = isset($_FILES['voitureassurance']) ? $_FILES['voitureassurance'] : "";
    $visitetechniquevoiture = isset($_FILES['visitetechniquevoiture']) ? $_FILES['visitetechniquevoiture'] : "";
    $voiturevignette = isset($_FILES['voiturevignette']) ? $_FILES['voiturevignette'] : "";
    $prixassurance = $_POST['prixassurance'];
    $datefinassurance = $_POST['datefinassurance'];
    $datefinvisitetechnique = $_POST['datefinvisitetechnique'];
    $prixvisitetechnique = $_POST['prixvisitetechnique'];
    
    if ($id_agence != "0") {
        $voitureagence = $id_agence;
    } else {
        $voitureagence = $_POST['voitureagence'];
    }
    
    if($voitureagence == 0){
        echo '<div class="text-echec">Veuillez choisir l\'agence!</div>';
    }else{
        // verifier file cartegrise
        $emplacement_cartegrise = "uploadfile/voiture/cartegrise/";
        $file_cartegrise = $emplacement_cartegrise . basename($_FILES["voiturecartegrise"]["name"]);
        $uploadOk_cartegrise = 1;
        $sizeGrise = $_FILES["voiturecartegrise"]["size"];
        $type_cartegrise = strtolower(pathinfo($file_cartegrise,PATHINFO_EXTENSION));

        if($type_cartegrise != "jpg" && $type_cartegrise != "png" && $type_cartegrise != "jpeg" && $type_cartegrise != "gif" ) {
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_cartegrise = 0;
        }  

        // verifier file vignette
        $emplacement_vignette = "uploadfile/voiture/vignette/";
        $file_vignette = $emplacement_vignette . basename($_FILES["voiturevignette"]["name"]);
        $uploadOk_vignette = 1;
        $sizeVign = $_FILES["voiturevignette"]["size"];
        $type_vignette = strtolower(pathinfo($file_vignette,PATHINFO_EXTENSION));
 
        if($type_vignette != "jpg" && $type_vignette != "png" && $type_vignette != "jpeg" && $type_vignette != "gif" ) {
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_vignette = 0;
        }  

        // verifier file visite technique
        $emplacement_visite = "uploadfile/voiture/visite_technique/";
        $file_visite = $emplacement_visite . basename($_FILES["visitetechniquevoiture"]["name"]);
        $uploadOk_visite = 1;
        $sizeVis = $_FILES["visitetechniquevoiture"]["size"];
        $type_visite = strtolower(pathinfo($file_visite,PATHINFO_EXTENSION));

        if($type_visite != "jpg" && $type_visite != "png" && $type_visite != "jpeg" && $type_visite != "gif" ) {
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_visite = 0;
        }  

        // verifier file assurance
        $emplacement_assurance = "uploadfile/voiture/assurance/";
        $file_assurance = $emplacement_assurance . basename($_FILES["voitureassurance"]["name"]);
        $uploadOk_assurance = 1;
        $sizeAssur = $_FILES["voitureassurance"]["size"];
        $type_assurance = strtolower(pathinfo($file_assurance,PATHINFO_EXTENSION));

        if($type_assurance != "jpg" && $type_assurance != "png" && $type_assurance != "jpeg" && $type_assurance != "gif" ) {
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_assurance = 0;
        }   

        if ($uploadOk_cartegrise == 1 && $uploadOk_assurance == 1 && $uploadOk_visite == 1 && $uploadOk_vignette == 1) {
            // vérification si le pimm existe
            $sql_e = "SELECT * FROM voiture WHERE pimm_voiture = '$voiturepimm' AND action_voiture = '1'";
            $res_e = mysqli_query($conn, $sql_e);
            if (mysqli_num_rows($res_e) > 0) {
                echo '<div class="text-echec">Désolé ... Immatriculation est déjà pris!</div>';
            } else {
                // upload file cartegrise
                if ($sizeGrise >= 500) {
                    compressImage($_FILES["voiturecartegrise"]["tmp_name"], $emplacement_cartegrise .$Namefile.".".$type_cartegrise, 60);
                } else {
                    move_uploaded_file($_FILES["voiturecartegrise"]["tmp_name"], $emplacement_cartegrise .$Namefile.".".$type_cartegrise);
                }
                $voiturecartegrise = $Namefile.".".$type_cartegrise;

                // insert data in table voiture
                $date = date('Y-m-d H:i:s');
                $queryVoiture = "INSERT INTO 
                            voiture(pimm_voiture,id_marquemodel,id_agence,id_typecarburant,boitevitesse_voiture,nbreplace_voiture,valise_voiture,puissance_voiture,climatisation_voiture,
                            cartegrise_voiture,date_created_voiture,date_updated_voiture) 
                            VALUES ('$voiturepimm','$voitureMarqueModel','$voitureagence','$voituretypecarburant','$voitureboitevitesse','$voiturenbreplace','$voiturenbrevalise','$voiturepuissance','$voitureclimatisation',
                            '$voiturecartegrise','$date','$date') ";
                $resultVoiture = mysqli_query($conn, $queryVoiture);
                if($resultVoiture){
                    // get the new id_voiture
                    $queryID = " SELECT id_voiture
                                    FROM voiture 
                                    WHERE pimm_voiture = '$voiturepimm'";
                    $IdVoiture = mysqli_query($conn, $queryID);
                    $rowID = mysqli_fetch_assoc($IdVoiture);
                    $ID= $rowID['id_voiture'];

                    // insert data in table assurance  and upload file
                    if ($sizeAssur >= 500) {
                        compressImage($_FILES["voitureassurance"]["tmp_name"], $emplacement_assurance .$Namefile."_".date("Y").".".$type_assurance, 60);
                    } else {
                        move_uploaded_file($_FILES["voitureassurance"]["tmp_name"], $emplacement_assurance .$Namefile."_".date("Y").".".$type_assurance) ;
                    }
                    $ClientAssur = $Namefile."_".date("Y").".".$type_assurance;
                    $queryAssurance = "INSERT INTO assurance_voiture(id_voiture,prix_assurance,date_fin_assurance,file_assurance) 
                                        VALUES ('$ID','$prixassurance','$datefinassurance','$ClientAssur')";
                    $resultAssurance = mysqli_query($conn, $queryAssurance);

                    // insert data in table visite and upload file
                    if ($sizeVis >= 500) {
                        compressImage($_FILES["visitetechniquevoiture"]["tmp_name"], $emplacement_visite .$Namefile."_".date("Y").".".$type_visite, 60);
                    } else {
                        move_uploaded_file($_FILES["visitetechniquevoiture"]["tmp_name"], $emplacement_visite .$Namefile."_".date("Y").".".$type_visite) ;
                    }
                    $voiturevisite = $Namefile."_".date("Y").".".$type_visite;
                    $queryVisite = "INSERT INTO visite_voiture(id_voiture,prix_visite,date_fin_visite,file_visite) 
                                    VALUES ('$ID','$prixvisitetechnique','$datefinvisitetechnique','$voiturevisite')";
                    $resultVisite = mysqli_query($conn, $queryVisite);

                    // insert data in table vignette and upload file
                    if ($sizeVign >= 500) {
                        compressImage($_FILES["voiturevignette"]["tmp_name"], $emplacement_vignette .$Namefile."_".date("Y").".".$type_vignette, 60);
                    } else {
                        move_uploaded_file($_FILES["voiturevignette"]["tmp_name"], $emplacement_vignette .$Namefile."_".date("Y").".".$type_vignette) ;
                    }
                    $voiturevignette = $Namefile."_".date("Y").".".$type_vignette;
                    $queryVignette = "INSERT INTO vignette_voiture(id_voiture,file_vignette) 
                                        VALUES ('$ID','$voiturevignette')";
                    $resultVignette = mysqli_query($conn, $queryVignette);
                    if($resultVoiture && $resultAssurance && $resultVisite && $resultVisite){
                        echo "<div class='text-checked'>La voiture $msg_insert_succés</div>";
                    }else {
                        echo "<div class='text-echec'>$msg_insert_echec la voiture</div>";
                    }         
                } else {
                    echo "<div class='text-echec'>$msg_insert_echec la voiture</div>";
                }                
            }
        }
    }     
}

function get_voiture_record()
{
    global $conn;
    $idvoiture = $_POST['id_voiture'];
    $query = " SELECT *
    FROM voiture AS V 
    LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
    LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
    LEFT JOIN agence AS A on V.id_agence = A.id_agence
    WHERE action_voiture = '1'
    AND V.id_voiture='$idvoiture'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $voiture_data = [];
        $voiture_data[0] = $row['id_voiture'];
        $imm = explode(" ", $row['pimm_voiture']);
        $voiture_data[1] = $imm[0];
        $voiture_data[2] = $imm[2];
        $voiture_data[3] = $row['id_marquemodel'];
        $voiture_data[4] = $row['id_carburantvoiture'];
        $voiture_data[5] = $row['boitevitesse_voiture'];
        $voiture_data[6] = $row['id_agence'];
        $voiture_data[7] = $row['nbreplace_voiture'];
        $voiture_data[8] = $row['valise_voiture'];
        $voiture_data[9] = $row['puissance_voiture'];
        $voiture_data[10] = $row['climatisation_voiture'];
    }
    echo json_encode($voiture_data);
}

function update_voiture_value()
{
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;
    $up_voitureid = $_POST["up_voitureid"];
    $up_voiturepimm = $_POST['up_voiturepimm1']." TU ".$_POST['up_voiturepimm2'];
    $Namefile = md5($up_voiturepimm);
    $up_voitureMarqueModel = $_POST["up_voitureMarqueModel"];
    $up_voituretypecarburant = $_POST["up_voituretypecarburant"];
    $up_voitureboitevitesse = $_POST["up_voitureboitevitesse"];
    $up_voiturenbreplace = $_POST['up_voiturenbreplace'];
    $up_voiturenbrevalise = $_POST['up_voiturenbrevalise'];
    $up_voiturepuissance = $_POST['up_voiturepuissance'];
    $up_voitureclimatisation = $_POST['up_voitureclimatisation'];
    $up_voiturecartegrise = isset($_FILES['up_voiturecartegrise']) ? $_FILES['up_voiturecartegrise'] : "";

    $voiture_query = "SELECT * FROM voiture where id_voiture = $up_voitureid";
    $voiture_result = mysqli_query($conn, $voiture_query);
    $voiture = mysqli_fetch_assoc($voiture_result);

    if($up_voiturecartegrise != ""){
        $emplacement_cartegrise = "uploadfile/voiture/cartegrise/";
        $file_cartegrise = $emplacement_cartegrise . basename($_FILES["up_voiturecartegrise"]["name"]);
        $uploadOk_cartegrise = 1;
        $type_cartegrise = strtolower(pathinfo($file_cartegrise,PATHINFO_EXTENSION));
        if($type_cartegrise != "jpg" && $type_cartegrise != "png" && $type_cartegrise != "jpeg" && $type_cartegrise != "gif" ) {
            $up_voiturecartegrise = $voiture["cartegrise_voiture"];
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_cartegrise = 0;
        }  
        if ($uploadOk_cartegrise != 0) {
            $sizeGrise = $_FILES["up_voiturecartegrise"]["size"];
           if ($sizeGrise >= 500) {
            compressImage($_FILES["up_voiturecartegrise"]["tmp_name"], $emplacement_cartegrise .$Namefile.".".$type_cartegrise, 60);
            $up_voiturecartegrise = $Namefile.".".$type_cartegrise;
           } else if (move_uploaded_file($_FILES["up_voiturecartegrise"]["tmp_name"], $emplacement_cartegrise .$Namefile.".".$type_cartegrise))  {
            $up_voiturecartegrise = $Namefile.".".$type_cartegrise;
           }
           else {
            $up_voiturecartegrise = $voiture["cartegrise_voiture"];
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
           }
        }
    }else{
        $up_voiturecartegrise = $voiture["cartegrise_voiture"];
    }    
    
    $sql_e = "SELECT * FROM voiture WHERE id_voiture != '$up_voitureid' AND pimm_voiture = '$up_voiturepimm' AND action_voiture = '1'";
    $res_e = mysqli_query($conn, $sql_e);
    if (mysqli_num_rows($res_e) > 0) {
        echo '<div class="text-echec" role="alert">Désolé ... Immatriculation est déjà pris!</div>';
        return;
    } else {
        $date = date('Y-m-d H:i:s');
        $update_query = "UPDATE voiture 
                        SET 
                            pimm_voiture = '$up_voiturepimm',
                            id_marquemodel = '$up_voitureMarqueModel',
                            id_typecarburant = '$up_voituretypecarburant',
                            boitevitesse_voiture = '$up_voitureboitevitesse',
                            nbreplace_voiture = '$up_voiturenbreplace',
                            valise_voiture = '$up_voiturenbrevalise',
                            puissance_voiture = '$up_voiturepuissance',
                            climatisation_voiture = '$up_voitureclimatisation',
                            cartegrise_voiture = '$up_voiturecartegrise',
                            date_updated_voiture = '$date'
                        WHERE id_voiture = $up_voitureid";

        $update_result = mysqli_query($conn, $update_query);
        if (!$update_result) {
            echo "<div class='text-echec'>$msg_update_echec voiture !</div>";
            return;
        }
        echo "<div class='text-checked'>La voiture $msg_update_succés</div>";
        return;
    }
}

function delete_voiture_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;

    $Del_ID = $_POST['id_voiture'];
    $date = date('Y-m-d H:i:s');
    $query = "UPDATE voiture 
                SET 
                    action_voiture = '0',
                    date_updated_voiture='$date' 
                WHERE id_voiture='$Del_ID'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<div class='text-checked'>La voiture $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec voiture !</div>";
    }
}

function update_assurance_voiture(){
    global $conn;
    global $msg_insert_succés;
    global $msg_insert_echec;
    $assurance_voiture_id = $_POST['assurance_voiture_id'];
    $up_DateFinAssurance = $_POST['up_DateFinAssurance'];
    $up_prixAssurance = $_POST['up_prixAssurance'];
    $AssurVoiturePhoto = isset($_FILES['up_assurancephoto']) ? $_FILES['up_assurancephoto'] : "";
   
    $emplacement_assurance = "uploadfile/voiture/assurance/";
    $file_assurance = $emplacement_assurance . basename($_FILES["up_assurancephoto"]["name"]);
    $uploadOk_assur = 1;
    $type_assurance = strtolower(pathinfo($file_assurance,PATHINFO_EXTENSION));
    if($type_assurance != "jpg" && $type_assurance != "png" && $type_assurance != "jpeg" && $type_assurance != "gif" ) {
        echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        $uploadOk_assur = 0;
    }  
    if ($uploadOk_assur != 0) {
        $queryPIMM = "SELECT pimm_voiture  
        FROM voiture 
        WHERE id_voiture = '$assurance_voiture_id'";
            $RowPIMM = mysqli_query($conn, $queryPIMM);
            if (mysqli_num_rows($RowPIMM) > 0) {
            $row = mysqli_fetch_assoc($RowPIMM);
            $PIMM= $row['pimm_voiture'];
            $Namefile = md5($PIMM);
        }

        if (move_uploaded_file($_FILES["up_assurancephoto"]["tmp_name"], $emplacement_assurance .$Namefile."_".date("Y").".".$type_assurance)) {
            $ClientAssur = $Namefile."_".date("Y").".".$type_assurance;
        } else {
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
        }
    }
    if ($uploadOk_assur == 1 ) {

        $query_update_etat = "UPDATE assurance_voiture
                    SET etat_assurance = '0'
                    WHERE id_voiture='$assurance_voiture_id'";
        $result_update_etat = mysqli_query($conn, $query_update_etat);

        if($result_update_etat){
            $query = "INSERT INTO assurance_voiture(id_voiture,prix_assurance,date_fin_assurance,file_assurance) 
                        VALUES ('$assurance_voiture_id','$up_prixAssurance','$up_DateFinAssurance','$ClientAssur') ";
            $result = mysqli_query($conn, $query);
            if($result) {
                echo "<div class='text-checked'>L'assurance $msg_insert_succés</div>";
            } else {
                echo "<div class='text-echec'>$msg_insert_echec l'assurance</div>";
            }
        } else {
            echo "<div class='text-echec'>$msg_insert_echec l'assurance</div>";
        }
    }
}

function update_visite_technique_voiture(){
    global $conn;
    global $msg_insert_succés;
    global $msg_insert_echec;
    $visite_voiutre_id = $_POST['visite_voiture_id'];
    $up_DateFinVisite = $_POST['up_DateFinVisite'];
    $up_prixVisite = $_POST['up_prixVisite'];
    $VisiteVoiturePhoto = isset($_FILES['up_visitephoto']) ? $_FILES['up_visitephoto'] : "";
    $emplacement_visite = "uploadfile/voiture/visite_technique/";
    $file_visite = $emplacement_visite . basename($_FILES["up_visitephoto"]["name"]);
    $uploadOk_visite = 1;
    $type_visite = strtolower(pathinfo($file_visite,PATHINFO_EXTENSION));
    if($type_visite != "jpg" && $type_visite != "png" && $type_visite != "jpeg" && $type_visite != "gif" ) {
        echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
        $uploadOk_visite = 0;
    }  
    if ($uploadOk_visite != 0) {
        $queryPIMM = "SELECT pimm_voiture  
        FROM voiture 
        WHERE id_voiture = '$visite_voiutre_id'";
            $RowPIMM = mysqli_query($conn, $queryPIMM);
            if (mysqli_num_rows($RowPIMM) > 0) {
            $row = mysqli_fetch_assoc($RowPIMM);
            $PIMM= $row['pimm_voiture'];
            $Namefile = md5($PIMM);
            }
      if (move_uploaded_file($_FILES["up_visitephoto"]["tmp_name"], $emplacement_visite .$Namefile."_".date("Y").".".$type_visite)) {
        $ClientVisite = $Namefile."_".date("Y").".".$type_visite;
      } else {
        echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
      }
    }
    if ($uploadOk_visite == 1 ) {
        $query_update_etat = "UPDATE visite_voiture
                    SET 
                        etat_visite = '0'
                    WHERE id_voiture='$visite_voiutre_id'";
        $result_update_etat = mysqli_query($conn, $query_update_etat);

        if($result_update_etat){
            $query = "INSERT INTO visite_voiture(id_voiture,prix_visite,date_fin_visite,file_visite) 
                        VALUES ('$visite_voiutre_id','$up_prixVisite','$up_DateFinVisite','$ClientVisite') ";
            $result = mysqli_query($conn, $query);

            if($result){
                echo "<div class='text-checked'>La visite technique $msg_insert_succés</div>";
            }else {
                echo "<div class='text-echec'>$msg_insert_echec la visite technique</div>";
            }
        } else {
            echo "<div class='text-echec'>$msg_insert_echec l'assurance</div>";
        }
    }
}


function viewpapiervoiture(){
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];
    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">PIMM</th>
            <th class="border-top-0">Marque/Modèle</th>';
            if($id_role !=2){
                $value .= '<th class="border-top-0">Agence</th>';
            }
            $value .= '<th class="border-top-0">Assurance</th>
            <th class="border-top-0">Vignette</th>
            <th class="border-top-0">Visite Technique</th>
            <th class="border-top-0">Actions</th>
        </tr>
    </thead>
    <tbody>';
    if($id_role == 2){
        $query = "SELECT V.pimm_voiture,V.id_voiture,M.marque,M.model,A.file_assurance,VN.file_vignette,VT.file_visite
        FROM voiture AS V
        LEFT JOIN marque_voiture AS M on M.id_marquevoiture  = V.id_marquemodel
        LEFT JOIN assurance_voiture AS A on A.id_voiture = V.id_voiture
        LEFT JOIN vignette_voiture AS VN on VN.id_voiture = V.id_voiture
        LEFT JOIN visite_voiture AS VT on VT.id_voiture = V.id_voiture 
        WHERE V.action_voiture='1'
        AND A.etat_assurance = '1' 
        AND VN.etat_vignette = '1'
        AND VT.etat_visite = '1'
        AND V.id_agence = '$id_agence'
        ORDER BY V.id_voiture ASC";
    }else{
        $query = "SELECT V.pimm_voiture,V.id_voiture,M.marque,M.model,A.file_assurance,VN.file_vignette,VT.file_visite,AG.nom_agence
        FROM voiture AS V
        LEFT JOIN marque_voiture AS M on M.id_marquevoiture  = V.id_marquemodel
        LEFT JOIN assurance_voiture AS A on A.id_voiture = V.id_voiture
        LEFT JOIN vignette_voiture AS VN on VN.id_voiture = V.id_voiture
        LEFT JOIN visite_voiture AS VT on VT.id_voiture = V.id_voiture 
        LEFT JOIN agence AS AG on AG.id_agence = V.id_agence 
        WHERE V.action_voiture='1'
        AND A.etat_assurance = '1' 
        AND VN.etat_vignette = '1'
        AND VT.etat_visite = '1'   
        ORDER BY V.id_voiture ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {  
        $value .= '<tr>
                <td>' . $i . '</td>
                <td>' . $row['pimm_voiture'] . '</td>
                <td>' . $row['marque'] . ' ' . $row['model'] . '</td>';
                if($id_role !=2){
                    $value .= '<td>' . $row['nom_agence'] . '</td>';
                }
                $value .= '
                <td><a href="uploadfile/voiture/assurance/' . $row["file_assurance"] . '" target="_blank"><img id="assur-'. $row["id_voiture"] .'" width="40px"height="40px" src="uploadfile/voiture/assurance/' . $row["file_assurance"] . '"></a></td>
                <td><a href="uploadfile/voiture/vignette/' . $row["file_vignette"] . '" target="_blank"><img id="vig-'. $row["id_voiture"] .'" width="40px"height="40px" src="uploadfile/voiture/vignette/' . $row["file_vignette"] . '"></a></td>
                <td><a href="uploadfile/voiture/visite_technique/' . $row["file_visite"] . '" target="_blank"><img id="vis-'. $row["id_voiture"] .'" width="40px"height="40px" src="uploadfile/voiture/visite_technique/' . $row["file_visite"] . '"></a></td>
                <td>
                    <button type="button" title="Modifier papier voiture" class="btn" style="font-size: 2px;" id="btn-edit-papiervoiture" data-id=' . $row['id_voiture'] . '>
                    <i class="lni lni-pencil-alt iconaction"></i></button>
                </td>

        </tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchPapierVoiture(){

    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">PIMM</th>
            <th class="border-top-0">Marque/Modèle</th>';
            if($id_role !=2){
                $value .= '<th class="border-top-0">Agence</th>';
            }           
            $value .= '<th class="border-top-0">Assurance</th>
            <th class="border-top-0">Vignette</th>   
            <th class="border-top-0">Visite Technique</th>  
            <th class="border-top-0">Actions</th>       
        </tr>
    </thead>
    <tbody>';
    if (isset($_POST['query'])) {
        $search = $_POST['query'];

        if($id_role == 2){
            $query = "SELECT V.pimm_voiture,V.id_voiture,M.marque,M.model,A.file_assurance,VN.file_vignette,VT.file_visite
            FROM voiture AS V
            LEFT JOIN marque_voiture AS M on M.id_marquevoiture  = V.id_marquemodel
            LEFT JOIN assurance_voiture AS A on A.id_voiture = V.id_voiture
            LEFT JOIN vignette_voiture AS VN on VN.id_voiture = V.id_voiture
            LEFT JOIN visite_voiture AS VT on VT.id_voiture = V.id_voiture 
            WHERE V.action_voiture='1'
            AND A.etat_assurance = '1' 
            AND VN.etat_vignette = '1'
            AND VT.etat_visite = '1' 
            AND V.id_agence = '$id_agence'
            AND (V.pimm_voiture LIKE ('%" . $search . "%')
                OR M.marque LIKE ('%" . $search . "%') 
                OR M.model LIKE ('%" . $search . "%'))       
                ORDER BY V.id_voiture ASC";
        }else{
            $query = "SELECT V.pimm_voiture,V.id_voiture,M.marque,M.model,A.file_assurance,VN.file_vignette,VT.file_visite,AG.nom_agence
            FROM voiture AS V
            LEFT JOIN marque_voiture AS M on M.id_marquevoiture  = V.id_marquemodel
            LEFT JOIN assurance_voiture AS A on A.id_voiture = V.id_voiture
            LEFT JOIN vignette_voiture AS VN on VN.id_voiture = V.id_voiture
            LEFT JOIN visite_voiture AS VT on VT.id_voiture = V.id_voiture 
            LEFT JOIN agence AS AG on AG.id_agence = V.id_agence 
            WHERE V.action_voiture='1'
            AND A.etat_assurance = '1' 
            AND VN.etat_vignette = '1'
            AND VT.etat_visite = '1'  
            AND (V.pimm_voiture LIKE ('%" . $search . "%')
                OR M.marque LIKE ('%" . $search . "%') 
                OR AG.nom_agence LIKE ('%" . $search . "%')
                OR M.model LIKE ('%" . $search . "%'))       
                ORDER BY V.id_voiture ASC";
        }

        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                <td>' . $i . '</td>
                <td>' . $row['pimm_voiture'] . '</td>
                <td>' . $row['marque'] . ' ' . $row['model'] . '</td>';
                if($id_role !=2){
                    $value .= '<td>' . $row['nom_agence'] . '</td>';
                }
                $value .= '
                <td><a href="uploadfile/voiture/assurance/' . $row["file_assurance"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/assurance/' . $row["file_assurance"] . '"></a></td>
                <td><a href="uploadfile/voiture/vignette/' . $row["file_vignette"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/vignette/' . $row["file_vignette"] . '"></a></td>
                <td><a href="uploadfile/voiture/visite_technique/' . $row["file_visite"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/visite_technique/' . $row["file_visite"] . '"></a></td>
                <td>
                    <button type="button" title="Modifier papier voiture" class="btn" style="font-size: 2px;" id="btn-edit-papiervoiture" data-id=' . $row['id_voiture'] . '>
                    <i class="lni lni-pencil-alt iconaction"></i></button>
                </td>
            </tr>';
        $i += 1;  
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        viewpapiervoiture();
    }
}


function update_papier_voiture(){
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;
    $up_voitureid = $_POST['up_voitureid'];
    $up_voitureassurance = isset($_FILES['up_voitureassurance']) ? $_FILES['up_voitureassurance'] : "";
    $up_voiturevignette = isset($_FILES['up_voiturevignette']) ? $_FILES['up_voiturevignette'] : "";
    $up_voiturevisite = isset($_FILES['up_voiturevisite']) ? $_FILES['up_voiturevisite'] : ""; 
    $year = date('Y');

    $papier_query = "SELECT V.pimm_voiture,A.file_assurance,VN.file_vignette,VT.file_visite
        FROM voiture AS V
        LEFT JOIN marque_voiture AS M on M.id_marquevoiture  = V.id_marquemodel
        LEFT JOIN assurance_voiture AS A on A.id_voiture = V.id_voiture
        LEFT JOIN vignette_voiture AS VN on VN.id_voiture = V.id_voiture
        LEFT JOIN visite_voiture AS VT on VT.id_voiture = V.id_voiture 
        WHERE V.id_voiture='$up_voitureid'
        AND A.etat_assurance = '1' 
        AND VN.etat_vignette = '1'
        AND VT.etat_visite = '1'";
    $papier_result = mysqli_query($conn, $papier_query);
    $papier = mysqli_fetch_assoc($papier_result);
    $pimm = $papier["pimm_voiture"];
    $Namefile = md5($pimm);
    if($up_voitureassurance != ""){
        $emplacement_assurance = "uploadfile/voiture/assurance/";
        $file_assurance = $emplacement_assurance . basename($_FILES["up_voitureassurance"]["name"]);
        $uploadOk_assurance = 1;
        $type_assurance = strtolower(pathinfo($file_assurance,PATHINFO_EXTENSION));
        if($type_assurance != "jpg" && $type_assurance != "png" && $type_assurance != "jpeg" && $type_assurance != "gif" ) {
            $up_voitureassurance = $papier["file_assurance"];
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_assurance = 0;
        }  
        if ($uploadOk_assurance != 0) {
            $sizeAssur = $_FILES["up_voitureassurance"]["size"];
          if ($sizeAssur >= 500) {
            compressImage($_FILES["up_voitureassurance"]["tmp_name"], $emplacement_assurance .$Namefile."_".date("Y").".".$type_assurance, 60);
            $up_voitureassurance = $Namefile."_".date("Y").".".$type_assurance;
          } else {
            move_uploaded_file($_FILES["up_voitureassurance"]["tmp_name"], $emplacement_assurance .$Namefile."_".date("Y").".".$type_assurance) ;
            $up_voitureassurance = $Namefile."_".date("Y").".".$type_assurance;
        }
        }
    }else{
        $up_voitureassurance = $papier["file_assurance"];
    }

    if($up_voiturevignette != ""){
        $emplacement_vignette = "uploadfile/voiture/vignette/";
        $file_vignette = $emplacement_vignette . basename($_FILES["up_voiturevignette"]["name"]);
        $uploadOk_vignette = 1;
        $type_vignette = strtolower(pathinfo($file_vignette,PATHINFO_EXTENSION));
        if($type_vignette != "jpg" && $type_vignette != "png" && $type_vignette != "jpeg" && $type_vignette != "gif" ) {
            $up_voiturevignette = $papier["file_vignette"];
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_vignette = 0;
        }  
        if ($uploadOk_vignette != 0) {
            $sizeVig = $_FILES["up_voiturevignette"]["size"];
          if ($sizeVig >= 500) {
            compressImage($_FILES["up_voiturevignette"]["tmp_name"], $emplacement_vignette .$Namefile."_".date("Y").".".$type_vignette, 60);
            $up_voiturevignette = $Namefile."_".date("Y").".".$type_vignette;
          } else {
            move_uploaded_file($_FILES["up_voiturevignette"]["tmp_name"], $emplacement_vignette .$Namefile."_".date("Y").".".$type_vignette) ;
            $up_voiturevignette = $Namefile."_".date("Y").".".$type_vignette;
        }
        }
    }else{
        $up_voiturevignette = $papier["file_vignette"];
    }

    if($up_voiturevisite != ""){
        $emplacement_visite = "uploadfile/voiture/visite_technique/";
        $file_visite = $emplacement_visite . basename($_FILES["up_voiturevisite"]["name"]);
        $uploadOk_visite = 1;
        $type_visite = strtolower(pathinfo($file_visite,PATHINFO_EXTENSION));
        if($type_visite != "jpg" && $type_visite != "png" && $type_visite != "jpeg" && $type_visite != "gif" ) {
            $up_voiturevisite = $papier["file_visite"];
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_visite = 0;
        }  
        if ($uploadOk_visite != 0) {
            $sizeVis = $_FILES["up_voiturevisite"]["size"];
          if ($sizeVis >= 500) {
            compressImage($_FILES["up_voiturevisite"]["tmp_name"], $emplacement_visite .$Namefile."_".date("Y").".".$type_visite, 60);
            $up_voiturevisite = $Namefile."_".date("Y").".".$type_visite;
          } else {
            move_uploaded_file($_FILES["up_voiturevisite"]["tmp_name"], $emplacement_visite .$Namefile."_".date("Y").".".$type_visite) ;
            $up_voiturevisite = $Namefile."_".date("Y").".".$type_visite;
        }
        }
    }else{
        $up_voiturevisite = $papier["file_visite"];
    }  
    $query_visite = "UPDATE visite_voiture 
    SET file_visite = '$up_voiturevisite'
    WHERE id_voiture = '$up_voitureid'
    AND etat_visite = '1'";

    $query_vignette = "UPDATE vignette_voiture 
    SET file_vignette = '$up_voiturevignette'
    WHERE id_voiture = '$up_voitureid'
    AND etat_vignette = '1'";

     $query_assurance = "UPDATE assurance_voiture 
     SET file_assurance = '$up_voitureassurance'
     WHERE id_voiture = '$up_voitureid'
     AND etat_assurance = '1' ";
     
     $update_assurance = mysqli_query($conn, $query_assurance);
     $update_visite = mysqli_query($conn, $query_visite);
    $update_vignette = mysqli_query($conn, $query_vignette);
    if (!$update_assurance || !$update_vignette || !$update_visite) {
        echo "<div class='text-echec'>$msg_update_echec voiture !</div>";
        return;
    } else {
        echo "<div class='text-checked'>La voiture $msg_update_succés</div>";
        return;
    }
}

function PapierYearArchivage(){
    global $conn;
    $idVoiture = $_POST['idVoiture'];
    $query = "SELECT YEAR(date_created_assurance)   AS years
    FROM assurance_voiture
    WHERE id_voiture='$idVoiture'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 'success', 'html' =>  $row]);
        }
        echo json_encode(['status' => 'failed']);
}

// Marque Voiture

function display_marquevoiture_record()
{
    global $conn;

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Marque</th>
            <th class="border-top-0">Modèle</th>
            <th class="border-top-0">Prix Location (DT/Jour)</th>
            <th class="border-top-0">Actions</th>      
        </tr>
    </thead>
    <tbody>';
    $query = "SELECT * 
        FROM marque_voiture 
        WHERE action_marquevoiture = '1'
        ORDER BY id_marquevoiture ASC";
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['marque'] . '</td>
            <td>' . $row['model'] . '</td>
            <td><button class="btn btn-add-red" title="Afficher le prix" id="btn-vue-prixmarque" data-id=' . $row['id_marquevoiture'] . '>Afficher le prix</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Modifier la marque voiture" class="btn" style="font-size: 2px;" id="btn-edit-marquevoiture" data-id=' . $row['id_marquevoiture'] . '>
                    <i class="lni lni-pencil-alt iconaction"></i></button>
                    <button type="button" title="Supprimer la marque voiture" class="btn" style="font-size: 2px;" id="btn-delete-marquevoiture" data-id1=' . $row['id_marquevoiture'] . '>
                    <i class="lni lni-trash iconaction"></i></button>
                </div>
            </td>
        </tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchMarqueVoiture()
{
    global $conn;

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Marque</th>
            <th class="border-top-0">Modèle</th>
            <th class="border-top-0">Prix Location (DT/Jour)</th>
            <th class="border-top-0">Actions</th>      
        </tr>
    </thead>
    <tbody>';
    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        $query = ($query = "SELECT * 
        FROM marque_voiture 
        WHERE action_marquevoiture = '1'
        AND (marque LIKE ('%" . $search . "%') OR model LIKE ('%" . $search . "%'))
            ORDER BY id_marquevoiture ASC");
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['marque'] . '</td>
                    <td>' . $row['model'] . '</td>
                    <td><button class="btn btn-add-red" title="Afficher le prix" id="btn-vue-prixmarque" data-id=' . $row['id_marquevoiture'] . '>Afficher le prix</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" title="Modifier la marque voiture" class="btn" style="font-size: 2px;" id="btn-edit-marquevoiture" data-id=' . $row['id_marquevoiture'] . '>
                            <i class="lni lni-pencil-alt iconaction"></i></button>
                            <button type="button" title="Supprimer la marque voiture" class="btn" style="font-size: 2px;" id="btn-delete-marquevoiture" data-id1=' . $row['id_marquevoiture'] . '>
                            <i class="lni lni-trash iconaction"></i></button>
                        </div>
                    </td>
                </tr>';
                $i += 1; 
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_marquevoiture_record();
    }
}

function InsertMarqueVoiture()
{
    global $conn;
    global $msg_insert_succés;

    $voituremarque = $_POST['voituremarque'];
    $voituremodel = $_POST['voituremodel'];
    $prixjan = $_POST['prixjan'];
    $prixfev = $_POST['prixfev'];
    $prixmars = $_POST['prixmars'];
    $prixavril = $_POST['prixavril'];
    $prixmai = $_POST['prixmai'];
    $prixjuin = $_POST['prixjuin'];
    $prixjuil = $_POST['prixjuil'];
    $prixaout = $_POST['prixaout'];
    $prixsept = $_POST['prixsept'];
    $prixoct = $_POST['prixoct'];
    $prixnov = $_POST['prixnov'];
    $prixdec = $_POST['prixdec'];
    $prix = array($prixjan, $prixfev, $prixmars, $prixavril, $prixmai, $prixjuin, $prixjuil, $prixaout, $prixsept, $prixoct, $prixnov, $prixdec);

    $sql_e = "SELECT * FROM marque_voiture WHERE marque = '$voituremarque' AND model = '$voituremodel' AND action_marquevoiture = '1'";
    $res_e = mysqli_query($conn, $sql_e);
    if (mysqli_num_rows($res_e) > 0) {
        echo '<div class="text-echec">Désolé ... la marque est déjà pris!</div>';
    } else {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT INTO marque_voiture(marque,model,date_created_marque,date_updated_marque) 
            VALUES ('$voituremarque','$voituremodel','$date','$date') ";

        $result = mysqli_query($conn, $query);
        if ($result) {
            $query_id_marque = "SELECT MAX(id_marquevoiture) FROM marque_voiture";
            $result_id_marque = mysqli_query($conn, $query_id_marque);
            $id_marque = mysqli_fetch_row($result_id_marque);
            $id_marque = $id_marque[0];

            for ($i = 0; $i < 12; $i++) {
                $j = $i + 1;
                $query_prix_marque = "INSERT INTO prix_marque_voiture(id_marque_voiture,id_month,prix_marquevoiture) 
                VALUES ('$id_marque','$j','$prix[$i]') ";
                $result_prix_marque = mysqli_query($conn, $query_prix_marque);
            }
            if ($result_prix_marque) {
                echo "<div class='text-checked'>La marque voiture $msg_insert_succés</div>";
            }
        } else {
            echo "<div class='text-echec'>Vous avez rencontré un problème lors de l'ajout du marque voiture</div>";
        }
    }
}

function get_prix_marquevoiture_data()
{
    global $conn;
    $prix_marquevoiture_data = [];
    $id_marquevoiture = $_POST['id_marquevoiture'];
    $table[] = $id_marquevoiture;
    for ($i = 1; $i < 13; $i++) {
        $query = "SELECT prix_marquevoiture
            FROM prix_marque_voiture 
            WHERE id_marque_voiture = '$id_marquevoiture'
            AND id_month = '$i'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $table[] = $row['prix_marquevoiture'];
    }
    $prix_marquevoiture_data = $table;
    echo json_encode($prix_marquevoiture_data);
}

function update_prix_marquevoiture_value()
{
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;

    $up_prix_marquevoitureid = $_POST["up_prix_marquevoitureid"];
    $up_prixjan = $_POST["up_prixjan"];
    $up_prixfev = $_POST["up_prixfev"];
    $up_prixmars = $_POST["up_prixmars"];
    $up_prixavr = $_POST["up_prixavr"];
    $up_prixmai = $_POST["up_prixmai"];
    $up_prixjuin = $_POST["up_prixjuin"];
    $up_prixjuil = $_POST["up_prixjuil"];
    $up_prixaout = $_POST["up_prixaout"];
    $up_prixsep = $_POST["up_prixsep"];
    $up_prixoct = $_POST["up_prixoct"];
    $up_prixnov = $_POST["up_prixnov"];
    $up_prixdec = $_POST["up_prixdec"];
    $table = array($up_prixjan, $up_prixfev, $up_prixmars, $up_prixavr, $up_prixmai, $up_prixjuin, $up_prixjuil, $up_prixaout, $up_prixsep, $up_prixoct, $up_prixnov, $up_prixdec);
    for ($i = 1; $i < 13; $i++) {
        $j = $i - 1;
        $update_query = "UPDATE prix_marque_voiture 
                            SET 
                                prix_marquevoiture = '$table[$j]'
                            WHERE id_marque_voiture = $up_prix_marquevoitureid AND id_month = '$i'";
        $update_result = mysqli_query($conn, $update_query);
    }
    if (!$update_result) {
        echo "<div class='text-echec'>$msg_update_echec prix marque !</div>";
        return;
    }
    echo "<div class='text-checked'>Le prix du marque $msg_update_succés</div>";
    return;

}


function get_marquevoiture_record()
{
    global $conn;
    $id_marquevoiture = $_POST['id_marquevoiture'];
    $query = " SELECT *
    FROM marque_voiture 
    WHERE action_marquevoiture = '1'
    AND id_marquevoiture = '$id_marquevoiture'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $marquevoiture_data = [];
        $marquevoiture_data[0] = $row['id_marquevoiture'];
        $marquevoiture_data[1] = $row['marque'];
        $marquevoiture_data[2] = $row['model'];
    }
    echo json_encode($marquevoiture_data);
}

function update_marquevoiture_value()
{
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;

    $up_marquevoitureid = $_POST["up_marquevoitureid"];
    $up_voituremarque = $_POST["up_voituremarque"];
    $up_voituremodel = $_POST["up_voituremodel"];
    $sql_e = "SELECT * 
                FROM marque_voiture 
                WHERE marque = '$up_voituremarque' 
                AND model = '$up_voituremodel' 
                AND action_marquevoiture = '1'
                AND id_marquevoiture != '$up_marquevoitureid'";
    $res_e = mysqli_query($conn, $sql_e);
    if (mysqli_num_rows($res_e) > 0) {
        echo '<div class="text-echec" role="alert">Désolé ... la marque est déjà pris!</div>';
        return;
    } else {
        $date = date('Y-m-d H:i:s');
        $update_query = "UPDATE marque_voiture 
                            SET 
                                marque = '$up_voituremarque',
                                model = '$up_voituremodel',
                                date_updated_marque = '$date'
                            WHERE id_marquevoiture = $up_marquevoitureid";
        $update_result = mysqli_query($conn, $update_query);
        if (!$update_result) {
            echo "<div class='text-echec'>$msg_update_echec marque voiture !</div>";
            return;
        }
        echo "<div class='text-checked'>La marque voiture $msg_update_succés</div>";
        return;
    }
}

function delete_marquevoiture_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;

    $id_marquevoiture = $_POST['id_marquevoiture'];
    $date = date('Y-m-d H:i:s');
    $query = "UPDATE marque_voiture 
                SET 
                    action_marquevoiture = '0',
                    date_updated_marque ='$date' 
                WHERE id_marquevoiture = '$id_marquevoiture'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<div class='text-checked'>La marque voiture $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec marque voiture !</div>";
    }
}

// Stock voiture

function display_stockvoiture_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">PIMM</th>
            <th class="border-top-0">Marque/Modèle</th>
            <th class="border-top-0">Type de carburant</th>
            <th class="border-top-0">Boite de vitesse</th>
            <th class="border-top-1">Localisation</th>
            <th class="border-top-1">Disponibilité</th>
            <th class="border-top-0">Carte grise</th>
            <th class="border-top-0">Transfert</th>      
        </tr>
    </thead>
    <tbody>';
    if($id_role == 2){
        $query = "SELECT * 
        FROM voiture as V 
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
        LEFT JOIN agence AS A on V.id_agence = A.id_agence
        WHERE action_voiture = '1'
        AND V.id_agence = $id_agence
        ORDER BY id_voiture ASC";
    }else{
        $query = "SELECT * 
        FROM voiture as V 
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
        LEFT JOIN agence AS A on V.id_agence = A.id_agence
        WHERE action_voiture = '1'
        ORDER BY id_voiture ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $disponibilte = disponibilite_Vehicule($row['id_voiture']);
        $class = "etat etatactif";
        if ($disponibilte == 'En entretien') {
            $class = "etat etatentretien";
        } 
        if ($disponibilte == 'Loué') {
            $row['nom_agence'] = localisation_Vehicule($row['id_voiture']);
            $class = "etat etatinactif";
        } 
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['pimm_voiture'] . '</td>
            <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
            <td>' . $row['label_carburant'] . '</td>
            <td>' . $row['boitevitesse_voiture'] . '</td>
            <td>' . $row['nom_agence'] . '</td>
            <td style="height: 70px;"><center><div class="'.$class.'">' . $disponibilte . '</div></center></td>
            <td><a href="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '"></a></td>
            <td>';
            if ($disponibilte != "Loué") {
                $value .= '<div class="btn-group" role="group">
                    <button type="button" title="Transférer la voiture" class="btn" style="font-size: 2px;" id="btn-transfert-voiture" data-id=' . $row['id_voiture'] . '>
                    <i class="bx bx-transfer iconaction"></i></button>
                </div>';
            }
        $value .= '</td></tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchStockVoiture()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">PIMM</th>
            <th class="border-top-0">Marque/Modèle</th>
            <th class="border-top-0">Type de carburant</th>
            <th class="border-top-0">Boite de vitesse</th>
            <th class="border-top-1">Localisation</th>
            <th class="border-top-1">Disponibilité</th>
            <th class="border-top-0">Carte grise</th>
            <th class="border-top-0">Assurance</th>
            <th class="border-top-0">Transfert</th>     
        </tr>
    </thead>
    <tbody>';
    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = "SELECT * 
            FROM voiture as V 
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
            LEFT JOIN agence AS A on V.id_agence = A.id_agence
            WHERE action_voiture = '1'
            AND V.id_agence = $id_agence
            AND (pimm_voiture LIKE ('%" . $search . "%') 
                OR marque LIKE ('%" . $search . "%')
                OR model LIKE ('%" . $search . "%')
                OR label_carburant LIKE ('%" . $search . "%')
                OR boitevitesse_voiture LIKE ('%" . $search . "%'))
                ORDER BY id_voiture ASC";
        }else{
            $query = "SELECT * 
            FROM voiture as V 
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            LEFT JOIN carburant_voiture AS C on V.id_typecarburant = C.id_carburantvoiture
            LEFT JOIN agence AS A on V.id_agence = A.id_agence
            WHERE action_voiture = '1'
            AND (pimm_voiture LIKE ('%" . $search . "%') 
                OR marque LIKE ('%" . $search . "%')
                OR model LIKE ('%" . $search . "%')
                OR label_carburant LIKE ('%" . $search . "%')
                OR boitevitesse_voiture LIKE ('%" . $search . "%'))
                ORDER BY id_voiture ASC";
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $disponibilte = disponibilite_Vehicule($row['id_voiture']);
                $class = "etat etatactif";
                if ($disponibilte == 'Loué') {
                    $row['nom_agence'] = localisation_Vehicule($row['id_voiture']);
                    $class = "etat etatinactif";
                } 
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['pimm_voiture'] . '</td>
                    <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
                    <td>' . $row['label_carburant'] . '</td>
                    <td>' . $row['boitevitesse_voiture'] . '</td>
                    <td>' . $row['nom_agence'] . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">' . $disponibilte . '</div></center></td>
                    <td><a href="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/cartegrise/' . $row["cartegrise_voiture"] . '"></a></td>
                    <td><a href="uploadfile/voiture/assurance/' . $row["assurance_voiture"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/assurance/' . $row["assurance_voiture"] . '"></a></td>
                    <td>';
                    if ($disponibilte != "Loué") {
                        $value .= '<div class="btn-group" role="group">
                            <button type="button" title="Transférer la voiture" class="btn" style="font-size: 2px;" id="btn-transfert-voiture" data-id=' . $row['id_voiture'] . '>
                            <i class="bx bx-transfer iconaction"></i></button>
                        </div>';
                    }
                $value .= '</td></tr>';
                $i += 1;  
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_stockvoiture_record();
    }
}

function update_agence_voiture()
{
    global $conn;
    $id_voiture = $_POST['id_voiture'];
    $up_voitureAgence = $_POST['up_voitureAgence'];

    $update_query = "UPDATE voiture 
                        SET id_agence='$up_voitureAgence'
                        WHERE id_voiture = $id_voiture";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        echo "<div class='text-checked'>La voiture est transférée avec succès !</div>";
    } else {
        echo "<div class='text-echec'>Vous avez rencontré un problème lors du transfert de la voiture !</div>";
    }
}

// Contrat

function display_contrat_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début</th>
            <th class="border-top-0">Date Fin</th>
            <th class="border-top-0">Prix Contrat</th>
            <th class="border-top-0">Pimm Voiture</th>
            <th class="border-top-0">Marque Voiture</th>
            <th class="border-top-0">Nom Client</th>
            <th class="border-top-0">Email Client</th>
            <th class="border-top-0">Agence</th>
            <th class="border-top-0">Actions</th>      
        </tr>
    </thead>
    <tbody>';

    if($id_role == 2){
        $query = "SELECT * 
        FROM contrat as C 
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
        LEFT JOIN client AS CL on CL.id_client = C.id_client
        LEFT JOIN agence AS A on A.id_agence = C.id_agence
        WHERE action_contrat = '1'
        AND C.id_agence = $id_agence
        AND datefin_contrat >= DATE(NOW())
        ORDER BY id_contrat ASC";
    }else{
        $query = "SELECT * 
        FROM contrat as C 
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
        LEFT JOIN client AS CL on CL.id_client = C.id_client
        LEFT JOIN agence AS A on A.id_agence = C.id_agence
        WHERE action_contrat = '1'
        AND datefin_contrat >= DATE(NOW())
        ORDER BY id_contrat ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['datedebut_contrat'] . '</td>
            <td>' . $row['datefin_contrat'] . '</td>
            <td>' . $row['prix_contrat'] . '</td>
            <td>' . $row['pimm_voiture'] . '</td>
            <td>' . $row['marque'] . ' ' . $row['model'] .'</td>
            <td>' . $row['nom_client'] . '</td>
            <td>' . $row['email_client'] . '</td>
            <td>' . $row['nom_agence'] . '</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                    <i class="bx bx-download iconaction"></i></button>
                    <button type="button" title="Supprimer le contrat" class="btn" style="font-size: 2px;" id="btn-delete-contrat" data-id1=' . $row['id_contrat'] . '>
                    <i class="lni lni-trash iconaction"></i></button>
                </div>
            </td>
        </tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchContrat()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début</th>
            <th class="border-top-0">Date Fin</th>
            <th class="border-top-0">Prix Contrat</th>
            <th class="border-top-0">Pimm Voiture</th>
            <th class="border-top-0">Marque Voiture</th>
            <th class="border-top-0">Nom Client</th>
            <th class="border-top-0">Email Client</th>
            <th class="border-top-0">Agence</th>
            <th class="border-top-0">Actions</th>      
        </tr>
    </thead>
    <tbody>';

    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = ("SELECT * 
            FROM contrat as C 
            LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
            LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
            LEFT JOIN client AS CL on CL.id_client = C.id_client
            LEFT JOIN agence AS A on A.id_agence = C.id_agence
            WHERE action_contrat = '1'
            AND C.id_agence = $id_agence
            AND datefin_contrat >= DATE(NOW())
            AND (pimm_voiture LIKE ('%" . $search . "%') 
                OR marque LIKE ('%" . $search . "%')
                OR model LIKE ('%" . $search . "%')
                OR nom_client LIKE ('%" . $search . "%')
                OR email_client LIKE ('%" . $search . "%')
                OR nom_agence LIKE ('%" . $search . "%')
                OR datedebut_contrat LIKE ('%" . $search . "%')
                OR datefin_contrat LIKE ('%" . $search . "%')
                OR prix_contrat LIKE ('%" . $search . "%'))
                ORDER BY id_contrat ASC"); 
        }else{
            $query = ("SELECT * 
            FROM contrat as C 
            LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
            LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
            LEFT JOIN client AS CL on CL.id_client = C.id_client
            LEFT JOIN agence AS A on A.id_agence = C.id_agence
            WHERE action_contrat = '1'
            AND datefin_contrat >= DATE(NOW())
            AND (pimm_voiture LIKE ('%" . $search . "%') 
                OR marque LIKE ('%" . $search . "%')
                OR model LIKE ('%" . $search . "%')
                OR nom_client LIKE ('%" . $search . "%')
                OR email_client LIKE ('%" . $search . "%')
                OR nom_agence LIKE ('%" . $search . "%')
                OR datedebut_contrat LIKE ('%" . $search . "%')
                OR datefin_contrat LIKE ('%" . $search . "%')
                OR prix_contrat LIKE ('%" . $search . "%'))
                ORDER BY id_contrat ASC"); 
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['datedebut_contrat'] . '</td>
                    <td>' . $row['datefin_contrat'] . '</td>
                    <td>' . $row['prix_contrat'] . '</td>
                    <td>' . $row['pimm_voiture'] . '</td>
                    <td>' . $row['marque'] . ' ' . $row['model'] .'</td>
                    <td>' . $row['nom_client'] . '</td>
                    <td>' . $row['email_client'] . '</td>
                    <td>' . $row['nom_agence'] . '</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                            <i class="bx bx-download iconaction"></i></button>
                            <button type="button" title="Supprimer le contrat" class="btn" style="font-size: 2px;" id="btn-delete-contrat" data-id1=' . $row['id_contrat'] . '>
                            <i class="lni lni-trash iconaction"></i></button>
                        </div>
                    </td>
                </tr>';
                $i += 1; 
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_contrat_record();
    }
}

function getIntersection($periodStart,$periodEnd,$timeIn,$timeOut){

    $interval = $timeIn->diff($periodStart);
    $diff = (int)$interval->format('%R%a');
  
    $interval1 = $timeOut->diff($periodEnd);
    $diff1 = (int)$interval1->format('%R%a');
  
    $interval2 = $timeOut->diff($periodStart);
    $diff2 = (int)$interval2->format('%R%a');
  
    $interval3 = $timeIn->diff($periodEnd);
    $diff3 = (int)$interval3->format('%R%a');
  
    $interval4 = $periodStart->diff($periodEnd);
    $diff4 = (int)$interval4->format('%R%a');
  
    $interval5 = $timeIn->diff($timeOut);
    $diff5 = (int)$interval5->format('%R%a');
  
    if($diff == 0 && $diff1 == 0) {
      return $diff5."if1";
    }
    if($diff < 0 && $diff1 > 0) {
      $f5=abs($diff5)+1;
      return $f5."if12";
    }
    if($diff < 0 && $diff1 <= 0) {
        $al=abs($diff3)+1;
      return $al."if2";
    }	
    if($diff >= 0 && $diff1 <= 0) {
      return $diff4."if3";
    }	
    if($diff >= 0 && $diff1 >= 0) {
      return $diff2."if4";
    }	
}

function InsertContrat()
{
    global $conn;
    global $msg_insert_succés;

    $DateDebutContrat = $_POST['DateDebutContrat'];
    $DateFinContrat = $_POST['DateFinContrat'];
    $ClientContrat = $_POST['ClientContrat'];
    $AgenceContrat = $_POST['AgenceContrat'];
    $VoitureContrat = $_POST['VoitureContrat'];

    if($_SESSION['Role'] == 2){
        $AgenceContrat = $_SESSION['Agence'];
    }
    if ($AgenceContrat != "0") {
        $query_getprice = "SELECT id_marquevoiture
                            FROM marque_voiture AS M
                            LEFT JOIN voiture AS V ON M.id_marquevoiture = V.id_marquemodel
                            WHERE id_voiture = $VoitureContrat";
        $result_price = mysqli_query($conn, $query_getprice);
        $row_price = mysqli_fetch_assoc($result_price);
        $marque_voiture = $row_price['id_marquevoiture'];

        $Date_DebutContrat = date_create($DateDebutContrat);
        $Date_FinContrat = date_create($DateFinContrat);
        $allday = 0;
        $PrixContrat = 0;
        $year1 = date('Y', strtotime($DateDebutContrat));
        $year2 = date('Y', strtotime($DateFinContrat));

        if($year1 != $year2){
            $sql = "SELECT CONCAT('$year1-',id_month,'-01') AS date_debut,LAST_DAY(CONCAT('$year1-',id_month,'-01')) AS date_fin,prix_marquevoiture 
            FROM prix_marque_voiture
            WHERE id_marque_voiture = '$marque_voiture'
            UNION
            SELECT CONCAT('$year2-',id_month,'-01') AS date_debut,LAST_DAY(CONCAT('$year2-',id_month,'-01')) AS date_fin,prix_marquevoiture 
            FROM prix_marque_voiture
            WHERE id_marque_voiture = '$marque_voiture'";
        }else{
            $sql = "SELECT CONCAT('$year1-',id_month,'-01') AS date_debut,LAST_DAY(CONCAT('$year1-',id_month,'-01')) AS date_fin,prix_marquevoiture 
            FROM prix_marque_voiture
            WHERE id_marque_voiture = '$marque_voiture'";
        }
        
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
          $date_debut = $row['date_debut'];
          $date_fin = $row['date_fin'];
          $date_debut = date_create($date_debut);
          $date_fin = date_create($date_fin);
          $interval1 = $Date_DebutContrat->diff($date_fin);
          $diff1 = (int)$interval1->format('%R%a');
          $interval2 = $date_debut->diff($Date_FinContrat);
          $diff2 = (int)$interval2->format('%R%a');
        
          if($diff2>=0 && $diff1>=0){
            $intersection = getIntersection($Date_DebutContrat,$Date_FinContrat,$date_debut,$date_fin);
            $days = abs($intersection);
            $prix = $days * $row['prix_marquevoiture'];      
            $PrixContrat = $PrixContrat + $prix;
            $allday = $allday + $days;
          }
        }

        $query = "INSERT INTO 
        contrat(id_client,id_agence,id_voiture,datedebut_contrat,datefin_contrat,prix_contrat) 
        VALUES ('$ClientContrat','$AgenceContrat','$VoitureContrat','$DateDebutContrat','$DateFinContrat','$PrixContrat')";
        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<div class='text-checked'>Le contrat $msg_insert_succés</div>";
        } else {
            echo "<div class='text-echec'>Erreur lors de l'ajout du contrat</div>";
        }
    } else {
        echo "<div class='text-echec'>SVP! Choisissez l'agence</div>";
    }
}

function delete_contrat_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;

    $Del_ID = $_POST['id_contrat'];
    $date = date('Y-m-d H:i:s');
    $query = "UPDATE contrat 
                SET 
                    action_contrat = '0',
                    date_updated_contrat = '$date' 
                WHERE id_contrat = '$Del_ID'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<div class='text-checked'>Le contrat $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec contrat !</div>";
    }
}

// Contrat Archive

function display_contrat_archive_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début</th>
            <th class="border-top-0">Date Fin</th>
            <th class="border-top-0">Prix Contrat</th>
            <th class="border-top-0">Pimm Voiture</th>
            <th class="border-top-0">Marque Voiture</th>
            <th class="border-top-0">Nom Client</th>
            <th class="border-top-0">Email Client</th>
            <th class="border-top-0">Agence</th>
            <th class="border-top-0">Actions</th>      
        </tr>
    </thead>
    <tbody>';
    if($id_role == 2){
        $query = "SELECT * 
        FROM contrat as C 
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
        LEFT JOIN client AS CL on CL.id_client = C.id_client
        LEFT JOIN agence AS A on A.id_agence = C.id_agence
        WHERE action_contrat = '1'
        AND C.id_agence = $id_agence
        AND datefin_contrat < DATE(NOW())
        ORDER BY id_contrat ASC"; 
    }else{
        $query = "SELECT * 
        FROM contrat as C 
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
        LEFT JOIN client AS CL on CL.id_client = C.id_client
        LEFT JOIN agence AS A on A.id_agence = C.id_agence
        WHERE action_contrat = '1'
        AND datefin_contrat < DATE(NOW())
        ORDER BY id_contrat ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['datedebut_contrat'] . '</td>
            <td>' . $row['datefin_contrat'] . '</td>
            <td>' . $row['prix_contrat'] . '</td>
            <td>' . $row['pimm_voiture'] . '</td>
            <td>' . $row['marque'] . ' ' . $row['model'] .'</td>
            <td>' . $row['nom_client'] . '</td>
            <td>' . $row['email_client'] . '</td>
            <td>' . $row['nom_agence'] . '</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                    <i class="bx bx-download iconaction"></i></button>
                </div>
            </td>
        </tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchContratArchive()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début</th>
            <th class="border-top-0">Date Fin</th>
            <th class="border-top-0">Prix Contrat</th>
            <th class="border-top-0">Pimm Voiture</th>
            <th class="border-top-0">Marque Voiture</th>
            <th class="border-top-0">Nom Client</th>
            <th class="border-top-0">Email Client</th>
            <th class="border-top-0">Agence</th>
            <th class="border-top-0">Actions</th>      
        </tr>
    </thead>
    <tbody>';

    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = ("SELECT * 
            FROM contrat as C 
            LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
            LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
            LEFT JOIN client AS CL on CL.id_client = C.id_client
            LEFT JOIN agence AS A on A.id_agence = C.id_agence
            WHERE action_contrat = '1'
            AND C.id_agence = $id_agence
            AND datefin_contrat < DATE(NOW())
            AND (pimm_voiture LIKE ('%" . $search . "%') 
                OR marque LIKE ('%" . $search . "%')
                OR model LIKE ('%" . $search . "%')
                OR nom_client LIKE ('%" . $search . "%')
                OR email_client LIKE ('%" . $search . "%')
                OR nom_agence LIKE ('%" . $search . "%')
                OR datedebut_contrat LIKE ('%" . $search . "%')
                OR datefin_contrat LIKE ('%" . $search . "%')
                OR prix_contrat LIKE ('%" . $search . "%'))
                ORDER BY id_contrat ASC");
        }else{
            $query = ("SELECT * 
            FROM contrat as C 
            LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
            LEFT JOIN marque_voiture AS M on M.id_marquevoiture = V.id_marquemodel
            LEFT JOIN client AS CL on CL.id_client = C.id_client
            LEFT JOIN agence AS A on A.id_agence = C.id_agence
            WHERE action_contrat = '1'
            AND datefin_contrat < DATE(NOW())
            AND (pimm_voiture LIKE ('%" . $search . "%') 
                OR marque LIKE ('%" . $search . "%')
                OR model LIKE ('%" . $search . "%')
                OR nom_client LIKE ('%" . $search . "%')
                OR email_client LIKE ('%" . $search . "%')
                OR nom_agence LIKE ('%" . $search . "%')
                OR datedebut_contrat LIKE ('%" . $search . "%')
                OR datefin_contrat LIKE ('%" . $search . "%')
                OR prix_contrat LIKE ('%" . $search . "%'))
                ORDER BY id_contrat ASC");
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['datedebut_contrat'] . '</td>
                    <td>' . $row['datefin_contrat'] . '</td>
                    <td>' . $row['prix_contrat'] . '</td>
                    <td>' . $row['pimm_voiture'] . '</td>
                    <td>' . $row['marque'] . ' ' . $row['model'] .'</td>
                    <td>' . $row['nom_client'] . '</td>
                    <td>' . $row['email_client'] . '</td>
                    <td>' . $row['nom_agence'] . '</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                            <i class="bx bx-download iconaction"></i></button>
                        </div>
                    </td>
                </tr>';
                $i += 1; 
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_contrat_archive_record();
    }
}

// Contrat Historique

function display_contrat_historique_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début Contrat</th>
            <th class="border-top-0">Date Fin Contrat</th>
            <th class="border-top-0">Pimm Voiture</th>
            <th class="border-top-0">Nom Client</th>
            <th class="border-top-0">Action</th>
            <th class="border-top-0">Date Action</th>
            <th class="border-top-0">Le contrat</th>           
        </tr>
    </thead>
    <tbody>';
    if($id_role == 2){
        $query = "SELECT *
        FROM contrat AS C
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        LEFT JOIN client AS CL on CL.id_client = C.id_client
        WHERE C.id_agence = $id_agence
        ORDER BY id_contrat ASC";
    }else{
        $query = "SELECT *
        FROM contrat AS C
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        LEFT JOIN client AS CL on CL.id_client = C.id_client
        ORDER BY id_contrat ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $class = "etat etatactif";
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['datedebut_contrat'] . '</td>
            <td>' . $row['datefin_contrat'] . '</td>
            <td>' . $row['pimm_voiture'] . '</td>
            <td>' . $row['nom_client'] . '</td>
            <td style="height: 70px;"><center><div class="'.$class.'">  AJOUT </div></center></td>
            <td>' . $row['date_created_contrat'] . '</td>
            <td>
                <div class="btn-group" role="group">
                    <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                    <i class="bx bx-download iconaction"></i></button>
                </div>
            </td>
        </tr>';
        $i += 1;  
        if($row['action_contrat'] == 0){
            $class = "etat etatinactif"; 
            $value .= '<tr>
                <td>' . $i . '</td>
                <td>' . $row['datedebut_contrat'] . '</td>
                <td>' . $row['datefin_contrat'] . '</td>
                <td>' . $row['pimm_voiture'] . '</td>
                <td>' . $row['nom_client'] . '</td>
                <td style="height: 70px;"><center><div class="'.$class.'">  SUPPRESSION </div></center></td>
                <td>' . $row['date_updated_contrat'] . '</td>
                <td>
                    <div class="btn-group" role="group">
                        <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                        <i class="bx bx-download iconaction"></i></button>
                    </div>
                </td>
            </tr>';
            $i += 1;  
        }
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchContratHistorique()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];
    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début Contrat</th>
            <th class="border-top-0">Date Fin Contrat</th>
            <th class="border-top-0">Pimm Voiture</th>
            <th class="border-top-0">Nom Client</th>
            <th class="border-top-0">Action</th>
            <th class="border-top-0">Date Action</th>
            <th class="border-top-0">Le contrat</th>              
        </tr>
    </thead>
    <tbody>';

    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = ("SELECT * 
            FROM contrat AS C
            LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
            LEFT JOIN client AS CL on CL.id_client = C.id_client
            WHERE V.id_agence = $id_agence
            AND (datedebut_contrat LIKE ('%" . $search . "%')
                OR datefin_contrat LIKE ('%" . $search . "%')
                OR pimm_voiture LIKE ('%" . $search . "%')
                OR nom_client LIKE ('%" . $search . "%')
                OR date_created_contrat LIKE ('%" . $search . "%'))
                ORDER BY id_contrat ASC");
            
        }else{
            $query = ("SELECT * 
            FROM contrat AS C
            LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
            LEFT JOIN client AS CL on CL.id_client = C.id_client
            WHERE (datedebut_contrat LIKE ('%" . $search . "%')
                OR datefin_contrat LIKE ('%" . $search . "%')
                OR pimm_voiture LIKE ('%" . $search . "%')
                OR nom_client LIKE ('%" . $search . "%')
                OR date_created_contrat LIKE ('%" . $search . "%'))
                ORDER BY id_contrat ASC");
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $class = "etat etatactif";
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['datedebut_contrat'] . '</td>
                    <td>' . $row['datefin_contrat'] . '</td>
                    <td>' . $row['pimm_voiture'] . '</td>
                    <td>' . $row['nom_client'] . '</td>
                    <td style="height: 70px;"><center><div class="'.$class.'">AJOUT</div></center></td>
                    <td>' . $row['date_created_contrat'] . '</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                            <i class="bx bx-download iconaction"></i></button>
                        </div>
                    </td>
                </tr>';
                $i += 1;  
                if($row['action_contrat'] == 0){
                    $class = "etat etatinactif"; 
                    $value .= '<tr>
                        <td>' . $i . '</td>
                        <td>' . $row['datedebut_contrat'] . '</td>
                        <td>' . $row['datefin_contrat'] . '</td>
                        <td>' . $row['pimm_voiture'] . '</td>
                        <td>' . $row['nom_client'] . '</td>
                        <td style="height: 70px;"><center><div class="'.$class.'">SUPPRESSION</div></center></td>
                        <td>' . $row['date_updated_contrat'] . '</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" title="Consulter le contrat" class="btn" style="font-size: 2px;" id="btn-show-contrat" data-id=' . $row['id_contrat'] . '>
                                <i class="bx bx-download iconaction"></i></button>
                            </div>
                        </td>
                    </tr>';
                    $i += 1;  
                }  
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_contrat_historique_record();
    }
} 

// Entretien

function display_entretien_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th class="border-top-0">#</th>
                <th class="border-top-0">Date Début</th>
                <th class="border-top-0">Date Fin</th>
                <th class="border-top-0">Prix Entretien</th>
                <th class="border-top-0">Pimm Voiture</th>
                <th class="border-top-0">Marque Voiture</th>
                <th class="border-top-0">Commentaire</th>';
                if($id_agence ==0){
                    $value .= '<th class="border-top-0">Agence</th>';
                }
                $value .= '<th class="border-top-0">Actions</th>      
            </tr>
        </thead>
    <tbody>';

    if($id_role == 2){
        $query = "SELECT * 
        FROM entretien_voiture as E 
        LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        WHERE action_entretien = '1'
        AND datefin_entretien >= DATE(NOW())
        AND V.id_agence = $id_agence
        ORDER BY id_entretien ASC";
    }else{
        $query = "SELECT * 
        FROM entretien_voiture as E 
        LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        LEFT JOIN agence AS A on V.id_agence = A.id_agence
        WHERE action_entretien = '1'
        AND datefin_entretien >= DATE(NOW())
        ORDER BY id_entretien ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
         
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['datedebut_entretien'] . '</td>
            <td>' . $row['datefin_entretien'] . '</td>
            <td>' . $row['prix_entretien'] . '</td>
            <td>' . $row['pimm_voiture'] . '</td>
            <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
            <td>' . $row['commentaire'] . '</td>' ;
           
            if($id_agence == 0){
                $value .= '<td>' . $row['nom_agence'] . '</td>';
            }
            $value .= '<td>
                <div class="btn-group" role="group">
                    <button type="button" title="Modifier l\'entretien" class="btn" style="font-size: 2px;" id="btn-edit-entretien" data-id=' . $row['id_entretien'] . '>
                    <i class="lni lni-pencil-alt iconaction"></i></button>
                    <button type="button" title="Supprimer l\'entretien" class="btn" style="font-size: 2px;" id="btn-delete-entretien" data-id1=' . $row['id_entretien'] . '>
                    <i class="lni lni-trash iconaction"></i></button>
                </div>
            </td>
        </tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchEntretien()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th class="border-top-0">#</th>
                <th class="border-top-0">Date Début</th>
                <th class="border-top-0">Date Fin</th>
                <th class="border-top-0">Prix Entretien</th>
                <th class="border-top-0">Pimm Voiture</th>
                <th class="border-top-0">Marque Voiture</th>
                <th class="border-top-0">Commentaire</th>';
                if($id_agence ==0){
                    $value .= '<th class="border-top-0">Agence</th>';
                }
                $value .= '<th class="border-top-0">Actions</th>      
            </tr>
        </thead>
    <tbody>';

    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = ("SELECT * 
            FROM entretien_voiture as E 
            LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            WHERE action_entretien = '1'
            AND datefin_entretien >= DATE(NOW())
            AND V.id_agence = $id_agence
            AND (datedebut_entretien LIKE ('%" . $search . "%')
                    OR datefin_entretien LIKE ('%" . $search . "%') 
                    OR prix_entretien LIKE ('%" . $search . "%')       
                    OR pimm_voiture LIKE ('%" . $search . "%')
                    OR marque LIKE ('%" . $search . "%')
                    OR blockage_voiture LIKE ('%" . $search . "%')
                    OR commentaire LIKE ('%" . $search . "%')
                    OR model LIKE ('%" . $search . "%'))
                    ORDER BY id_entretien ASC");
        }else{
            $query = ("SELECT * 
            FROM entretien_voiture as E 
            LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            LEFT JOIN agence AS A on V.id_agence = A.id_agence
            WHERE action_entretien = '1'
            AND datefin_entretien >= DATE(NOW())
            AND (datedebut_entretien LIKE ('%" . $search . "%')
                    OR datefin_entretien LIKE ('%" . $search . "%') 
                    OR prix_entretien LIKE ('%" . $search . "%')       
                    OR pimm_voiture LIKE ('%" . $search . "%')
                    OR marque LIKE ('%" . $search . "%')
                    OR commentaire LIKE ('%" . $search . "%')
                    OR model LIKE ('%" . $search . "%')
                    OR nom_agence LIKE ('%" . $search . "%'))
                    ORDER BY id_entretien ASC");
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                <td>' . $i . '</td>
                <td>' . $row['datedebut_entretien'] . '</td>
                <td>' . $row['datefin_entretien'] . '</td>
                <td>' . $row['prix_entretien'] . '</td>
                <td>' . $row['pimm_voiture'] . '</td>
                <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
                <td>' . $row['commentaire'] . '</td>';
                if($id_agence == 0){
                    $value .= '<td>' . $row['nom_agence'] . '</td>';
                }
                $value .= '<td>
                    <div class="btn-group" role="group">
                        <button type="button" title="Modifier l\'entretien" class="btn" style="font-size: 2px;" id="btn-edit-entretien" data-id=' . $row['id_entretien'] . '>
                        <i class="lni lni-pencil-alt iconaction"></i></button>
                        <button type="button" title="Supprimer l\'entretien" class="btn" style="font-size: 2px;" id="btn-delete-entretien" data-id1=' . $row['id_entretien'] . '>
                        <i class="lni lni-trash iconaction"></i></button>
                    </div>
                </td>
            </tr>';
                $i += 1; 
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_entretien_record();
    }
}

function InsertEntretien()
{
    global $conn;
    global $msg_insert_succés;

    $DateDebutEntretien = $_POST['DateDebutEntretien'];
    $DateFinEntretien = $_POST['DateFinEntretien'];
    $prixentretien = $_POST['prixentretien'];
    $voiture_entretien = $_POST['voiture_entretien'];
    $blockageVoiture = $_POST['blockageVoiture'];
    $commentaire = $_POST['commentaire'];
   
    $date = date('Y-m-d H:i:s');
    $query = "INSERT INTO entretien_voiture(id_voiture,datedebut_entretien,datefin_entretien,blockage_voiture,commentaire,prix_entretien,date_created_entretien,date_updated_entretien) 
            VALUES ('$voiture_entretien','$DateDebutEntretien','$DateFinEntretien','$blockageVoiture','$commentaire','$prixentretien','$date','$date') ";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo "<div class='text-checked'>L'entretien $msg_insert_succés</div>";
    } else {
        echo "<div class='text-echec'>Vous avez rencontré un problème lors de l'ajout du voiture</div>";
    }  
}

function get_entretien_record()
{
    global $conn;
    $id_entretien = $_POST['id_entretien'];
    $query = "SELECT *
        FROM entretien_voiture
        WHERE action_entretien = '1'
        AND id_entretien='$id_entretien'";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $entretien_data = [];
        $entretien_data[0] = $row['id_entretien'];
        $entretien_data[1] = $row['datedebut_entretien'];
        $entretien_data[2] = $row['datefin_entretien'];
        $entretien_data[3] = $row['blockage_voiture'];  
        $entretien_data[4] = $row['commentaire'];  
        $entretien_data[5] = $row['prix_entretien'];  

    }
    echo json_encode($entretien_data);
}

function update_entretien_value()
{
    global $conn;
    global $msg_update_succés;
    global $msg_update_echec;

    $up_entretienid = $_POST["up_entretienid"];
    $up_DateDebutEntretien = $_POST["up_DateDebutEntretien"];
    $up_DateFinEntretien = $_POST["up_DateFinEntretien"];
    $up_prixentretien = $_POST["up_prixentretien"];
    $upblockageVoiture = $_POST["upblockageVoiture"];
    $up_commentaire = $_POST["up_commentaire"];
    $date = date('Y-m-d H:i:s');
    $update_query = "UPDATE entretien_voiture 
        SET 
            datedebut_entretien = '$up_DateDebutEntretien',
            datefin_entretien = '$up_DateFinEntretien',
            prix_entretien = '$up_prixentretien',
            blockage_voiture='$upblockageVoiture',
            commentaire=' $up_commentaire',
            date_updated_entretien = '$date'
        WHERE id_entretien = $up_entretienid";
    $update_result = mysqli_query($conn, $update_query);
    if (!$update_result) {
        echo "<div class='text-echec'>$msg_update_echec l'entretien !</div>";
        return;
    }
    echo "<div class='text-checked'>L'entretien $msg_update_succés</div>";
    return;
}

function delete_entretien_record()
{
    global $conn;
    global $msg_delete_succés;
    global $msg_delete_echec;
    $Del_ID = $_POST['id_entretien'];
    $date = date('Y-m-d H:i:s');
    $query = "UPDATE entretien_voiture 
                SET 
                    action_entretien = '0',
                    date_updated_entretien='$date' 
                WHERE id_entretien='$Del_ID'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<div class='text-checked'>L'entretien $msg_delete_succés</div>";
    } else {
        echo "<div class='text-echec'>$msg_delete_echec l'entretien !</div>";
    }
}

// Entretien Archive

function display_entretien_archive_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];
    
    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th class="border-top-0">#</th>
                <th class="border-top-0">Date Début</th>
                <th class="border-top-0">Date Fin</th>
                <th class="border-top-0">Prix Entretien</th>
                <th class="border-top-0">Pimm Voiture</th>
                <th class="border-top-0">Marque Voiture</th>
                <th class="border-top-0">Commentaire</th>';

                if($id_agence ==0){
                    $value .= '<th class="border-top-0">Agence</th>';
                }
            $value .= '</tr>
        </thead>
    <tbody>';

    if($id_role == 2){
        $query = "SELECT * 
        FROM entretien_voiture as E 
        LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        WHERE action_entretien = '1'
        AND datefin_entretien < DATE(NOW())
        AND V.id_agence = $id_agence
        ORDER BY id_entretien ASC";
    }else{
        $query = "SELECT * 
        FROM entretien_voiture as E 
        LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        LEFT JOIN agence AS A on V.id_agence = A.id_agence
        WHERE action_entretien = '1'
        AND datefin_entretien < DATE(NOW())
        ORDER BY id_entretien ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['datedebut_entretien'] . '</td>
            <td>' . $row['datefin_entretien'] . '</td>
            <td>' . $row['prix_entretien'] . '</td>
            <td>' . $row['pimm_voiture'] . '</td>
            <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
            <td>' . $row['commentaire'] . '</td>
            ';
            if($id_agence == 0){
                $value .= '<td>' . $row['nom_agence'] . '</td>';
            }
        $value .= '</tr>';
        $i += 1;  
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchEntretienArchive()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
        <thead>
            <tr>
                <th class="border-top-0">#</th>
                <th class="border-top-0">Date Début</th>
                <th class="border-top-0">Date Fin</th>
                <th class="border-top-0">Prix Entretien</th>
                <th class="border-top-0">Pimm Voiture</th>
                <th class="border-top-0">Marque Voiture</th>
                <th class="border-top-0">Commentaire</th>';

                if($id_agence ==0){
                    $value .= '<th class="border-top-0">Agence</th>';
                }
            $value .= '</tr>
        </thead>
    <tbody>';

    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = ("SELECT * 
            FROM entretien_voiture as E 
            LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            WHERE action_entretien = '1'
            AND datefin_entretien < DATE(NOW())
            AND V.id_agence = $id_agence
            AND (datedebut_entretien LIKE ('%" . $search . "%')
                    OR datefin_entretien LIKE ('%" . $search . "%') 
                    OR prix_entretien LIKE ('%" . $search . "%')       
                    OR pimm_voiture LIKE ('%" . $search . "%')
                    OR marque LIKE ('%" . $search . "%')
                    OR commentaire LIKE ('%" . $search . "%')
                    OR model LIKE ('%" . $search . "%'))
                    ORDER BY id_entretien ASC");
        }else{
            $query = ("SELECT * 
            FROM entretien_voiture as E 
            LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            LEFT JOIN agence AS A on V.id_agence = A.id_agence
            WHERE action_entretien = '1'
            AND datefin_entretien < DATE(NOW())
            AND (datedebut_entretien LIKE ('%" . $search . "%')
                    OR datefin_entretien LIKE ('%" . $search . "%') 
                    OR prix_entretien LIKE ('%" . $search . "%')       
                    OR pimm_voiture LIKE ('%" . $search . "%')
                    OR marque LIKE ('%" . $search . "%')
                    OR commentaire LIKE ('%" . $search . "%')
                    OR model LIKE ('%" . $search . "%')
                    OR nom_agence LIKE ('%" . $search . "%'))
                    ORDER BY id_entretien ASC");
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['datedebut_entretien'] . '</td>
                    <td>' . $row['datefin_entretien'] . '</td>
                    <td>' . $row['prix_entretien'] . '</td>
                    <td>' . $row['pimm_voiture'] . '</td>
                    <td>' . $row['marque'] . ' ' . $row['model'] . '</td>
                    <td>' . $row['commentaire'] . '</td>';
                    if($id_agence == 0){
                        $value .= '<td>' . $row['nom_agence'] . '</td>';
                    }
                $value .= '</tr>';
                $i += 1; 
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_entretien_archive_record();
    }
}

// Entretien Historique

function display_entretien_historique_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];

    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début Entretien</th>
            <th class="border-top-0">Date Fin Entretien</th>
            <th class="border-top-0">Pimm Voiture</th>';
            if($id_agence ==0){
                $value .= '<th class="border-top-0">Agence</th>';
            }
            $value .= '<th class="border-top-0">Action</th>
            <th class="border-top-0">Date Action</th>         
        </tr>
    </thead>
    <tbody>';
    if($id_role == 2){
        $query = "SELECT *
        FROM entretien_voiture as E 
        LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        WHERE V.id_agence = $id_agence
        ORDER BY id_entretien ASC";
    }else{
        $query = "SELECT * 
        FROM entretien_voiture as E 
        LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
        LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
        LEFT JOIN agence AS A on V.id_agence = A.id_agence
        ORDER BY id_entretien ASC";
    }
    $result = mysqli_query($conn, $query);
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        $class = "etat etatactif";
        $value .= '<tr>
            <td>' . $i . '</td>
            <td>' . $row['datedebut_entretien'] . '</td>
            <td>' . $row['datefin_entretien'] . '</td>
            <td>' . $row['pimm_voiture'] . '</td>';
            if($id_agence == 0){
                $value .= '<td>' . $row['nom_agence'] . '</td>';
            }
            $value .= '<td style="height: 70px;"><center><div class="'.$class.'">  AJOUT </div></center></td>
            <td>' . $row['date_created_entretien'] . '</td>
        </tr>';
        $i += 1;  
        if($row['action_entretien'] == 0){
            $class = "etat etatinactif"; 
            $value .= '<tr>
                <td>' . $i . '</td>
                <td>' . $row['datedebut_entretien'] . '</td>
                <td>' . $row['datefin_entretien'] . '</td>
                <td>' . $row['pimm_voiture'] . '</td>';
                if($id_agence == 0){
                    $value .= '<td>' . $row['nom_agence'] . '</td>';
                }
                $value .= '<td style="height: 70px;"><center><div class="'.$class.'">  SUPPRESSION </div></center></td>
                <td>' . $row['date_updated_entretien'] . '</td>
            </tr>';
            $i += 1;  
        }
    }
    $value .= '</table>';
    echo json_encode(['status' => 'success', 'html' => $value]);
}

function searchEntretienHistorique()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];
    $value = '<table class="table table-striped align-middle">
    <thead>
        <tr>
            <th class="border-top-0">#</th>
            <th class="border-top-0">Date Début Entretien</th>
            <th class="border-top-0">Date Fin Entretien</th>
            <th class="border-top-0">Pimm Voiture</th>';
            if($id_agence ==0){
                $value .= '<th class="border-top-0">Agence</th>';
            }
            $value .= '<th class="border-top-0">Action</th>
            <th class="border-top-0">Date Action</th>          
        </tr>
    </thead>
    <tbody>';

    if (isset($_POST['query'])) {
        $search = $_POST['query'];
        if($id_role == 2){
            $query = ("SELECT *
            FROM entretien_voiture as E 
            LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            WHERE V.id_agence = $id_agence
            AND (datedebut_entretien LIKE ('%" . $search . "%')
                OR datefin_entretien LIKE ('%" . $search . "%')
                OR pimm_voiture LIKE ('%" . $search . "%')
                OR date_created_entretien LIKE ('%" . $search . "%'))
                ORDER BY id_entretien ASC"); 
        }else{
            $query = ("SELECT * 
            FROM entretien_voiture as E 
            LEFT JOIN voiture AS V on E.id_voiture = V.id_voiture
            LEFT JOIN marque_voiture AS MM on V.id_marquemodel = MM.id_marquevoiture
            LEFT JOIN agence AS A on V.id_agence = A.id_agence
            WHERE (datedebut_entretien LIKE ('%" . $search . "%')
                OR datefin_entretien LIKE ('%" . $search . "%')
                OR pimm_voiture LIKE ('%" . $search . "%')
                OR date_created_entretien LIKE ('%" . $search . "%')
                OR nom_agence LIKE ('%" . $search . "%'))
                ORDER BY id_entretien ASC");
        }
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $class = "etat etatactif";
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['datedebut_entretien'] . '</td>
                    <td>' . $row['datefin_entretien'] . '</td>
                    <td>' . $row['pimm_voiture'] . '</td>';
                    if($id_agence == 0){
                        $value .= '<td>' . $row['nom_agence'] . '</td>';
                    }
                    $value .= '<td style="height: 70px;"><center><div class="'.$class.'">  AJOUT </div></center></td>
                    <td>' . $row['date_created_entretien'] . '</td>
                </tr>';
                $i += 1;  
                if($row['action_entretien'] == 0){
                    $class = "etat etatinactif"; 
                    $value .= '<tr>
                        <td>' . $i . '</td>
                        <td>' . $row['datedebut_entretien'] . '</td>
                        <td>' . $row['datefin_entretien'] . '</td>
                        <td>' . $row['pimm_voiture'] . '</td>';
                        if($id_agence == 0){
                            $value .= '<td>' . $row['nom_agence'] . '</td>';
                        }
                        $value .= '<td style="height: 70px;"><center><div class="'.$class.'">  SUPPRESSION </div></center></td>
                        <td>' . $row['date_updated_entretien'] . '</td>
                    </tr>';
                    $i += 1;  
                } 
            }
            $value .= '</tbody>';
        } else {
            $value = '<table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td>Aucune donnée correspond à votre recherche!</td>
            </tr>
            </tbody>';
        }
        echo $value;
    } else {
        display_entretien_historique_record();
    }
} 
// Planing Contrat

function display_planning_contrat_record()
{    
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];
    $data_planing = array();

    if($id_role == "2"){
        $query = "SELECT C.id_contrat,V.pimm_voiture AS title ,C.datedebut_contrat AS start ,DATE_ADD(C.datefin_contrat, INTERVAL 1 DAY) AS end 
        FROM contrat as C 
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        WHERE C.action_contrat='1'
        AND C.id_agence='$id_agence'";
    }else{
        $query = "SELECT C.id_contrat,V.pimm_voiture AS title ,C.datedebut_contrat AS start ,DATE_ADD(C.datefin_contrat, INTERVAL 1 DAY) AS end 
        FROM contrat as C 
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        WHERE C.action_contrat='1'";
    }
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)){
        $row['title'] = $row['id_contrat']. " - ". $row['title'];
        $data_planing[]=$row;
    }
    echo json_encode($data_planing);
}

function display_planning_liste_contrat_record()
{
    global $conn;
    $id_role = $_SESSION['Role'];
    $id_agence = $_SESSION['Agence'];
    $date= $_POST['date'];

    $value = '<ul style="margin-left: 18%; margin-top: 2%;" >';

    if($id_role == "2"){
        $query = "SELECT * 
        FROM contrat AS C
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        WHERE C.action_contrat='1'
        AND C.id_agence='$id_agence'
        AND  C.datedebut_contrat <='$date' 
        AND C.datefin_contrat >='$date' ";
    }else{
        $query = "SELECT * 
        FROM contrat AS C
        LEFT JOIN voiture AS V on V.id_voiture = C.id_voiture
        WHERE C.action_contrat='1'
        AND  C.datedebut_contrat <='$date' 
        AND C.datefin_contrat >='$date' ";
    }
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
            $value .="<li>CONTRAT DE LOCATION N°{$row['id_contrat']} - {$row['pimm_voiture']} </li>";
        }
    }else{
        $value="<div class=' alert alert-danger' role='alert' style='text-align:center;'>Aucun Contrat trouvé !</div>";
    }
    
    echo $value;
}
