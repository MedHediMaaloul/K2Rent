<?php
if (isset($_GET['N'])){
  
  include("../Gestion_location/inc/connect_db.php");
  $id_contrat = $_GET['N'];
    
  $query = "SELECT id_contrat,id_client
    FROM contrat
    WHERE id_contrat = $id_contrat";
  $result = mysqli_query($conn, $query);
  
  if ($result) {
    while ($row = $result->fetch_assoc()) {
      $Contrat_number = $row['id_contrat'];
    }
  }

  require('fpdf.php');

  class PDF extends FPDF
  {
  protected $col = 0;
  protected $y0;
  function Header()
  {
      global $titre;
          $this->SetFont('Arial','B',12);
          $w = $this->GetStringWidth($titre)+20;
          $this->SetX((310-$w)/8);
          $this->Cell($w,35,utf8_decode($titre),0,1,'C',false);
          $this->Ln(-13);
          $this->y0 = $this->GetY(); 
  }
  function Footer()
  {
      // Pied de page
      $this->SetY(-15);
      $texte = "k2 Group SAS au capital de 146.000 euro - RC DIJON:4669B
      7 RUE JEAN BAPTISTE SAY 21800 CHEVIGNY ST SAUVEUR
      Siret: 88236307000013 TVA: FR59882363090 APE: 4669B";
      $this->SetFont('Arial','B',6);
      $this->MultiCell(0,3,utf8_decode($texte),0,'C');
      $this->SetFont('Arial','I',8);
      $this->SetTextColor(128);
      $this->Cell(0,0,"Page ".$this->PageNo(),0,0,'R');

  }
  function SetCol($col)
  {
      // Positionnement sur une colonne
      $this->col = $col;
      $x = 10 + $col * 50;
      $this->SetLeftMargin($x);
      $this->SetX($x);
  }
  function AcceptPageBreak()
  {
      // Méthode autorisant ou non le saut de page automatique
      if($this->col<3)
      {
          // Passage à la colonne suivante
          $this->SetCol($this->col+1);
          // Ordonnée en haut
          $this->SetY($this->y0);
          // On reste sur la page
          return false;
      }
      else
      {
          // Retour en première colonne
          $this->SetCol(0);
          // Saut de page
          return true;
      }
  }
  function printTableHeader($header,$w){
  	//Couleurs, épaisseur du trait et police grasse
  	$this->SetFillColor(255,255,255);
  	$this->SetTextColor(0);
  	$this->SetDrawColor(0,0,0);
  	$this->SetFont('Arial','B',9);
  	for($i=0;$i<count($header);$i++)
  		$this->Cell($w[$i],7,$header[$i],'LRT',0,'C');
  	$this->Ln();
  	//Restauration des couleurs et de la police pour les données du tableau
  	$this->SetFillColor(245,245,245);
  	$this->SetTextColor(0);
  	$this->SetFont('Arial');
  
  }
  function table($header,$w,$al,$datas){
  	//Impression de l'entête tableau
  	$this->SetLineWidth(.3);
  	$this->printTableHeader($header,$w);
  
  	$posStartX=$this->getX();	
  	$posBeforeX=$posStartX;
  
  	$posBeforeY=$this->getY();
  	$posAfterY=$posBeforeY;
  	$posStartY=$posBeforeY;
  
  	//On parcours le tableau des données
  	foreach($datas as $row){
  		$posBeforeX=$posStartX;
  		$posBeforeY=$posAfterY;
    
  		//On vérifie qu'il n'y a pas débordement de page.
  		$nb=0;
  		for($i=0;$i<count($header);$i++){
  			$nb=max($nb,$this->NbLines($w[$i],$row[$i]));
  		}
  		$h=6*$nb;
    
  		//Effectue un saut de page si il y a débordement
  		$resultat = $this->CheckPageBreak($h,$w,$header,$posStartX,$posStartY,$posAfterY);
  		if($resultat>0){
  			$posAfterY=$resultat;
  			$posBeforeY=$resultat;
  			$posStartY=$resultat;
  		}
    
  		//Impression de la ligne
  		for($i=0;$i<count($header);$i++){
  			$this->MultiCell($w[$i],6,strip_tags($row[$i]),'',$al[$i],false);
  			//On enregistre la plus grande hauteur de cellule
  			if($posAfterY<$this->getY()){
  				$posAfterY=$this->getY();
  			}
  			$posBeforeX+=$w[$i];
  			$this->setXY($posBeforeX,$posBeforeY);
  		}
  		//Tracé de la ligne du dessous
  		$this->Line($posStartX,$posAfterY,$posBeforeX,$posAfterY);
  		$this->setXY($posStartX,$posAfterY);
  	}
  
  	//Tracé des colonnes
  	$this->PrintCols($w,$posStartX,$posStartY,$posAfterY);
  }
  function PrintCols($w,$posStartX,$posStartY,$posAfterY){
  	$this->Line($posStartX,$posStartY,$posStartX,$posAfterY);
  	$colX=$posStartX;
  	//On trace la ligne pour chaque colonne
  	foreach($w as $row){
  		$colX+=$row;
  		$this->Line($colX,$posStartY,$colX,$posAfterY);
  	}
  }
  function CheckPageBreak($h,$w,$header,$posStartX,$posStartY,$posAfterY){
  	//Si la hauteur h provoque un débordement, saut de page manuel
  	if($this->GetY()+$h>$this->PageBreakTrigger){
  		//On imprime les colonnes de la page actuelle
  		$this->PrintCols($w,$posStartX,$posStartY,$posAfterY);
  		//On ajoute une page
  		$this->AddPage();
  		//On réimprime l'entête du tableau
  		$this->printTableHeader($header,$w);
  		//On renvoi la position courante sur la nouvelle page
  		return $this->GetY();
  	}
  	//On a pas effectué de saut on revoie 0
  	return 0;
  }
  function NbLines($w,$txt){
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
  }
  function CorpsChapitre($fichier)
  {
      // Lecture du fichier texte
      $txt = file_get_contents($fichier);
      $this->Image('k2rent.jpg',10,13,20,15);
      // Police
      $this->SetFont('Arial','',5);
      // Sortie du texte sur 6 cm de largeur
      $this->MultiCell(47,2.4,utf8_decode($txt));
      $this->Ln();
      // Mention
      $this->SetFont('Arial','B',6);
      $this->Cell(0,"2","Paraphe");
      // Retour en première colonne
      $this->SetCol(0);
  }

  function AjouterChapitre($num, $titre, $fichier)
  {
      // Ajout du chapitre
      $this->AddPage();
      // $this->TitreChapitre($num,$titre);
      $this->CorpsChapitre($fichier);
  }
  function VerifPage()
  {
    if( (($this->GetY())==0) | (($this->GetY())>=240) ) {
      $this->AddPage();
    }
  }
  }

  $pdf = new PDF('P','mm','A4');
  $pdf->AddPage();
  define('EURO',chr(128));
  $pdf->SetTitle(utf8_decode("ContratN°".$Contrat_number));
  $pdf->Image('k2rent.png',10,15,20,15);
  $pdf->SetFont('Arial','B',8);
  $pdf->Cell(50,4,utf8_decode('CONTRAT DE LOCATION N°').$Contrat_number,0,2,'',false);
  $pdf->Output('I',utf8_decode("ContratN°".$Contrat_number.".pdf"));

}else {
    echo "erreur";
}
?>