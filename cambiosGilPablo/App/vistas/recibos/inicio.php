<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php // Declara vRecibos en Javascript y lo puebla con $datos['recibos]. Se ha movido a otro fichero porque Beautify lo rompía cada vez que se aplicaba en esta vista.
require_once RUTA_APP.'/vistas/recibos/script_inicio_recibos.php' ?>

<div class="row ps-3">
    <div class="container mb-3">
        <!-- <h3>Recibos</h3> -->

        <div class="row g-3">
            <div class="col-6 col-md-2">
                <div class="form-floating">
                    <select class="form-select" id="ficheros-year" name="ficheros-year" aria-label="Selector año"
                        onchange="poblarDesplegablesRecibos()" required>
                        <option selected disabled value id="defYearRecibos" style="display: none;">Seleccione año...</option>
                    </select>
                    <label for="ficheros-year">Año</label>
                </div>
            </div>

            <div class="col-6 col-md-2">
                <div class="form-floating">
                    <select class="form-select" id="ficheros-semestre" name="ficheros-semestre"
                        aria-label="Selector semestre" onchange="listarRecibos()" required>
                        <option selected disabled value id="defSemRecibos" style="display: none;">Seleccione semestre...</option>
                    </select>
                    <label for="ficheros-semestre">Semestre</label>
                </div>
            </div>
            
            <div class="col-12 col-md-auto d-none">
                <a href="#" class="nav-link h-100" id="enlaceListaRecibos">
                    <div class="btn btn-primary h-100 d-flex align-items-center justify-content-center">
                        <span><i class="bi bi-download me-2"></i>Descargar fichero para bancos (.xlsx)</span>
                    </div>
                </a>

            </div>

            <div class="col-12 col-md-auto d-none">
                 <a href="#" class="nav-link h-100" id="enlaceRecibosPdf" onmouseup="mostrarAlertDescarga()"> 
                    <div class="btn btn-primary h-100 d-flex align-items-center justify-content-center">
                        <span><i class="bi bi-file-earmark-pdf me-2"></i>Descargar todos (.pdf)</span>
                    </div>
                </a>

            </div>

            <div class="col-12 col-md-auto d-none">
                 <a href="#" class="nav-link h-100" id="enviarRemesa" onmouseup="mostrarAlertEnviarRemesa()" > 
                    <div class="btn btn-primary h-100 d-flex align-items-center justify-content-center">
                        <span><i class="bi bi-envelope me-2"></i>Enviar todos los recibos</span>
                    </div>
                </a>

            </div>

            <!-- <div class="btn btn-primary col-12 col-md-auto d-none d-flex">
            <i class="bi bi-envelope me-2"></i>
            <input type="submit" class="h-100 col-12" id="enviarRemesa" name="submit_action" href="#" value="Enviar todos los recibos" onmouseup="mostrarAlertEnviarRemesa()">


                 <a href="#" class="nav-link h-100" id="enviarRemesa" > 
                    <div class="btn btn-primary h-100 d-flex align-items-center justify-content-center">
                        <span><i class="bi bi-envelope me-2"></i>Enviar todos</span>
                    </div>
                </a>

            </div> -->
            
            <div class="col-12 mt-3 d-none mb-2">
                <ul class="list-group" id="lista-recibos">
                </ul>
            </div>
        </div>

    </div>

    <div class="table-responsive d-flex flex-column align-items-center d-none">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-1">Código</th>
                    <th class="col-1">Fecha</th>
                    <th class="col-1">Importe</th>
                    <th class="col-auto">Asociada</th>
                    <th class="col-4">Notas</th>
                    <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
                    <th class="col-1 text-end">Acciones</th>

                    <?php endif ?>
                </tr>
            </thead>
            <tbody id="tablaItems">
            </tbody>
        </table>

        <nav aria-label="Paginacion">
            <ul class="pagination py-3" id="ulPaginacion">
            </ul>
        </nav>


    </div>

