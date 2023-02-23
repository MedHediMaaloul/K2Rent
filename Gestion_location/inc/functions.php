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
    
    if ($nb_res == 0) {
        return "Disponible";
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

// Notification 

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
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notifications.php">Voir Tous ('.$number_notif.' autres)</a></div>';
            }else{
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notifications.php">Voir Tous</a></div>';
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
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notifications.php">Voir Tous ('.$number_notif.' autres)</a></div>';
            }else{
                $output .= '<div style="margin-top:12px; position: absolute; right: 5%;"><a style="color: #1E90FF;" href="notifications.php">Voir Tous</a></div>';
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
                        <td><button type="button" title="Supprimer l\'horaire" class="btn btn-danger float-end" style="padding: 3px; font-size: 4px !important;" id="btn-delete-agence-heur" data-id4=' . $horaire['id_horaire'] . '>X</button></div></td> 
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
                            <td><button type="button" title="Supprimer l\'horaire" class="btn btn-danger float-end" style="padding: 3px; font-size: 4px !important;" id="btn-delete-agence-heur" data-id4=' . $horaire['id_horaire'] . '>X</button></div></td> 
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
            <th class="border-top-0">Assurance</th>
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
            <td><a href="uploadfile/voiture/assurance/' . $row["assurance_voiture"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/assurance/' . $row["assurance_voiture"] . '"></a></td>
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
            <th class="border-top-0">Assurance</th>
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
                    <td><a href="uploadfile/voiture/assurance/' . $row["assurance_voiture"] . '" target="_blank"><img width="40px"height="40px" src="uploadfile/voiture/assurance/' . $row["assurance_voiture"] . '"></a></td>
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
    if ($id_agence != "0") {
        $voitureagence = $id_agence;
    } else {
        $voitureagence = $_POST['voitureagence'];
    }
    
    if($voitureagence == 0){
        echo '<div class="text-echec">Veuillez choisir l\'agence!</div>';
    }else{
        // upload file cartegrise
        $emplacement_cartegrise = "uploadfile/voiture/cartegrise/";
        $file_cartegrise = $emplacement_cartegrise . basename($_FILES["voiturecartegrise"]["name"]);
        $uploadOk_cartegrise = 1;
        $type_cartegrise = strtolower(pathinfo($file_cartegrise,PATHINFO_EXTENSION));

        if($type_cartegrise != "jpg" && $type_cartegrise != "png" && $type_cartegrise != "jpeg" && $type_cartegrise != "gif" ) {
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_cartegrise = 0;
        }  
        if ($uploadOk_cartegrise != 0) {
          if (move_uploaded_file($_FILES["voiturecartegrise"]["tmp_name"], $emplacement_cartegrise .$Namefile.".".$type_cartegrise)) {
            $voiturecartegrise = $Namefile.".".$type_cartegrise;
          } else {
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
          }
        }
        // upload file assurance
        $emplacement_assurance = "uploadfile/voiture/assurance/";
        $file_assurance = $emplacement_assurance . basename($_FILES["voitureassurance"]["name"]);
        $uploadOk_assurance = 1;
        $type_assurance = strtolower(pathinfo($file_assurance,PATHINFO_EXTENSION));

        if($type_assurance != "jpg" && $type_assurance != "png" && $type_assurance != "jpeg" && $type_assurance != "gif" ) {
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_assurance = 0;
        }  
        if ($uploadOk_assurance != 0) {
          if (move_uploaded_file($_FILES["voitureassurance"]["tmp_name"], $emplacement_assurance .$Namefile.".".$type_assurance)) {
            $voitureassurance = $Namefile.".".$type_assurance;
          } else {
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
          }
        }
        // vérification si le pimm existe
        $sql_e = "SELECT * FROM voiture WHERE pimm_voiture = '$voiturepimm' AND action_voiture = '1'";
        $res_e = mysqli_query($conn, $sql_e);
        if (mysqli_num_rows($res_e) > 0) {
            echo '<div class="text-echec">Désolé ... Immatriculation est déjà pris!</div>';
        } else {
            $date = date('Y-m-d H:i:s');
            $query = "INSERT INTO voiture(pimm_voiture,id_marquemodel,id_agence,id_typecarburant,boitevitesse_voiture,nbreplace_voiture,valise_voiture,puissance_voiture,climatisation_voiture,
                        cartegrise_voiture,assurance_voiture,date_created_voiture,date_updated_voiture) 
                    VALUES ('$voiturepimm','$voitureMarqueModel','$voitureagence','$voituretypecarburant','$voitureboitevitesse','$voiturenbreplace','$voiturenbrevalise','$voiturepuissance','$voitureclimatisation',
                        '$voiturecartegrise','$voitureassurance','$date','$date') ";

            $result = mysqli_query($conn, $query);
            if ($result) {
                echo "<div class='text-checked'>La voiture $msg_insert_succés</div>";
            } else {
                echo "<div class='text-echec'>Vous avez rencontré un problème lors de l'ajout du voiture</div>";
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
    $up_voitureassurance = isset($_FILES['up_voitureassurance']) ? $_FILES['up_voitureassurance'] : "";

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
          if (move_uploaded_file($_FILES["up_voiturecartegrise"]["tmp_name"], $emplacement_cartegrise .$Namefile.".".$type_cartegrise)) {
            $up_voiturecartegrise = $Namefile.".".$type_cartegrise;
          } else {
            $up_voiturecartegrise = $voiture["cartegrise_voiture"];
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
          }
        }
    }else{
        $up_voiturecartegrise = $voiture["cartegrise_voiture"];
    }
    
    if($up_voitureassurance != ""){
        $emplacement_assurance = "uploadfile/voiture/assurance/";
        $file_assurance = $emplacement_assurance . basename($_FILES["up_voitureassurance"]["name"]);
        $uploadOk_assurance = 1;
        $type_assurance = strtolower(pathinfo($file_assurance,PATHINFO_EXTENSION));

        if($type_assurance != "jpg" && $type_assurance != "png" && $type_assurance != "jpeg" && $type_assurance != "gif" ) {
            $up_voitureassurance = $voiture["assurance_voiture"];
            echo "<div class='text-echec'>Désolé ... seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés</div>"; 
            $uploadOk_assurance = 0;
        }  
        if ($uploadOk_assurance != 0) {
          if (move_uploaded_file($_FILES["up_voitureassurance"]["tmp_name"], $emplacement_assurance .$Namefile.".".$type_assurance)) {
            $up_voitureassurance = $Namefile.".".$type_assurance;
          } else {
            $up_voitureassurance = $voiture["assurance_voiture"];
            echo "<div class='text-echec'>Désolé ... une erreur s'est produite lors du téléchargement de votre fichier</div>"; 
          }
        }
    }else{
        $up_voitureassurance = $voiture["assurance_voiture"];
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
                            assurance_voiture = '$up_voitureassurance',
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
            <td>' . $row['prix_marquevoiture'] . '</td>
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
        AND (marque LIKE ('%" . $search . "%') OR model LIKE ('%" . $search . "%') OR prix_marquevoiture LIKE ('%" . $search . "%'))
            ORDER BY id_marquevoiture ASC");
        $result = mysqli_query($conn, $query);
        $i = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $value .= '<tr>
                    <td>' . $i . '</td>
                    <td>' . $row['marque'] . '</td>
                    <td>' . $row['model'] . '</td>
                    <td>' . $row['prix_marquevoiture'] . '</td>
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
    $voitureprix = $_POST['voitureprix'];
    
    $sql_e = "SELECT * FROM marque_voiture WHERE marque = '$voituremarque' AND model = '$voituremodel' AND action_marquevoiture = '1'";
    $res_e = mysqli_query($conn, $sql_e);
    if (mysqli_num_rows($res_e) > 0) {
        echo '<div class="text-echec">Désolé ... la marque est déjà pris!</div>';
    } else {
        $date = date('Y-m-d H:i:s');
        $query = "INSERT INTO marque_voiture(marque,model,prix_marquevoiture,date_created_marque,date_updated_marque) 
            VALUES ('$voituremarque','$voituremodel','$voitureprix','$date','$date') ";

        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<div class='text-checked'>La marque voiture $msg_insert_succés</div>";
        } else {
            echo "<div class='text-echec'>Vous avez rencontré un problème lors de l'ajout du marque voiture</div>";
        }
    }
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
        $marquevoiture_data[3] = $row['prix_marquevoiture'];
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
    $up_voitureprix = $_POST["up_voitureprix"];

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
                                prix_marquevoiture = '$up_voitureprix',
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
            <th class="border-top-0">Assurance</th>
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

function InsertContrat()
{
    global $conn;
    global $msg_insert_succés;

    $DateDebutContrat = $_POST['DateDebutContrat'];
    $DateFinContrat = $_POST['DateFinContrat'];
    $ClientContrat = $_POST['ClientContrat'];
    $AgenceContrat = $_POST['AgenceContrat'];
    if($_SESSION['Role'] == 2){
        $AgenceContrat = $_SESSION['Agence'];
    }
    $VoitureContrat = $_POST['VoitureContrat'];
    //Nombre de jours
    $nbJoursTimestamp = strtotime($DateFinContrat) - strtotime($DateDebutContrat);
    $nbJours = $nbJoursTimestamp/86400;
    $nbJours = number_format($nbJours, 0, '.', '');

    if ($AgenceContrat != "0") {
        $query_getprice = "SELECT prix_marquevoiture 
                            FROM marque_voiture AS M
                            LEFT JOIN voiture AS V ON M.id_marquevoiture = V.id_marquemodel
                            WHERE id_voiture = $VoitureContrat";
        $result_price = mysqli_query($conn, $query_getprice);
        $row_price = mysqli_fetch_assoc($result_price);
        $PrixContrat = $row_price['prix_marquevoiture'] * $nbJours;
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
            $class = "etatL etatinactif"; 
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
                    $class = "etatL etatinactif"; 
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
                <th class="border-top-0">Marque Voiture</th>';
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
            <td>' . $row['marque'] . ' ' . $row['model'] . '</td>';
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
                <th class="border-top-0">Marque Voiture</th>';
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
                <td>' . $row['marque'] . ' ' . $row['model'] . '</td>';
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
   
    $date = date('Y-m-d H:i:s');
    $query = "INSERT INTO entretien_voiture(id_voiture,datedebut_entretien,datefin_entretien,prix_entretien,date_created_entretien,date_updated_entretien) 
            VALUES ('$voiture_entretien','$DateDebutEntretien','$DateFinEntretien','$prixentretien','$date','$date') ";
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
        $entretien_data[3] = $row['prix_entretien'];  
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
    
    $date = date('Y-m-d H:i:s');
    $update_query = "UPDATE entretien_voiture 
        SET 
            datedebut_entretien = '$up_DateDebutEntretien',
            datefin_entretien = '$up_DateFinEntretien',
            prix_entretien = '$up_prixentretien',
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
                <th class="border-top-0">Marque Voiture</th>';
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
            <td>' . $row['marque'] . ' ' . $row['model'] . '</td>';
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
                <th class="border-top-0">Marque Voiture</th>';
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
                    <td>' . $row['marque'] . ' ' . $row['model'] . '</td>';
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
            $class = "etatL etatinactif"; 
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
                    $class = "etatL etatinactif"; 
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
					FROM user AS U,role_user AS R,agence AS A
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