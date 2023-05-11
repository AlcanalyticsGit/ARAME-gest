<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php require_once RUTA_APP.'/vistas/empresas/script_inicio_empresas.php' ?>

<?php
    isset($datos['socia']->cod  ) ? $accion = "Modificar" : $accion = "Crear";
?>

<form method="POST" class="container-fluid p-0 ps-3" enctype="multipart/form-data">

    <?php if($this->datos['usuarioSesion']->rol<=20) : ?>
    <?php if($accion=="Modificar") : ?>
    <div class="row mb-4">
        <div class="col-12 d-flex">
            <div class="me-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="alta" name="alta"
                        <?php if ($datos['socia']->alta==1) echo 'checked'; ?>>
                    <label class="form-check-label" for="alta">Socia de alta</label>
                </div>
            </div>
            <div class="me-4"><strong>Número de socia:</strong> <?php echo $datos['socia']->cod ?></div>
            <div class="me-4"><strong>ALTA:</strong>
                <?php echo $datos['socia']->fecha_alta ?></div>
            <?php if($datos['socia']->alta=='0') : ?>
            <div class=""><strong>BAJA:</strong> <?php echo $datos['socia']->fecha_baja ?>
            </div>
            <?php endif ; ?>
            <div>
                <?php foreach ($datos['socia']->premios as $premio) : ?>
                <span class="badge bg-premio"><i class="bi bi-award"> </i><?php echo $premio->year ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <div class="row mb-4">
        <h4>Datos personales</h4>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3 col-lg-2 col-xl-2 mb-3">
                <div class="col-12 mb-2">
                    <div class="col-auto mb-3">

                        <?php if($accion=="Crear"): ?>
                        <img src="<?php echo RUTA_URL."/img/socias/defaultprofilepic.png" ?>"
                            class="img-thumbnail profilepic" alt="Imagen de perfil">
                        <?php else : ?>

                        <img src="<?php echo $datos['socia']->profilepic ?>" class="img-thumbnail profilepic"
                            alt="Imagen de perfil de <?php echo $datos['socia']->nombre.' '.$datos['socia']->apellidos ?>">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-group input-group-sm">
                        <label class="input-group-text" for="avatar">Foto</label>
                        <input type="file" class="form-control form-control" id="avatar" name="avatar"
                            accept="image/png, image/jpeg, image/jpg, image/gif"></div>

                </div>
            </div>

            <div class="col-12 col-md-8">
                <div class="row">
                    <div class="col-12 col-lg-5">
                        <div class="form-floating mb-3">
                            <input type="text" name="nombre" id="nombre" class="form-control form-control-sm"
                                value="<?php echo $datos['socia']->nombre ?>" placeholder="Nombre" required>
                            <label for="nombre">Nombre <sup>*</sup></label>
                        </div>
                    </div>

                    <div class="col-12 col-lg-7">
                        <div class="form-floating mb-3">
                            <input type="text" name="apellidos" id="apellidos" class="form-control form-control-sm"
                                value="<?php echo $datos['socia']->apellidos ?>" placeholder="Apellidos" required>
                            <label for="apellidos">Apellidos <sup>*</sup></label>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 col-lg-4">
                        <div class="form-floating mb-3">
                            <input type="text" name="nif" id="nif" class="form-control form-control-sm"
                                value="<?php echo $datos['socia']->nif ?>" placeholder="DNI / NIE">
                            <label for="nif">DNI / NIE <sup>*</sup></label>
                        </div>
                    </div>


                    <!-- </div> -->
                    <!-- <div class="row"> -->

                </div>
            </div>

        </div>
    </div>

    <div class="row mb-4">

        <h4>Datos de contacto</h4>

        <div class="row">

            <div class="col-12 col-md-3 col-lg-2">
                <div class="form-floating mb-3">

                    <input type="text" name="tlf" id="tlf" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->tlf ?>" placeholder="Teléfono">
                    <label for="tlf">Teléfono</label>
                </div>
            </div>

            <div class="col-12 col-md-3 col-lg-2">
                <div class="form-floating mb-3">

                    <input type="text" name="movil" id="movil" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->movil ?>" placeholder="Móvil">
                    <label for="movil">Móvil</label>
                </div>
            </div>

            <div class="col-12 col-md-3 col-lg-2">
                <div class="form-floating mb-3">
                    <input type="text" name="fax" id="fax" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fax?>" placeholder="Fax">
                    <label for="fax">Fax</label>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="form-floating mb-3">
                    <input type="email" name="email" id="email" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->email?>" placeholder="Correo electrónico">
                    <label for="email">Correo electrónico</label>
                </div>
            </div>


            <!-- <div class="row">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="form-floating mb-3">

                    <input type="text" name="dir" id="dir" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->dir ?>" placeholder="Dirección postal">
                    <label for="dir">Dirección postal</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-3 col-lg-2">
                <div class="form-floating mb-3">

                    <input type="number" name="cp" id="cp" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->cp ?>" placeholder="Código postal">
                    <label for="cp">Código postal</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">

                    <input type="text" name="poblacion" id="poblacion" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->poblacion ?>" placeholder="Población">
                    <label for="poblacion">Población</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="provincia" id="provincia" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->provincia ?>" placeholder="Provincia">
                    <label for="provincia">Provincia</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="pais" id="pais" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->pais ?>" placeholder="País">
                    <label for="pais">País</label>
                </div>
            </div>
        </div> -->

        </div>
    </div>


    <?php if($this->datos['usuarioSesion']->rol<=20) : ?>
    <div class="row mb-4">
        <h4>Información profesional</h4>
        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <select class="form-select" size="7" aria-label="Selector empresas" id="empresas">
                    <?php foreach ($datos['empresas'] as $empresa) : ?>
                    <option value="<?php echo $empresa->nif?>" id="emp-<?php echo $empresa->nif ?>"
                        title="NIF <?php echo $empresa->nif ?>" ondblclick="incorporarEmpresa()">
                        <?php echo $empresa->nombre ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-12 col-md-6 mb-3">
                <select class="form-select" size="7" aria-label="Selector empresas vinculadas a socia"
                    id="empresas-socia-listado">
                    <?php foreach ($datos['socia']->empresas as $empresa) : ?>
                    <option value="<?php echo $empresa->cif?>" id="emp-socia-<?php echo $empresa->cif ?>"
                        title="NIF <?php echo $empresa->cif ?>" ondblclick="desIncorporarEmpresa()">
                        <?php echo $empresa->empresa ?></option>
                    <?php endforeach ?>
                </select>
            </div>

        </div>
        <?php endif; ?>
        <div id="empresas-socia" name="">
            <?php if($accion=='Modificar') foreach ($datos['socia']->empresas as $empresa) :?>
            <input name="empresas-socia[]" type="hidden" value="<?php echo $empresa->cif ?>">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mb-4">
        <h4>Información de facturación</h4>

        <?php if($this->datos['usuarioSesion']->rol<=20) : ?>

        <!-- <div class="col-auto mb-3">
                    <button type="button" class="btn btn-primary w-auto" onmouseup="copiarDatosFactPers()">Copiar datos
                        personales</button>
                    </div> -->
        <div class="col-auto mb-3">
            <button type="button" class="btn btn-primary w-auto" onmouseup="copiarDatosFactEmp()">
                <i class="bi bi-journal-arrow-down me-1"></i>
                Copiar datos de la empresa seleccionada</button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="form-floating mb-3">
                    <input type="text" name="fact-nombre" id="fact-nombre" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fact_nombre ?>" placeholder="Razón social">
                    <label for="fact-nombre">Razón social</label>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="fact-nif" id="fact-nif" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fact_nif ?>" placeholder="NIF">
                    <label for="fact-nif">NIF</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="form-floating mb-3">
                    <input type="text" name="fact-dir" id="fact-dir" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fact_dir ?>" placeholder="Dirección postal">
                    <label for="fact-dir">Dirección postal</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-3 col-lg-2">
                <div class="form-floating mb-3">
                    <input type="number" name="fact-cp" id="fact-cp" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fact_cp ?>" placeholder="Código postal">
                    <label for="fact-cp">Código postal</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="fact-poblacion" id="fact-poblacion" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fact_poblacion ?>" placeholder="Población">
                    <label for="fact-poblacion">Población</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="fact-provincia" id="fact-provincia" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fact_provincia ?>" placeholder="Provincia">
                    <label for="fact-provincia">Provincia</label>
                </div>
            </div>

            <div class="col-12 col-md-4 col-lg-3">
                <div class="form-floating mb-3">
                    <input type="text" name="fact-pais" id="fact-pais" class="form-control form-control-sm"
                        value="<?php echo $datos['socia']->fact_pais ?>" placeholder="País">
                    <label for="fact-pais">País</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="metodo-pago" name="metodo-pago" aria-label="Método de pago">
                            <option <?php if($datos['socia']->metodo_pago=='') echo 'selected' ?>
                                style="display: none;">
                                Seleccionar</option>
                            <?php foreach ($datos['metodos_pago'] as $metodo_pago) : ?>
                            <option value="<?php echo $metodo_pago->metodo_pago ?>"
                                <?php if($datos['socia']->metodo_pago==$metodo_pago->metodo_pago) echo 'selected' ?>>
                                <?php echo $metodo_pago->metodo_pago ?></option>
                            <?php endforeach ?>

                        </select>
                        <label for="metodo-pago">Método de pago</label>
                    </div>
                </div>

                <div class="col-12 col-md-5 col-lg-4">
                    <div class="form-floating mb-3">
                        <input type="text" name="iban" id="iban" class="form-control form-control-sm"
                            value="<?php echo $datos['socia']->iban ?>" placeholder="IBAN">
                        <label for="iban">IBAN</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($this->datos['usuarioSesion']->rol<=20) : ?>
    <div class="row mb-4">
        <h4>Otros</h4>
        <div class="row">
            <div class="col-12 col-sm-4">
                <div class="col-auto mb-3">
                    <div class="form-floating">
                        <select class="form-select" id="cuota" name="cuota" aria-label="Cuota">
                            <option value="Normal" <?php if($datos['socia']->cuota=='Normal') echo 'selected' ?>>
                                Normal
                            </option>
                            <option value="Reducida" <?php if($datos['socia']->cuota=='Reducida') echo 'selected' ?>>
                                Bonificada</option>
                            <option value="Exenta" <?php if($datos['socia']->cuota=='Exenta') echo 'selected' ?>>
                                Exenta</option>
                        </select>
                        <label for="cuota">Cuota</label>
                    </div>
                </div>

                <div class="col-auto mb-3">
                    <div class="form-floating">

                        <input class="form-control" list="datalistOptions" id="referida_por" name="referida_por"
                            placeholder="Referida por..." value="<?php echo $datos['socia']->referida_por ?>">
                        <datalist id="datalistOptions">
                            <?php foreach ($datos['socias'] as $socia) : ?>
                            <option value="<?php echo $socia->cod ?>">
                                <?php echo $socia->nombre.' '.$socia->apellidos ?>
                            </option>
                            <?php endforeach ?>
                        </datalist>
                        <label for="referida_por" class="form-label">Referida por...</label>
                    </div>

                </div>
            </div>

            <div class="col-12 col-sm-8">

                <div class="input-group">
                    <span class="input-group-text">Notas</span>
                    <textarea class="form-control" id="notas" name="notas" rows="5"
                        aria-label="Notas"><?php echo $datos['socia']->notas ?></textarea>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>


    <div class="row pt-2 d-flex justify-content-center mb-4">
        <div class="w-auto">
            <input type="submit" class="btn btn-primary" name="submit"
                value="<?php echo ($accion=='Crear' ? "Registrar socia" : "Guardar cambios")?>">
            
        <?php if ($accion == 'Crear') :?>
            <!-- Button trigger modal -->
            <button onclick="cargarDatos()" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sociaAutonomaModal">
            Añadir datos empresa autónoma y socia
            </button>
            <!-- <input type="checkbox" name="addEmpresa" id="addEmpresa">
            <label for="addEmpresa">Añadir Empresa Autónoma Automáticamente</label> -->
        <?php endif ?>
        </div>
    </div>

