<?php

    //Para redireccionar la pagina
    function redireccionar($pagina){
        header('location: '.RUTA_URL.$pagina);
    }

    function tienePrivilegios($rol_usuario,$rolesPermitidos){
        // si $rolesPermitidos es vacio, se tendran privilegios
        if (empty($rolesPermitidos) || in_array($rol_usuario, $rolesPermitidos)) {
            return true;
        }
    }

    function limpiarCache() {
        
        /* Detele Cache Files Here */
        $dir = dirname(RUTA_APP).'/public/cache/'; /** define the directory **/

        /*** cycle through all files in the directory ***/
        // foreach (glob($dir."*") as $file) {
        foreach (glob($dir.'*.*') as $file){
            $filelastmodified = filemtime($file);
            if ((filemtime($file) < time() - 3600*24) || (time() - $filelastmodified) > 3600*24) { // 1 day
                unlink($file);
                }
        }

    }