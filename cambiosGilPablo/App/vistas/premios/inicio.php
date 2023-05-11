<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php // Declara vSocias en Javascript y lo puebla con $datos['usuarios]. Se ha movido a otro fichero porque Beautify lo rompía cada vez que se aplicaba en esta vista.
require_once RUTA_APP.'/vistas/premios/script_inicio_premios.php'; ?>

<div class="container-fluid mb-4">

    <nav class="nav row mb-3 px-3 justify-content-between">

        <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
        <div class="col-auto d-flex p-0">
            <a class="btn btn-outline-primary" href="<?php echo RUTA_URL?>/premios/agregar/"><i
                    class="bi bi-trophy-fill me-2"></i>Nuevo premio</a>
        </div>
        <?php endif ?>
        
        <div class="col-12 col-md-6 col-lg-5 col-xl-4 d-flex p-0">
        
            <input class="form-control" type="search" id="barraBusqueda" placeholder="Buscar" aria-label="Search"
                onkeyup="filtrarPremios()">
        
        </div>
    </nav>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-1">Año</th>
                    <th class="">Socia</th>
                    <th class="col-auto">Descripción</th>
                    <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10, 20])):?>
                    <th class="col-1 text-end">Acciones</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody id="tablaItems">
            </tbody>
        </table>
    </div>
    
    <div class="d-flex flex-column align-items-center">
        <div class="ms-auto">
        <a href="<?php echo RUTA_URL ?>/premios/listado_premios" class="nav-link color-principal">
        Descargar listado completo (.xlsx)<i class="bi bi-download ms-2"></i>   </a>
    </div>

        <nav aria-label="Paginacion">
            <ul class="pagination py-3" id="ulPaginacion">

            </ul>
        </nav>

    </div>

    


</div>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>

<script>
    filtroActivo = 0;
    filtrarPremios();
</script>