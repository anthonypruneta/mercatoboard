<!DOCTYPE html>
<?php
    include ('bd.php');
    include ('ReqMerca.php');
?>

<html>
    <head>
        <title>Mercato Board</title>
        <meta charset="utf-8">        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script src="gmaps.js"></script>
        <script type="text/javascript" src="jquery.tipsy.js"></script>
        <link href='css.css' rel='stylesheet' type='text/css' />
        <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>       
    </head>
    <body>
        <div id="wrapformmap">
        <div class="wrapper">
        <form class="formMap" method="POST" action="index.php">
            <select name="pays" id="pays">
                <?php
                // Afficher la liste des pays disponibles dans la BDD.
                AfficherPaysListe();
                ?>
            </select>
            <input type="submit" value="Envoyer">
        </form>
        <form class="formMap" method="POST" action="index.php">
              <select name="club" id="club">
                <?php
                // Afficher la liste des club disponible dans la BDD.
                AfficherClubListe();
                ?>
            </select>
            <input type="submit" value="Envoyer">
        </form>
        <form class="formMap" method="POST" action="index.php">
        <input type="submit" name="btnRefresh" value="Tous les pays/clubs">
        </form>
        </div>
        </div>
    </body>
</html>

<?php
    // Test sur le $_POST, s'il est vide pas de tri, si il est plein on tri.    
    if(!empty($_POST))
            {            
                // Soit le tri est sur le pays, soit sur le club.
                
                if (isset($_POST['pays'])){
                    $triPays = $_POST['pays'];
                $req = "Select NouveauClub, AncienClub, Montant, club.Id, club.Pays from transfert inner join club ON club.Id = NouveauClub OR club.Id = AncienClub WHERE club.Pays = '".$triPays."'";
                }
                elseif (isset($_POST['club'])){
                
                $triClub = $_POST['club'];
                $req = "Select * from transfert where NouveauClub = '".$triClub."' OR AncienClub = '".$triClub."'";
                }
                else{
                    $req = "Select NouveauClub, AncienClub, Montant, club.Id from transfert inner join club where club.Id = NouveauClub";
                }
            }
    else{
        $req = "Select NouveauClub, AncienClub, Montant, club.Id from transfert inner join club where club.Id = NouveauClub";
    }
    
    // On séléctionne dans la BDD les nouveaux et anciens club, et on rajoute la variable de pays si elle existe.
    $req2 = mysql_query($req);
    while ($req3 = mysql_fetch_assoc($req2)){
        // On récupère la lng et lat des nouveaux et anciens clubs, ligne par ligne.
        $i = i + 1;
        $latA = AfficherLatAvecId($req3['NouveauClub']);
        $lngA = AfficherLngAvecId($req3['NouveauClub']);
        $latD = AfficherLatAvecId($req3['AncienClub']);
        $lngD = AfficherLngAvecId($req3['AncienClub']);
        // On chance la couleur si c'est un prêt (montant == 0) ou pas.
        if ($req3['Montant'] == 0){
           $color = "strokeColor: '#000000'";
           $taille = "strokeWeight: 1";
        }
        else{
           $color = "strokeColor: '#9ACD32'";
           if ($req3['Montant'] < 1500000){
               $taille = "strokeWeight: 2";
           }
           elseif ($req3['Montant'] < 30000000){
               $taille = "strokeWeight: 4";
           }
           elseif ($req3['Montant'] > 30000000){
               $taille = "strokeWeight: 20";
           }
        }
        $fontsizeib = '</font><font style="font-size:10;">';
        $fontsizetitle = '<center><font style="font-size:15;">';
        $logoA = '<center><IMG SRC="'.AfficherLogoAvecId($req3["NouveauClub"]).'" HEIGHT=80 WIDTH=60></center>';
        $logoD = '<center><IMG SRC="'.AfficherLogoAvecId($req3["AncienClub"]).'" HEIGHT=80 WIDTH=60></center>';
        // On concatène a chaque fois le résultat, ce qui permettra de l"ajouter dans la map.
        $resultat .=
                "path" . $i . " = [[" . $latD . ", " . $lngD . "], [" . $latA . ", " . $lngA . "]]
                        map.drawPolyline({
                        path: path" . $i . ",
                        ".$color.",
                        strokeOpacity: 0.8,
                        ".$taille."                       
                });
                var image = new google.maps.MarkerImage('".AfficherMiniAvecId($req3['AncienClub'])."');
                var test = map.addMarker({
                  lat: ".$latD.",
                  lng: ".$lngD.",
                  title: '".AfficherClubAvecId($req3['AncienClub'])."</font></center>',
                  icon: image,
                  infoWindow: {
                    maxWidth: 200,
                    maxHeight: 200,
                    content: '".$fontsizetitle." ".AfficherClubAvecId($req3['AncienClub'])." <br> ".$fontsizeib.$logoD." <br> ".AfficherTransfertAvecId($req3['AncienClub'])."'
                  }
                });
                var image2 = new google.maps.MarkerImage('".AfficherMiniAvecId($req3['NouveauClub'])."');
                var test2 = map.addMarker({
                  lat: ".$latA.",
                  lng: ".$lngA.",
                  title: '$fontsizetitle".AfficherClubAvecId($req3['NouveauClub'])."</font></center>',
                  icon: image2,
                  infoWindow: {
                    maxWidth: 200,
                    maxHeight: 200,
                    content: '".$fontsizeib." ".AfficherClubAvecId($req3['NouveauClub'])." <br> ".$logoA." <br> ".AfficherTransfertAvecId($req3['NouveauClub'])." </font>'
                  }
                });
                ";
        
    }
    
// On execute le script avec le résultat obtenue précédement et on affiche donc la carte.
echo
"<script type='text/javascript'>
    
	var map;
    $(document).ready(function(){
      map = new GMaps({
		zoom: 3,
                minZoom:3,
                scrollwheel:false,
        div: '#map',
        lat: 48.7279,
        lng: 2.36455,
		height: '600px'
		});
		" . $resultat . "
		});
                
                
	
</script>

</head>
<body>
  <div class='row'>
    <div class='span11'>
      <div id='map'></div>
    </div>
    </div>
</body>
</html>";
?>
