<script>
    <?php //PASAR LISTADO USUARIOS A ARRAY JS ?>
    vUsuarios = <?php echo json_encode($datos['usuarios']); ?>;
    let rutaURL = '<?php echo RUTA_URL ?>';
    const usuarioSesionUsername = '<?php echo $datos['usuarioSesion']->username ?>';
    let auth = <?php echo $datos['usuarioSesion']->rol ?>;
    let paginaActiva = <?php echo isset($_GET['pag']) ? $_GET['pag']-1 : '0' ?>;

</script>