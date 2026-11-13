<?php 

    $fichero = fopen("alumnos.txt", "a");
    if(!$fichero)  
        die('Error');
    fputs($fichero,"Hola, nueva línea con fputs()\n");
    fclose($fichero);

    copy("alumnos.txt","alumnos2.txt");
    copy("alumnos.txt","/var/www/html/ejerciciosHtml/ficheros/txtNuevo/pueba.txt");
?>