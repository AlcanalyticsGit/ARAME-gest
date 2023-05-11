<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php // Declara vUsuarios en Javascript y lo puebla con $datos['usuarios]. Se ha movido a otro fichero porque Beautify lo rompía cada vez que se aplicaba en esta vista.
require_once RUTA_APP.'/vistas/usuarios/script_inicio_usuarios.php'; ?>

<div class="container-fluid w-auto mb-4">

    <div class="col-auto">
        <nav class="nav row mb-3 px-3 d-flex justify-between">

            <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
            <div class="col-auto ps-0">
                <a class="btn btn-outline-primary" href="<?php echo RUTA_URL?>/usuarios/agregar/"><i
                        class="bi bi-person-plus-fill pe-2"></i>Nuevo usuario</a>
            </div>
            <?php endif ?>

            <div class="col-auto d-flex">
                <div class="nav nav-pills d-flex">
                    <button class="nav-link d-none" aria-current="page" id="filtroTodos"
                        onclick="cambiarFiltroUsuarios(0)">Todos</button>
                    <button class="nav-link d-none" id="filtroAdmins"
                        onclick="cambiarFiltroUsuarios(11)">Administradores</button>
                    <button class="nav-link d-none" id="filtroUsuarios"
                        onclick="cambiarFiltroUsuarios(12)">Usuarios</button>
                </div>
            </div>

            <div class="col-12 col-md-auto">

                <input class="form-control" type="search" id="barraBusqueda" placeholder="Buscar" aria-label="Search"
                    onkeyup="filtrarUsuarios()">
                <!-- <button class="btn btn-outline-primary" type="submit">Buscar</button> -->

            </div>
        </nav>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="col-2">Nombre de usuario</th>
                        <th class="col-auto">Nombre completo</th>
                        <th class="col-1">Rol</th>
                        <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
                        <th class="col-1 text-end">Acciones</th>
                        <?php endif ?>
                    </tr>
                </thead>
                <tbody id="tablaItems">
                </tbody>
            </table>
        </div>
        
        <div class="d-flex flex-column align-items-center">
            <nav aria-label="Paginacion">
                <ul class="pagination py-3" id="ulPaginacion">

                </ul>
            </nav>

        </div>


    </div>

</div>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>

<script>
    filtroActivo = 0;
    filtrarUsuarios();
</script>