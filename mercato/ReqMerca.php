<?php

// Fonction qui permet d'ajouter un nouveau transfert dans la BDD.
function InsertTransfert($nom, $nationalite, $nouveau, $ancien, $montant, $date, $statut=1){
   $date = strtotime($date);
   $req = "Insert into transfert value ('', '$nom','$nationalite' , '$nouveau', '$ancien', $montant, $date, $statut)";
   mysql_query($req);
   return $req;
} 

// Fonction qui affiche la liste des clubs.
function AfficherClubListe(){
    $reqClub = "Select Id, Nom from club order by Nom";
    $reqClub2 = mysql_query($reqClub);
    while ($reqClub3 = mysql_fetch_assoc($reqClub2)){
        echo '<option value="'.$reqClub3['Id'].'">'.$reqClub3['Nom'].'</option>';
    }
    
}

// Fonction qui affiche la liste des pays.
function AfficherPaysListe(){
    $reqPays = "Select Pays from club group by Pays";
    $req2Pays = mysql_query($reqPays);
    while($req3Pays = mysql_fetch_array($req2Pays)){
        echo "<option name = '".$req3Pays['Pays']."' id= '".$req3Pays['Pays']."'>".$req3Pays['Pays']."</option>";
    }
}

// Fonction qui affiche un club a partir de son Id.
function AfficherClubAvecId($id){
    $reqClub2 = "Select Nom from club where Id=".$id;
    $req2Club2 = mysql_query($reqClub2);
    $req3Club2 = mysql_result($req2Club2, 0);
    return $req3Club2;

}

// Fonction qui affiche le logo du club a partir de son Id.
function AfficherLogoAvecId($id){
    $reqLogo = "Select Logo from club where Id=".$id;
    $req2Logo = mysql_query($reqLogo);
    $req3Logo = mysql_result($req2Logo, 0);
    return $req3Logo;

}

// Fonction qui affiche une miniature du logo du club a partir de son Id.
function AfficherMiniAvecId($id){
    $reqMini = "Select Miniature from club where Id=".$id;
    $req2Mini = mysql_query($reqMini);
    $req3Mini = mysql_result($req2Mini, 0);
    return $req3Mini;

}

// Fonction qui affiche la Lat d'un club a partir de son Id.
function AfficherLatAvecId($id){
    $reqLat = "Select Lat from club where Id=".$id;
    $req2Lat = mysql_query($reqLat);
    $req3Lat = mysql_result($req2Lat, 0);
    return $req3Lat;
}

// Fonction qui affiche la Lng d'un club a partir de son Id.
function AfficherLngAvecId($id){
    $reqLng = "Select Lng from club where Id=".$id;
    $req2Lng = mysql_query($reqLng);
    $req3Lng = mysql_result($req2Lng, 0);
    return $req3Lng;
}


// Fonction qui affiche la liste des transferts.
function AfficherTransfert(){
    $reqTrs = "Select Joueur, NouveauClub, AncienClub, Montant, Date, Nationalite from transfert ORDER BY Montant DESC";
    $req2Trs = mysql_query($reqTrs);
    while ($req3Trs = mysql_fetch_assoc($req2Trs)){
        $date = $req3Trs['Date'];
        // Si le montant est égal a 0, afficher 'pret'.
        if ($req3Trs['Montant'] == 0)
           $montant = 'Pret';
        else {
           $montant = number_format($req3Trs['Montant'], 0, ',', ',') ;
        }
        echo 'Nom: '.$req3Trs['Joueur'].' <IMG SRC='.$req3Trs['Nationalite'].' HEIGHT=15 WIDTH=25> <br> Nouveau Club: '. AfficherClubAvecId($req3Trs['NouveauClub']).' <IMG SRC='.AfficherMiniAvecId($req3Trs['NouveauClub']).' HEIGHT=20 WIDTH=20> <br>Ancien Club: '.  AfficherClubAvecId($req3Trs['AncienClub']).' <IMG SRC='.AfficherMiniAvecId($req3Trs['AncienClub']).' HEIGHT=20 WIDTH=20> <br>Montant: '.$montant.' <br>Date: '.date('d/m/Y', (int)$date).'<br><br><br>';
    }
    
}

