<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>

<div class="container d-flex justify-content-center">


    <div class="card bg-light mt-5 card-center">
        <h2 class="card-header w-auto">Eliminar usuario</h2>

        <form method="post" class="card-body">
            <p class="col-8">¿Está seguro de querer eliminar el siguiente usuario? ¡Esta acción no se puede deshacer!</p>

            <div class="mt-3 mb-3 border w-auto p-3 rounded bg-white">
                <h6 class="d-inline-block w-auto">Nombre:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['usuario']->nombre ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Apellidos: </h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['usuario']->apellido_1." ".$datos['usuario']->apellido_2 ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Usuario: </h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['usuario']->username ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Nivel de usuario: </h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['usuario']->rol ?></p>
            </div>
            
            <div class="d-flex justify-content-between">
                <div>
                    <a href=".." class="btn btn-secondary">Cancelar</a>
                </div>
                <div>
                    <input type="submit" class="btn btn-danger" value="Eliminar usuario">
                </div>

            </div>
        </form>

    </div>
</div>
<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>