</div>

<div class="row px-3">
    <div class="col">
        <hr class="my-4">
    </div>
</div>

<div class="row px-3 mb-5">

    <div class="container">

        <h3>Emitir nueva remesa</h3>
        <div class="row">
            <div class="col-12 col-md-10 col-lg-8">
                <p>Rellene los campos y haga click en el botón para generar una remesa de recibos en la base de datos
                    con
                    fecha de hoy. Tenga en cuenta lo siguiente:</p>
                <ul>
                    <li><strong>No</strong> se generarán ficheros PDF.</li>
                    <li><strong>No</strong> se enviará ningún correo electrónico a las asociadas.</li>
                    <li>Los recibos ya existentes <strong>no</strong> se verán afectados.</li>
                </ul>
            </div>
        </div>

        <form method="post" class="row">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                <div class="input-group mb-3">
                    <label for="form-concepto" class="input-group-text">Concepto remesa</label>
                    <input type="text" class="form-control" name="form-concepto" id="form-concepto" required>
                </div>
            </div>

            <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                <div class="col-12 d-flex">
                    <div class="input-group">
                        <label for="form-year" class="input-group-text">Año</label>
                        <input type="number" class="form-control" name="form-year" id="form-year"
                            min="<?php echo ((int)date("Y"))-1 ?>" value="<?php echo date("Y") ?>" required>

                        <label for="form-semestre" class="input-group-text">Semestre</label>
                        <select class="form-select" id="form-semestre" name="form-semestre"
                            aria-label="Selector semestre" required>
                            <option selected disabled value style="display: none;"></option>
                            <option value="1S">Primer semestre (1S)</option>
                            <option value="2S">Segundo semestre (2S)</option>
                        </select>
                    </div>

                    <div class="ps-3">
                        <input type="submit" class="btn btn-primary" name="submit_action" id="form-boton"
                            value="Generar remesa" onmouseup="activarLS()">
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<!-- <div class="row px-3">
    <div class="col">
        <hr class="my-4">
    </div>
</div> -->

<!-- <form method="post" class="row px-3">
    <div class="container mb-5">
        <h3>Gestión de recibos en masa</h3>
        <div class="row">
            <div class="col-12 col-md-10 col-lg-8">
                <p>Seleccione una de las remesas existentes y presione el botón correspondiente para generar en masa los
                    ficheros o enviarlos por correo. Tenga en cuenta que los ficheros <strong>no</strong> se descargarán
                    en
                    el
                    equipo, sino que se almacenarán en el servidor. El proceso puede tardar algunos minutos.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7">

                <div class="input-group">
                    <label for="form-ficheros-year" class="input-group-text">Año</label>
                    <select class="form-select" id="form-ficheros-year" name="form-ficheros-year"
                        aria-label="Selector año" onchange="poblarDesplegablesFicheros()" required>
                        <option selected disabled value id="defYearFicheros" style="display: none;"></option>
                    </select>

                    <label for="form-ficheros-semestre" class="input-group-text">Semestre</label>
                    <select class="form-select col-3" id="form-ficheros-semestre" name="form-ficheros-semestre"
                        aria-label="Selector semestre" required disabled>
                        <option selected disabled value id="defSemFicheros" style="display: none;"></option>
                    </select>
                    <input type="submit" class="btn btn-primary" name="submit_action" id="form-ficheros-boton"
                        value="Generar ficheros" disabled onmouseup="activarLS()">
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false" id="form-ficheros-boton-mas-acciones" disabled>
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><input type="submit" class="dropdown-item" id="form-ficheros-boton-enviar"
                                name="submit_action" href="#" value="Enviar todos los recibos" onmouseup="activarLS()">
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form> -->


<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>

<script>
    filtroActivo = 0;
    // filtrarRecibos();
    // listarRecibos();
    elementosPorPagina = 10;
    poblarDesplegables();
    // poblarDesplegablesFicheros();


</script>