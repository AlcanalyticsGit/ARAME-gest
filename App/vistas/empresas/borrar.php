<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>

<div class="container d-flex justify-content-center">


    <div class="card bg-light mt-5 card-center">
        <h2 class="card-header w-auto">Eliminar empresa</h2>

        <form method="post" class="card-body">
            <p class="col-12 col-lg-9">¿Está seguro de querer eliminar la siguiente empresa?</p>

            <div class="mt-3 mb-3 border w-auto p-3 rounded bg-white">
                <h6 class="d-inline-block w-auto">Nombre:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['empresa']->nombre ?></p>
                <br>
                <h6 class="d-inline-block w-auto">NIF: </h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['empresa']->nif ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Dirección: </h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['empresa']->dir.', '.$datos['empresa']->cp.' '.$datos['empresa']->poblacion.' '.$datos['empresa']->provincia.' '.$datos['empresa']->pais ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Teléfono / Fax: </h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $datos['empresa']->telefono .' / '.$datos['empresa']->fax ?></p>
                <br>
                
            </div>

            <p><strong>ATENCIÓN: ¡Esta acción no se puede deshacer!</strong></p>

            
            <div class="d-flex justify-content-between">
                <div>
                    <a href="<?php echo RUTA_URL.'/empresas' ?>" class="btn btn-secondary">Cancelar</a>
                </div>
                <div>
                    <input type="submit" class="btn btn-danger" value="Sí, eliminar empresa">
                </div>

            </div>
        </form>

    </div>
</div>
<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>