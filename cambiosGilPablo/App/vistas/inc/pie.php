<!-- FIN CONTENIDO -->

        <!-- INICIO FOOTER -->
        </main>

    <?php if (!isset($datos['login'])) {
        require_once RUTA_APP.'/vistas/inc/footer.php';
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>


<?php if(isset($_SESSION['modo_depuracion']) && $_SESSION['modo_depuracion'] && $datos['usuarioSesion']->rol == 10) : ?>
    <!-- SOLO PARA DEPURACIÓN, ¡¡¡ELIMINAR DE LA VERSIÓN DE PRODUCCIÓN!!! -->
    <button class="btn btn-info w-auto" id="btn-depuracion" onclick="debug_code()"><i class="bi bi-bug"></i></button>
    <div id="depuracion" style="display:none;">
    <?php
    echo "<pre>";
    print_r($this);
    var_dump($this);
    print_r($_SERVER);                
    echo "</pre>";
    ?>
    <!-- FINAL BOTÓN DEPURACIÓN -->
<?php endif; ?>

</div>

</body>

</html>