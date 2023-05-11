<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>

<?php
    isset($datos['empresa']->nif  ) ? $accion = "Modificar" : $accion = "Crear";
?>

<form method="POST" class="container-fluid p-0 ps-3" enctype="multipart/form-data">

    <div class="row mb-4">
        <h3>Información de la empresa</h3>

        <div class="row">

            <div class="col-12 col-sm-3 mb-3">
                <div class="col-12 mb-2">

                    <?php if($accion=="Modificar"): ?>
                    <img src="<?php echo $datos['empresa']->logo ?>" class="img-thumbnail profilepic"
                        alt="Logotipo <?php echo $datos['empresa']->nombre ?>">
                    <?php else : ?>
                    <img src="<?php echo RUTA_URL."/img/empresas/defaultlogo.jpg" ?>" class="img-thumbnail profilepic"
                        alt="Logotipo por defecto">
                    <?php endif;?>
                </div>

                <div class="col-12">
                    <div class="input-group input-group-sm">
                        <!-- <label class="input-group-text" for="logo">Logotipo</label> -->
                        <input type="file" class="form-control form-control" id="logo" name="logo"
                        accept="image/png, image/jpeg, image/jpg, image/gif"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-9 mb-3">

                <div class="row mb-3">
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="nombre" id="nombre" class="form-control form-control-sm"
                                value="<?php echo $datos['empresa']->nombre ?>" placeholder="Razón social" required>
                            <label for="nombre">Razón social</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="nif" id="nif" class="form-control form-control-sm"
                                value="<?php echo $datos['empresa']->nif ?>" placeholder="NIF" required>
                            <label for="nif">NIF</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-floating mb-3">
                            <input type="number" min="0" name="fundacion" id="fundacion"
                                class="form-control form-control-sm"
                                value="<?php echo $datos['empresa']->year_fundacion ?>" placeholder="Año de fundación">
                            <label for="fundacion">Año de fundación</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-floating mb-3">
                            <input type="number" min="0" name="num_trabajadores" id="num_trabajadores"
                                class="form-control form-control-sm"
                                value="<?php echo $datos['empresa']->num_trabajadores ?>"
                                placeholder="Número de trabajadores">
                            <label for="num_trabajadores">Núm. trabajadores</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="web" id="web" class="form-control form-control-sm"
                                value="<?php echo isset($datos['empresa']->sitio_web) ? $datos['empresa']->sitio_web : '' ?>" placeholder="Sitio web">
                            <label for="web">Sitio web</label>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-sm-3 mb-3">
                        <div class="col-auto">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="autonoma" name="autonoma"
                                    <?php if ($datos['empresa']->es_autonoma==1) echo 'checked'; ?>>
                                <label class="form-check-label" for="autonoma">Es autónoma</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text">Descripción</span>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                aria-label="Descripción"><?php echo $datos['empresa']->descripcion ?></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">
        <h3>Datos de contacto</h3>
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="form-floating mb-3">
                    <input type="text" name="dir" id="dir" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->dir ?>" placeholder="Dirección postal">
                    <label for="dir">Dirección postal</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-3 col-lg-2">
                <div class="form-floating mb-3">
                    <input type="number" name="cp" id="cp" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->cp ?>" placeholder="Código postal">
                    <label for="cp">Código postal</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="poblacion" id="poblacion" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->poblacion ?>" placeholder="Población">
                    <label for="poblacion">Población</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="provincia" id="provincia" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->provincia ?>" placeholder="Provincia">
                    <label for="provincia">Provincia</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="pais" id="pais" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->pais ?>" placeholder="País">
                    <label for="pais">País</label>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="form-floating mb-3">
                    <input type="email" name="email" id="email" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->email?>" placeholder="Correo electrónico">
                    <label for="email">Correo electrónico</label>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="tlf" id="tlf" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->telefono ?>" placeholder="Teléfono 1">
                    <label for="tlf">Teléfono 1</label>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="tlf2" id="tlf2" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->telefono_2 ?>" placeholder="Teléfono 2">
                    <label for="tlf2">Teléfono 2</label>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="fax" id="fax" class="form-control form-control-sm"
                        value="<?php echo $datos['empresa']->fax ?>" placeholder="Fax">
                    <label for="fax">Fax</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <h3>Sectores</h3>
        <div class="col-12 col-md-6 mb-3">
            <select class="form-select" size="7" aria-label="Selector sectores" id="sectores">
                <?php foreach ($datos['sectores'] as $sector) : ?>
                <option value="<?php echo $sector->nombre?>" id="sectores-<?php echo $sector->nombre ?>"
                    title="<?php echo $sector->nombre ?>" ondblclick="incorporarSector()">
                    <?php echo $sector->nombre ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <select class="form-select" size="7" aria-label="Selector sectores vinculados a empresa"
                id="sectores-empresa-listado">
                <?php foreach ($datos['empresa']->sectores as $sector) : ?>
                <option value="<?php echo $sector->nombre?>" id="sectores-empresa-<?php echo $sector->nombre ?>"
                    title="<?php echo $sector->nombre ?>" ondblclick="desIncorporarSector()">
                    <?php echo $sector->nombre ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div id="sectores-empresa" name="sectores-empresa">
            <?php if($accion=='Modificar') foreach ($datos['empresa']->sectores as $sector) :?>
            <input name="sectores-empresa[]" type="hidden" value="<?php echo $sector->nombre ?>">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mb-4">
        <h3>Socias relacionadas</h3>
        <div class="col-12 col-md-6 mb-3">
            <select class="form-select" size="7" aria-label="Selector socias" id="socias">
                <?php foreach ($datos['socias'] as $socia) : ?>
                <option value="<?php echo $socia->cod?>" id="socias-<?php echo $socia->cod ?>"
                    title="<?php echo $socia->nombre." ".$socia->apellidos." (".$socia->nif.")" ?>"
                    ondblclick="incorporarSocia()">
                    <?php echo $socia->nombre." ".$socia->apellidos." (".$socia->nif.")" ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <select class="form-select" size="7" aria-label="Selector socias vinculadas a empresa"
                id="socias-empresa-listado">
                <?php foreach ($datos['socias_empresa'] as $socia) : ?>
                <option value="<?php echo $socia->cod?>" id="socias-empresa-<?php echo $socia->cod ?>"
                    title="<?php echo $socia->nombre." ".$socia->apellidos." (".$socia->nif.")" ?>"
                    ondblclick="desIncorporarSocia()">
                    <?php echo $socia->nombre." ".$socia->apellidos." (".$socia->nif.")" ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div id="socias-empresa">
            <?php if(isset($datos['socias_empresa'])): ?>
                <?php foreach ($datos['socias_empresa'] as $socia) :?>
                    <input name="socias-empresa[]" type="hidden" value="<?php echo $socia->cod ?>">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mb-4">
        <h3>Otros</h3>
        <div class="col-12 col-md-5 col-lg-4">
            <div class="form-floating mb-3">
                <input type="text" name="iban" id="iban" class="form-control form-control-sm"
                    value="<?php echo $datos['empresa']->iban ?>" placeholder="IBAN">
                <label for="iban">IBAN</label>
            </div>
        </div>

        <div class="col-12 col-sm-8">
            <div class="input-group">
                <span class="input-group-text">Notas</span>
                <textarea class="form-control" id="notas" name="notas" rows="5"
                    aria-label="Notas"><?php echo $datos['empresa']->notas ?></textarea>
            </div>
        </div>
    </div>

    <div class="row pt-2 d-flex justify-content-center mb-4">
        <div class="w-auto">
            <input type="submit" class="btn btn-primary"
                value="<?php echo ($accion=='Crear' ? "Crear nueva empresa" : "Guardar cambios")?>">
        </div>
    </div>

</form>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>