// Fonction qui affiche les transferts d'un club a partir de son Id.
function AfficherTransfertAvecId($id){
    $reqTrs2 = "Select Joueur, NouveauClub, AncienClub, Montant, Date, Nationalite from transfert WHERE NouveauClub = '".$id."' OR AncienClub = '".$id."' ORDER BY Date DESC";
    $req2Trs2 = mysql_query($reqTrs2);
    while ($req3Trs2 = mysql_fetch_assoc($req2Trs2)){
        $date = $req3Trs2['Date'];
        // Si le montant est égal a 0, afficher 'pret'.
        if ($req3Trs2['Montant'] == 0)
           $montant = 'Pret';
        else {
           $montant = number_format($req3Trs2['Montant'], 0, ',', ',') ;
        }
        $trsId .= ' Nom: '.$req3Trs2['Joueur'].' <IMG SRC='.$req3Trs2['Nationalite'].' HEIGHT=15 WIDTH=25> <br><font color="green"> Nouveau Club: '. AfficherClubAvecId($req3Trs2['NouveauClub']).'</font> <IMG SRC='.AfficherLogoAvecId($req3Trs2['NouveauClub']).' HEIGHT=20 WIDTH=20> <br><font color="red">Ancien Club: '.  AfficherClubAvecId($req3Trs2['AncienClub']).' </font><IMG SRC='.AfficherLogoAvecId($req3Trs2['AncienClub']).' HEIGHT=20 WIDTH=20> <br>Montant: '.$montant.' <br>Date: '.date('d/m/Y', (int)$date).'<br><br><br>';
    }
    return $trsId;
}

// Affiche le nombre total de transferts.
function AfficherNbTrs(){
$reqNbTrs = "SELECT count(*) FROM transfert";
$reqNbTrs2 = mysql_query($reqNbTrs);
$reqNbTrs3 = mysql_result($reqNbTrs2, 0);
return $reqNbTrs3;
}


// Affiche le nombre de club actif sur le marché des transferts.
function AfficherNbCbActif(){
$reqNbClubActif = "SELECT DISTINCT NouveauClub FROM transfert UNION SELECT DISTINCT AncienClub FROM transfert";
$reqNbClubActif2 = mysql_query($reqNbClubActif);
return mysql_num_rows($reqNbClubActif2);
}

function AfficherMtTrs(){
$reqMtTrs = "SELECT SUM(Montant) FROM transfert";
$reqMtTrs2 = mysql_query($reqMtTrs);
$reqMtTrs3 = mysql_result($reqMtTrs2, 0);
return number_format($reqMtTrs3, 0, ',', ',');
}

// Affiche le top 3 des clubs ayant le plus dépensé.
function AfficherTop3Dps(){
$reqTopDps = "SELECT sum(Montant) AS Total, club.Nom FROM transfert INNER JOIN club ON transfert.NouveauClub = club.Id GROUP BY NouveauClub ORDER BY sum(Montant) DESC LIMIT 0,3";
$reqTopDps2 = mysql_query($reqTopDps);
echo "<p align ='left'><font size = 5 color='#0174DF'><br><B>TOP 3 DES CLUBS QUI ONT LE PLUS DEPENSÉS</B></font><br>";
while ($reqTopDps3 = mysql_fetch_assoc($reqTopDps2)){
    echo "<font size = 5>".$reqTopDps3['Nom']." = <font color='#9ACD32'><B>".number_format($reqTopDps3['Total'], 0, ',', ',')." €</font></B></font><br>";
}
echo '</p>';
}

