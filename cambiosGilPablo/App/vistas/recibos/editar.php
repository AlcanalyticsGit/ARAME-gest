<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php
    $recibo=$this->datos['reciboCompleto']['recibo'];
    $socia=$this->datos['reciboCompleto']['socia'];
?>

<form method="POST" class="container-fluid p-0 ps-3" enctype="multipart/form-data">

    <div class="row mb-4">
        <h3>Recibo</h3>

        <div class="row">
            <div class="col-12 col-sm-5 col-lg-4 col-xl-3">

                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" id="socia_nombre" class="form-control form-control-sm"
                            value="<?php  echo $socia->nombre." ".$socia->apellidos ?>"
                            placeholder="Concepto" disabled>
                        <label for="socia_nombre">Nombre de socia</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" id="socia_nif" class="form-control form-control-sm"
                            value="<?php  echo $socia->nif ?>" placeholder="NIF" disabled>
                        <label for="socia_nif">NIF</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" id="socia_cod" class="form-control form-control-sm"
                            value="<?php  echo $socia->cod ?>" placeholder="Número de socia" disabled>
                        <label for="socia_cod">Número de socia</label>
                    </div>
                </div>
                <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="text" name="concepto" id="concepto" class="form-control form-control-sm"
                                value="<?php  echo $recibo->concepto ?>" placeholder="Concepto" required>
                            <label for="concepto">Concepto</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input type="number" min="0" name="cuantia" id="cuantia"
                                class="form-control form-control-sm" value="<?php echo $recibo->cuantia ?>"
                                placeholder="Cuantía (€)">
                            <label for="cuantia">Cuantía (€)</label>
                        </div>
                    </div>
            </div>

            <div class="col-12 col-sm-7 col-lg-8 col-xl-9">
                <div class="row">


                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="nombre" id="nombre" class="form-control form-control-sm"
                                value="<?php  echo $recibo->nombre ?>" placeholder="Razón social facturación">
                            <label for="nombre">Razón social facturación</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="nif" id="nif" class="form-control form-control-sm"
                                value="<?php  echo $recibo->nif ?>" placeholder="NIF">
                            <label for="nif">NIF</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="dir" id="dir" class="form-control form-control-sm"
                                value="<?php  echo $recibo->direccion ?>" placeholder="Dirección">
                            <label for="dir">Dirección</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="number" name="cp" id="cp"
                                class="form-control form-control-sm" value="<?php  echo $recibo->cp ?>"
                                placeholder="cp">
                            <label for="cp">CP</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="poblacion" id="poblacion" class="form-control form-control-sm"
                                value="<?php  echo $recibo->poblacion ?>" placeholder="Población">
                            <label for="poblacion">Población</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="provincia" id="provincia" class="form-control form-control-sm"
                                value="<?php  echo $recibo->provincia ?>" placeholder="Provincia">
                            <label for="provincia">Provincia</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="pais" id="pais" class="form-control form-control-sm"
                                value="<?php  echo $recibo->pais ?>" placeholder="País">
                            <label for="pais">País</label>
                        </div>
                    </div>
                    
                </div>

            </div>
        </div>
    </div>

    <div class="row pt-2 d-flex justify-content-center mb-4">
        <div class="w-auto">
            <input type="submit" class="btn btn-primary" value="Guardar cambios">
        </div>
    </div>

</form>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>