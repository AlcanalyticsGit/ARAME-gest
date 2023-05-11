<script>
    <?php //PASAR LISTADO USUARIOS A ARRAY JS ?>
    vSocias = <?php echo json_encode($datos['socias']); ?>;
    let rutaURL = '<?php echo RUTA_URL ?>';
    let auth = <?php echo $datos['usuarioSesion']->rol ?>;
    let paginaActiva = <?php echo isset($_GET['pag']) ? $_GET['pag']-1 : '0' ?>;
</script>