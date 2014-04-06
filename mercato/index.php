<html>
    <head>
        <title>Mercato Board</title>
        <meta charset="utf-8">        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
        <script type="text/javascript" src="gmaps.js"></script>
        <script type="text/javascript" src="jquery.tipsy.js"></script>
        <link href='css.css' rel='stylesheet' type='text/css' />
        <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>       
    </head>
    <body>
        <div id="text" style="position:relative;">
            <h1 id="title"><font color='#9ACD32'>Mercato</font>Board.com <div id="social"><?php include ('social.php');?></div> </h1>
        </div>
        <div class="wrapper">
        <?php
        include ('bd.php');
        include ('ReqMerca.php');
        ?>
            <div id="msgwlc">Bienvenue sur la page récapitulative du mercato d'hiver de 2013.</div>
            <div id="numbers">
                <div class="number"><?php echo "<font color='#9ACD32'>".AfficherNbCbActif()."</font>" ?><br><font style="font-size:20;"> clubs</font></div>
                <div class="number"><?php echo "<font color='#9ACD32'>".AfficherNbTrs()."</font>" ?><br><font style="font-size:20;"> transferts</font></div>
                <div class="number"><?php echo "<font color='#9ACD32'>".AfficherMtTrs()."€</font>" ?><br><font style="font-size:20;"> dépensés</font></div>
            </div>
            <a href="http://anthonypruneta.fr/mercato/mercato" class="button" style="margin-bottom: 5%; margin-top: -10%;">Voir la carte</a>
        </div>

<!--        <div id="wrapmap">-->
        <?php
//        echo '<div id="gmaps" style="position:relative;">';
//        include ('mercato.php');
//        echo "</div>";
//        echo '</div>';
        
        echo '<div class="wrapper">';
        echo '<div id="bubbletitle">Activité sur le marché des transferts</div>';
        echo '<div id="bubblechart" style="position:relative;">'; 
        include ('Graph/GraphAchVen.php');
        echo "</div>";

        echo '<div id="circletitle">Top des dépenses des clubs</div>';
        echo '<div id="circlechart" style="position:relative;">';
        include ('Graph/GraphMtClub.php');
        echo "</div>";

        echo "<div id='stacktitle'>Nombres d'achats et de prêts du mois de Janvier</div>";
        echo '<div id="stackgraph" style="position:relative;">'; 
        include ('Graph/GraphTsPt.php');
        echo "</div>";

        echo '<div id="bartitle">Montant et nombres de ventes des pays</div>';
        echo '<div id="bargraph" style="position:relative;">';
        include ('Graph/GraphMtNbPays.php');
        echo "</div>";

        ?>
<!--        </div>-->
        
         
    </body>
    <div id="trais"></div>
    <div id="sign"><p>Created by <a href = "https://twitter.com/Anth00P" target="_blank">Antho Pruneta</a>, with the help of Mapize.</p><div style="margin-top:-7px"> <?php include ('social.php');?></div></div>
    </html>