<?php

    include ('bd.php');
    include ('ReqMerca.php');
    mysql_connect("localhost", "root", "35whuJau");
    mysql_select_db('mercato');
    

    $dirname = './img/logo/'; 
    $dir = opendir($dirname); 

    while($file = readdir($dir)) { 
    if($file != '.' && $file != '..' && !is_dir($dirname.$file)) 
    {   
        $trouve_moi = ".";
        // cherche la postion du '.'  
        $position = strpos($file, $trouve_moi);
        // enleve l'extention, tout ce qui se trouve apres le '.' 
        $image_sans_extension = substr($file, 0, $position);
        
        //echo '- <a href="'.$dirname.$file.'">'.$file.'</a>'.'<br /><br />';
        echo $dirname.$file;
        $req = "UPDATE club SET Miniature='".$dirname.$file."' WHERE nom LIKE '%".$image_sans_extension."%'";

        //$req = "SELECT logo FROM club WHERE nom LIKE '%".$image_sans_extension."%'";
        //$req = "Select logo from club";
        $req2 = mysql_query($req);
        
    } 
    } 

    closedir($dir); 

?>