</form>



<!-- MODAL -->
<div class="modal fade" id="sociaAutonomaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Datos Empresa Autónoma</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formModal" enctype="multipart/form-data">
                    <h4>Datos personales</h4>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="nombreModal" id="nombreModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_nombre ?>" placeholder="Nombre" required>
                                <label for="nombreModal">Nombre <sup>*</sup></label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="apellidosModal" id="apellidosModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_nif ?>" placeholder="Apellidos" required>
                                <label for="apellidosModal">Apellidos <sup>*</sup></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="dniModal" id="dniModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_dir ?>" placeholder="DNI" required>
                                <label for="dniModal">DNI <sup>*</sup></label>
                            </div>
                        </div>
                    </div>
                    <h4>Información empresa</h4>
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="razonSocialModal" id="razonSocialModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_nombre ?>" placeholder="Razón social" required>
                                <label for="razonSocialModal">Razón social <sup>*</sup></label>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="nifModal" id="nifModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_nif ?>" placeholder="NIF" required>
                                <label for="nifModal">NIF <sup>*</sup></label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="dirModal" id="dirModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_dir ?>" placeholder="Dirección postal">
                                <label for="dirModal">Dirección postal</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="number" name="cpModal" id="cpModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_cp ?>" placeholder="Código postal">
                                <label for="cpModal">Código postal</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-8 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="poblacionModal" id="poblacionModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_poblacion ?>" placeholder="Población">
                                <label for="poblacionModal">Población</label>
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="provinciaModal" id="provinciaModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_provincia ?>" placeholder="Provincia">
                                <label for="provinciaModal">Provincia</label>
                            </div>
                        </div>

                        <div class="col-12 col-md-6 col-lg-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="paisModal" id="paisModal" class="form-control form-control-sm"
                                    value="<?php echo $datos['socia']->fact_pais ?>" placeholder="País">
                                <label for="paisModal">País</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <input type="submit" class="btn btn-primary" name="postModal" value="Guardar">

                </form>
            </div>
        </div>
    </div>
</div>


<!--FIN MODAL -->

<script>
    
    function cargarDatos(){
    
        var nom = document.getElementById("nombre").value;
        var ape = document.getElementById("apellidos").value;
        var nif = document.getElementById("nif").value;
        var avatar = document.getElementById("avatar");
        var form = document.getElementById("formModal");
        avatar.style.display = "none";

        form.appendChild(avatar);

        // console.log(nombreAvatar);
    
        if (isEmpty(ape)) {
            var datosRazonSocial = nom;
        }else{
            var datosRazonSocial = nom + " " + ape;
        }

        document.getElementById("nombreModal").value = nom;
        document.getElementById("apellidosModal").value = ape;
        document.getElementById("dniModal").value = nif;
        document.getElementById("razonSocialModal").value = datosRazonSocial;
        document.getElementById("nifModal").value = nif;
        
    }

    function isEmpty(value) {
        return (value === undefined || value == null || value.length === 0);
    }
    
</script>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>