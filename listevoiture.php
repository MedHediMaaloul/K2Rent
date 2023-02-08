<?php
session_start();
include('Gestion_location/inc/connect_db.php');
$id_agence = $_SESSION['Agence'];

if ($_POST['DateDebutContrat'] && $_POST['DateFinContrat']) {
    $debut = $_POST['DateDebutContrat'];
    $fin = $_POST['DateFinContrat'];
}
if ($id_agence != "0") {
    $query = "SELECT * FROM voiture WHERE
    id_agence = '$id_agence' AND action_voiture = '1'";
} else {
    $agencecontratvoiture = $_POST['AgenceContrat'];
    $query = "SELECT * FROM voiture WHERE
    id_agence = '$agencecontratvoiture' AND action_voiture = '1'";
}


$result = mysqli_query($conn, $query);

?>
<label class="col-md-12 p-0"> Vehicule<span class="text-danger">*</span></label>
<div class="col-md-12 border-bottom p-0">
    <select id="list_voiture" name="list_voiture" placeholder="Nom " class="form-control p-0 border-0" required>
        <option value="" disabled selected> Vehicule
        </option>
        <?php
        if ($result->num_rows > 0) {

            while ($row = mysqli_fetch_assoc($result)) {
                $disponibilte = disponibilite_Vehicule($row['id_voiture'], $debut, $fin);
                if ($disponibilte == 'disponibile') {
                    echo '<option value="' . $row['id_voiture'] . '">' . $row['pimm_voiture'] . '</option>';
                } else {
                    echo '<option disabled value="' . $row['id_voiture'] . '">' . $row['pimm_voiture'] . ' Non Disponible</option>';
                }
            }
        }
        ?>
    </select>
</div>

<script type="text/javascript">
    $(function() {
        $('#list_voiture').select2({
            dropdownParent: $('#list_voiture').parent()
        });
    })
</script>

<?php



function disponibilite_Vehicule($id_voiture, $debut, $fin)
{
    global $conn;

    $query = "SELECT * FROM contrat
    where  
    id_voiture ='$id_voiture' and 
    ((datedebut_contrat <='$debut' and datefin_contrat >='$debut' )
     or (datedebut_contrat <='$fin' and datefin_contrat >='$fin' ) 
     or (datedebut_contrat >='$debut' and datefin_contrat <='$fin' ))
     AND action_contrat != '0'
    ";
    $result = mysqli_query($conn, $query);
    $nb_res = mysqli_num_rows($result);

    if ($nb_res == 0) {
        return "disponibile";
    } else {
        return " non disponibile";
    }
}
?>