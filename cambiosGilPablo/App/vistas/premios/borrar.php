<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>

<div class="container d-flex justify-content-center">


    <div class="card bg-light mt-5 card-center">
        <h2 class="card-header w-auto">Eliminar premio</h2>

        <form method="post" class="card-body">
            <p class="col-8">¿Está seguro de querer eliminar el siguiente premio? ¡Esta acción no se puede deshacer!</p>

            <div class="mt-3 mb-3 border w-auto p-3 rounded bg-white">
                <h6 class="d-inline-block w-auto">Año:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['premio']->year ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Socia:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['premio']->socia_nombre." ".$datos['premio']->socia_apellidos ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Descripción:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['premio']->descripcion ?></p>
                <br>
            </div>
            
            <div class="d-flex justify-content-between">
                <div>
                    <a href="<?php echo RUTA_URL.'/premios' ?>" class="btn btn-secondary">Cancelar</a>
                </div>
                <div>
                    <input type="submit" class="btn btn-danger" value="Sí, eliminar premio">
                </div>

            </div>
        </form>

    </div>
</div>
<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>