// Affiche le top 4 des clubs ayant le plus acheter de joueurs.
function AfficherTop4NbRec(){
$reqTopNb = "SELECT COUNT(Joueur) AS NbJoueur , club.Nom FROM transfert INNER JOIN club ON transfert.NouveauClub = club.Id GROUP BY NouveauClub ORDER BY COUNT(Joueur) DESC LIMIT 0,4";
$reqTopNb2 = mysql_query($reqTopNb);
echo "<p align ='right'><font size = 5 color='#0174DF'><br><B>TOP 4 DES CLUBS QUI ONT LE PLUS RECRUTÉS</B></font><br>";
while ($reqTopNb3 = mysql_fetch_assoc($reqTopNb2)){
    echo "<font size = 5>".$reqTopNb3['Nom']." = <font color='#9ACD32'><B>".$reqTopNb3['NbJoueur']."</font></B></font><br>";
}
echo '</p>';
}

function AfficherTop4NbVen(){
$reqTopNb = "SELECT COUNT(Joueur) AS NbJoueur , club.Nom FROM transfert INNER JOIN club ON transfert.AncienClub = club.Id GROUP BY AncienClub ORDER BY COUNT(Joueur) DESC LIMIT 0,4";
$reqTopNb2 = mysql_query($reqTopNb);
echo "<p align ='left'><font size = 5 color='#0174DF'><br><B>TOP 4 DES CLUBS QUI ONT LE PLUS VENDUS</B></font><br>";
while ($reqTopNb3 = mysql_fetch_assoc($reqTopNb2)){
    echo "<font size = 5>".$reqTopNb3['Nom']." = <font color='#9ACD32'><B>".$reqTopNb3['NbJoueur']."</font></B></font><br>";
}
echo '</p>';
}

function AfficherNbTrsPays(){
$reqNbTrsPays = "SELECT COUNT(*) AS Total, club.Pays FROM transfert INNER JOIN club ON transfert.NouveauClub = club.Id GROUP BY club.Pays ORDER BY count(*) DESC LIMIT 0,10";
$reqNbTrsPays2 = mysql_query($reqNbTrsPays);
echo "<p align ='right'><font size = 5 color='#0174DF'><br><B>TOP 10 NOMBRE DE JOUEURS RECRUTÉS PAR PAYS</B></font><br>";
while ($reqNbTrsPays3 = mysql_fetch_assoc($reqNbTrsPays2)){
    echo "<font size = 5>".$reqNbTrsPays3['Pays']." = <font color='#9ACD32'><B>".$reqNbTrsPays3['Total']."</font></B></font><br>";
}
echo '</p>';
}

function AfficherTtMtPays(){
$reqMtTrsPays = "SELECT sum(Montant) AS Montant, club.Pays FROM transfert INNER JOIN club ON transfert.NouveauClub = club.Id GROUP BY club.Pays ORDER BY Montant DESC LIMIT 0,10";
$reqMtTrsPays2 = mysql_query($reqMtTrsPays);
echo "<p align ='left'><font size = 5 color='#0174DF'><br><B>TOP 10 MONTANT DÉPENSÉ PAR PAYS</B></font><br>";
while ($reqMtTrsPays3 = mysql_fetch_assoc($reqMtTrsPays2)){
    echo "<font size = 5>".$reqMtTrsPays3['Pays']." = <font color='#9ACD32'><B>".number_format($reqMtTrsPays3['Montant'], 0, ',', ',')." €</font></B></font><br>";
}
echo '</p>';
}

function AfficherTrsMtJour (){
$reqTrsMtJrs = "SELECT SUM(Montant) AS Total, Date FROM transfert GROUP BY Date ORDER BY  Total DESC LIMIT 0,3";
$reqTrsMtJrs2 = mysql_query($reqTrsMtJrs);
echo "<p align ='right'><font size = 5 color='#0174DF'><br><B>LES 3 JOURS LES PLUS FRUCTUEUX</B></font><br>";
while ($resTrsMtJrs3 = mysql_fetch_assoc($reqTrsMtJrs2)){
    $date = $resTrsMtJrs3['Date'];
    echo "<font size = 5>".date('d/m/Y', (int)$date)." = <font color='#9ACD32'><B>".number_format($resTrsMtJrs3['Total'], 0, ',', ',')." €</font></B></font><br>";
}
echo '</p>';
}
?>
