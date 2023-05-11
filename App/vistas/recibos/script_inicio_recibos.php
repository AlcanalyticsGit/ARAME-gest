<script>
    <?php //PASAR LISTADO RECIBOS A ARRAY JS ?>
    vRecibosEmitidos = <?php echo json_encode($datos['recibosEmitidos']); ?>;
    let auth = <?php echo $datos['usuarioSesion']->rol; ?>;
    let rutaURL = '<?php echo RUTA_URL ?>';
    let paginaActiva = <?php echo isset($_GET['pag']) ? $_GET['pag']-1 : '0' ?>;


</script>