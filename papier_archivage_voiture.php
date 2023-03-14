<?php
session_start();
if (!isset($_SESSION['Login'])) {
    header("Location:login.php");
} else {
    include('Gestion_location/inc/header_sidebar.php');
}
?>
<style>
    #paper_years{
    display: grid;
    grid-template-columns: repeat(5,auto); 
    column-gap: 2%;
    row-gap: 5%;
    }
     .years{
    padding: 1%;
    background: #F1F1F1;
    border-radius: 3px;
    font-weight: 600;
    font-size: 20px;
    line-height: 18px;
    color: #6C6C6C;
    border-color: red;
}
.years:hover{
    background: #BF1616; 
}

.years:focus{
    border-radius: 4%;
    background: #BF1616;
    color:white;
}

</style>
<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <div class="breadcrumb-item" style="font-size: 19px; font-weight: bold;">Contrat</div>
                    <div class="breadcrumb-item active" style="font-size: 19px; color:#D71218; font-weight: bold;"
                        aria-current="page">Liste archivage des Contrats</div>
                </ol>
            </nav>
            <div class="ms-auto">
                <div id="div1">
                    <div class="input-group">
                        <input type="input" class="form-control search-control" placeholder="Que recherchez-vous?"
                            id="searchContratArchive">
                        <span class="position-absolute top-50 search-show translate-middle-y"><i
                                class='bx bx-search'></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="contain">
            <!-- Liste des PIMMs -->

            <?php
                                include_once('Gestion_location/inc/connect_db.php');
                                $query = "SELECT pimm_voiture,id_voiture  FROM voiture WHERE action_voiture = '1' ORDER BY id_voiture ASC";
                                $result = mysqli_query($conn, $query);
                            ?>
            <div class="form-group border-bottom mb-4">
                <label class="col-md-12 p-0">Marque<span class="text-danger">*</span></label>
                <div class="col-md-12">
                    <select name="type" id="PimmPapier" class="form-select-simple" required>
                        <option value="0" disabled selected>Selectionner la PIMM</option>
                        <?php
                        
                                if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id_voiture'] . '">' . $row['pimm_voiture'] .  ' </option>';
                                }
                                }
                            ?>
                    </select>
                </div>
            </div>
            <div id="paper_years">
                <?php 
            for ($i = date('Y'); $i >2022; $i--) {
                ?>
                <button class="years" id="<?php echo abs($i) ?>"> <?php echo $i;?> </button>
                <?php
                }
                ?>
            </div>


        </div>
    </div>
</div>

<?php
include('Gestion_location/inc/footer.php')
?>

</body>

</html>