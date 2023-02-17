<?php
session_start();
include('Gestion_location/inc/connect_db.php');

if ($_SESSION['Role'] == "2") {
    $id_agence = $_SESSION['Agence']; 
} else {
    $id_agence = $_POST['entretienagence'];
}

if ($id_agence != ""){

    $query = "SELECT * 
        FROM voiture 
        WHERE id_agence = '$id_agence' 
        AND action_voiture = '1'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
?>
        <label class="col-md-12 p-0"> Vehicule<span class="text-danger">*</span></label>
        <div class="col-md-12 border-bottom p-0">
            <select id="voiture_entretien" name="voiture_entretien" class="form-control p-0 border-0" required>
                <option value="" disabled selected>Vehicule</option>
                <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id_voiture'] . '">' . $row['pimm_voiture'] . '</option>';
                    }
                ?>
            </select>
        </div>
<?php
    }
}
?>

<script type="text/javascript">
    $(function() {
        $('#voiture_entretien').select2({
            width: '100%',
            dropdownParent: $('#voiture_entretien').parent()
        });
    })
</script>