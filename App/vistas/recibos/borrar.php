<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php
    $recibo = $datos['reciboCompleto']['recibo'];
    $socia = $datos['reciboCompleto']['socia'];
?>

<div class="container d-flex justify-content-center">


    <div class="card bg-light mt-5 card-center">
        <h2 class="card-header w-auto">Eliminar recibo</h2>

        <form method="post" class="card-body">
            <p class="col-8">¿Está seguro de querer eliminar el siguiente recibo? ¡Esta acción no se puede deshacer!</p>

            <div class="mt-3 mb-3 border w-auto p-3 rounded bg-white">
                <h6 class="d-inline-block w-auto">Código:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $recibo->year."/".$recibo->semestre."/".$recibo->cod ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Fecha de emisión:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $recibo->fecha ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Asociada:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $socia->nombre." ".$socia->apellidos ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Razón social:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $recibo->nombre ?></p>
                <br>
                <h6 class="d-inline-block w-auto">Dirección de facturación:</h6>
                <p class="d-inline-block w-auto mb-0"><?php echo $recibo->direccion." ".$recibo->cp." ".$recibo->poblacion." ".$recibo->provincia." ".$recibo->pais ?></p>

                
            </div>
            
            <div class="d-flex justify-content-between">
                <div>
                    <a href="<?php echo RUTA_URL.'/recibos' ?>" class="btn btn-secondary">Cancelar</a>
                </div>
                <div>
                    <input type="submit" class="btn btn-danger" value="Sí, eliminar recibo">
                </div>

            </div>
        </form>

    </div>
</div>
<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>