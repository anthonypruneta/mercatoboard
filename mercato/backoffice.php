        <?php
            include ('bd.php');
            include ('ReqMerca.php');
           
            if(!empty($_POST))
            {                
                extract($_POST);
                InsertTransfert($nom, $nationalite, $nouveau, $ancien, $montant, $date);
            }
          
       ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>

       <form method="POST" action="index.php">
            Nom : <input type="text" name="nom" id ="nom"><br>
            Nationalité (lien drapeau wikipedia) : <input type="text" name="nationalite" id="nationalite"><br>
            Nouveau club : <select name="nouveau" id="nouveau"><?php AfficherClubListe();?></select><br>
            Ancien club : <select name="ancien" id="ancien"><?php AfficherClubListe();?></select><br>
            Montant : <input type="text" name="montant" id="montant"><br>
            Date : <input type="text" name="date" id="date"><br>
            <input type="submit" value="Envoyer">
        </form>
                
    </body>

</html>


