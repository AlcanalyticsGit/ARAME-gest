<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php // Declara vEmpresas en Javascript y lo puebla con $datos['usuarios]. Se ha movido a otro fichero porque Beautify lo rompía cada vez que se aplicaba en esta vista.
require_once RUTA_APP.'/vistas/empresas/script_inicio_empresas.php' ?>

<div class="container-fluid mb-4">

    <nav class="nav row mb-3 px-3">

        <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
        <div class="col-auto d-flex p-0">
            <a class="btn btn-outline-primary" href="<?php echo RUTA_URL?>/empresas/agregar/"><i
                    class="bi bi-building-fill-add me-2"></i>Nueva empresa</a>
        </div>
        <?php endif ?>

        <div class="col-auto d-flex ms-auto me-auto p-0">
            <div class="nav nav-pills d-flex">
                <button class="nav-link d-none" aria-current="page" id="filtroTodos"
                    onclick="cambiarFiltroEmpresas(0)">Todas</button>
                <button class="nav-link d-none" id="filtroAutonoma"
                    onclick="cambiarFiltroEmpresas(10)">Autónomas</button>
                <button class="nav-link d-none" id="filtroEmpresas"
                    onclick="cambiarFiltroEmpresas(11)">Empresas</button>
            </div>
        </div>

        <div class="col-auto d-flex p-0">

            <input class="form-control" type="search" id="barraBusqueda" placeholder="Buscar" aria-label="Search"
                onkeyup="filtrarEmpresas()">
            <!-- <button class="btn btn-outline-primary" type="submit">Buscar</button> -->

        </div>
    </nav>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="">NIF</th>
                    <th class="col-auto">Nombre</th>
                    <th class="col-1">Teléfono 1</th>
                    <th class="col-1">Teléfono 2</th>
                    <th class="col-1">Email</th>
                    <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
                    <th class=""></th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody id="tablaItems">
            </tbody>
        </table>
    </div>

    <div class="d-flex flex-column align-items-center">
        <div class="ms-auto">
            <a href="<?php echo RUTA_URL ?>/empresas/mostrarListadoEmpresas" class="nav-link color-principal">
                Descargar listado (.xlsx)<i class="bi bi-download ms-2"></i> </a>
        </div>

        <nav aria-label="Paginacion">
            <ul class="pagination py-3" id="ulPaginacion">
            </ul>
        </nav>
    </div>

</div>
<!-- <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10])):?>

<?php endif ?> -->

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>

<script>
    filtroActivo = 0;
    filtrarEmpresas();
</script>