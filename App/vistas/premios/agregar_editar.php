<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php require_once RUTA_APP.'/vistas/premios/script_inicio_premios.php' ?>

<?php
    isset($datos['socia']->cod  ) ? $accion = "Modificar" : $accion = "Crear";
?>

<form method="POST" class="container-fluid p-0 ps-3" enctype="multipart/form-data">

    <div class="row mb-4">
        <h4>Información del premio</h4>
        <div class="row">
            <div class="col-12 col-md-8">
                <div class="row mb-4">

                    <div class="col-12 col-xs-5 col-sm-4 col-md-3 col-lg-2">
                        <div class="form-floating mb-3">
                            <input type="number" name="year" id="year" class="form-control form-control-sm"
                                value="<?php echo $datos['premio']->year ?>" placeholder="Año" required>
                            <label for="year">Año</label>
                        </div>
                    </div>

                    <div class="col-12 col-xs-7 col-sm-8 col-md-9 col-lg-10 mb-3">
                        <div class="form-floating">
                            <input class="form-control" list="datalistOptions" id="socia" name="socia"
                                placeholder="Socia premiada" value="<?php if (isset($datos['premio']->socia_cod)) echo $datos['premio']->socia_cod ?>" required>
                            <datalist id="datalistOptions">
                                <?php foreach ($datos['socias'] as $socia) : ?>
                                <option value="<?php echo $socia->cod ?>">
                                    <?php echo $socia->nombre.' '.$socia->apellidos ?>
                                </option>
                                <?php endforeach ?>
                            </datalist>
                            <label for="socia" class="form-label">Socia premiada</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text">Descripción</span>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="2"
                                aria-label="Descripción"><?php echo $datos['premio']->descripcion ?></textarea>
                        </div>
                    </div>

                </div>

                
                <div class="row pt-2 d-flex justify-content-center mb-4">
                    <div class="w-auto">
                        <input type="submit" class="btn btn-primary"
                            value="<?php echo ($accion=='Crear' ? "Guardar premio" : "Guardar cambios")?>">
                    </div>
                </div>


            </div>

        </div>
    </div>


</form>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>