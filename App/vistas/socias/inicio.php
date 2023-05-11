<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php // Declara vSocias en Javascript y lo puebla con $datos['usuarios]. Se ha movido a otro fichero porque Beautify lo rompía cada vez que se aplicaba en esta vista.
require_once RUTA_APP.'/vistas/socias/script_inicio_socias.php'; ?>


    <nav class="nav row mb-3 px-3" > 

        <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
        <div class="col-auto d-flex p-0">
            <a class="btn btn-outline-primary" style="margin-right: 5px;" href="<?php echo RUTA_URL?>/socias/agregar/"><i
                    class="bi bi-person-plus-fill pe-2"></i>Nueva socia</a>
        </div>
        <?php endif ?>

        
            <div class="col-auto d-flex ms-auto me-auto p-0" id="ocultar1">
                <div class="nav nav-pills d-flex">
                    <button class="nav-link d-none" aria-current="page" id="filtroTodos" onclick="cambiarFiltroSocias(0)">Todas</button>
                    <button class="nav-link d-none" id="filtroAlta" onclick="cambiarFiltroSocias(11)">De alta</button>
                    <button class="nav-link d-none" id="filtroBaja" onclick="cambiarFiltroSocias(12)">De baja</button>
                    <!-- <button class="nav-link text-secondary" id="filtroAutonoma" onclick="cambiarFiltroSocias(21)">Autónomas</button>
                    <button class="nav-link text-secondary" id="filtroEmpresas" onclick="cambiarFiltroSocias(22)">Vinculadas a empresa</button> -->
                </div>
            </div>
            
            
            <div class="col-auto d-flex p-0" id="ocultar2">
            
                <input class="form-control" type="search" id="barraBusqueda" placeholder="Buscar" aria-label="Search" oninput="filtrarSocias()">
                <!-- <button class="btn btn-outline-primary" type="submit">Buscar</button> -->
            
            </div>
        
    </nav>  
    
    <!-- <div class="col-auto d-flex p-0" id="radios" style="display: none;">
    <button id="elegidas" name="elegidas">Mostrar Socias Elegidas</button>

    
    </div> -->

    <div class="d-flex border rounded p-2">

        
        <div>
            <select class="form-select" aria-label="" id="seleccionar" name="seleccionar">
                <option value="0" style="display:none;">Selección múltiple</option>
                <option value="1">Seleccionar todas</option>
                <option value="2">Solo esta página</option>
                <option value="3">Desmarcar todas</option>
            </select>
        </div>
        
        
        <div class="px-3">
            <button type="button" class="btn btn-primary color-principal" id="botonEnviarCorreo" data-bs-toggle="modal" data-bs-target="">
                Enviar correo<i class="bi bi-envelope ms-2"></i>
            </button> 
        </div>

        <div id="radios">
            <div>
                <input type="radio" name="opcion" id="opcion1" value="1">
                <label for="opcion1">Mostrar seleccionadas</label><br>
            </div>
            <div>
                <input type="radio" name="opcion" id="opcion2" value="2">
                <label for="opcion2">Mostrar todas</label><br>
            </div>
        </div>
    </div>


    <div class="table-responsive">
        
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class=""></th>
                        <th class="">Nº</th>
                        <th class="col-auto">Nombre</th>
                        <th class="col-1">Teléfono</th>
                        <th class="col-1">Móvil</th>
                        <th class="col-1">Correo electrónico</th>
                        <th class="col-auto">Empresa</th> 
                        <!-- <?php// if (tienePrivilegios($datos['usuarioSesion']->rol,[10])):?>
                        <th></th>
                        <?php //endif ?> -->
                    </tr>
                </thead>
                <tbody id="tablaItems">
                </tbody>
            </table>    
    </div>
    
    <div class="d-flex flex-column align-items-center">
        
        <div class="ms-auto">
            <a href="<?php echo RUTA_URL ?>/socias/mostrarListadoSocias" class="nav-link color-principal">
            Descargar listado completo (.xlsx)<i class="bi bi-download ms-2"></i>   </a>
            
        </div>

        <nav aria-label="Paginacion">
            <ul class="pagination py-3" id="ulPaginacion">

            </ul>
        </nav>
    </div>
    
    
            
    <!-- MODAL VER PERFIL-->
    <div class="modal fade" id="verPerfilModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloPerfilModal"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="deshabilitarModalSocia">
                    
                        <div class="row">
                            <div class="col-6" id="parteSocia">
                                <div class="row border rounded mx-1 my-2 p-1">
                                    
                                    <div class="col-12 col-md-6 col-lg-8">
                                        <h3>Datos personales</h3>
                                        <div class="col-12">
                                            <div class="mb-2">
                                                <h6 class="mb-0">Nombre completo</h6>
                                                <span name="nombreModal" id="nombreModal"></span>
                                            </div>
                                            <div class="mb-2">
                                                <h6 class="mb-0">DNI</h6>
                                                <span name="dniModal" id="dniModal"></span>
                                            </div>
                                            <div class="mb-2">
                                                <h6 class="mb-0">Referida por</h6>
                                                <span name="referidaModal" id="referidaModal"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 p-0">
                                        <div id="foto">
                                            <img src="" alt="" id="fotoPerfilModal" class="rounded profilepic">
                                        </div>
                                    </div>
                                </div>
        
                                <div class="row border rounded mx-1 my-2 p-1">
                                <h3>Datos de contacto</h3>
                                    <div class="mb-2 col-4">
                                        <h6 class="mb-0">Teléfono fijo</h6>
                                        <span name="telefonoModal" id="telefonoModal"></span>
                                    </div>
                                    <div class="mb-2 col-4">
                                        <h6 class="mb-0">Teléfono móvil</h6>
                                        <span name="movilModal" id="movilModal"></span>
                                    </div>
                                    <div class="mb-2 col-4">
                                        <h6 class="mb-0">Fax</h6>
                                        <span name="faxModal" id="faxModal"></span>
                                    </div>
                                    <div class="mb-2 col-12">
                                        <h6 class="mb-0">Correo electrónico</h6>
                                        <span name="correoModal" id="correoModal"></span>
                                    </div>
                                </div>
        
                                <div class="row border rounded mx-1 my-2 p-1">
                                    <h3>Información de facturación</h3>
                                    <div class="row">
                                        <div class="mb-2 col-8">
                                            <h6 class="mb-0">Dirección de facturación</h6>
                                            <span name="razonSocialModal" id="razonSocialModal"></span>
                                        </div>
                                        <div class="mb-2 col-4">
                                            <h6 class="mb-0">NIF</h6>
                                            <span name="nifModal" id="nifModal"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-2 col-2">
                                        <h6 class="mb-0">Cuota</h6>
                                        <span name="cuotaModal" id="cuotaModal"></span>
                                    </div>
                                    <div class="mb-2 col-4">
                                        <h6 class="mb-0">Método de Pago</h6>
                                        <span name="metPagoModal" id="metPagoModal"></span>
                                    </div>
                                    <div class="mb-2 col-6">
                                        <h6 class="mb-0">IBAN</h6>
                                        <span name="ibanModal" id="ibanModal"></span>
                                    </div>
                                </div>
            
                                <div class="row border rounded mx-1 my-2 p-1">
                                    <div class="mb-2 col-12">
                                        <h3 class="mb-0">Notas</h3>
                                        <span id="notasModal" name="notasModal"></span>
                                    </div>
                                </div>
                                    
                            </div>

                        <div class="col-6" id="parteEmpresa">
                            
                            <div class="row border rounded mx-1 my-2 p-1">
                                <div class="col-12 col-md-6 col-lg-8">
                                    <h3>Información de la empresa</h3>
                                    <div class="row">
                                        <div class="mb-2 col-8">
                                            <h6 class="mb-0">Razón social</h6>
                                            <span name="nombreEmpresa" id="nombreEmpresa"></span>
                                        </div>
                                        <div class="mb-2 col-4">
                                            <h6 class="mb-0">NIF</h6>
                                            <span name="nifEmpresa" id="nifEmpresa"></span>
                                        </div>
                                        
                                        <div class="mb-2 col-8">
                                            <h6 class="mb-0">Descripción</h6>
                                            <span name="descripcionEmpresa" id="descripcionEmpresa"></span>
                                        </div>
                                        <div class="mb-2 col-4">
                                            <h6 class="mb-0">¿Autónoma?</h6>
                                            <input class="form-check-input" type="checkbox" id="autonomaEmpresa" name="autonomaEmpresa" disabled>
                                        </div>
                                        <div class="mb-2 col-6">
                                            <h6 class="mb-0">Número de trabajadores</h6>
                                            <span name="num_trabajadoresEmpresa" id="num_trabajadoresEmpresa"></span>
                                        </div>
                                        <div class="mb-2 col-6">
                                            <h6 class="mb-0">Año de fundación</h6>
                                            <span name="fundacionEmpresa" id="fundacionEmpresa"></span>
                                        </div>
                                        <div class="mb-2 col-12">
                                            <h6 class="mb-0">IBAN</h6>
                                            <span name="ibanEmpresa" id="ibanEmpresa"></span>
                                        </div>
                                        
                                        
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 p-0">   
                                    <img src="" alt="" id="fotoEmpresaSociaMod" class="rounded profilepic">
                                </div>
                            </div>

                            <div class="row border rounded mx-1 my-2 p-1">
                                <h3>Datos de contacto</h3>
                                <div class="mb-2 col-4">
                                    <h6 class="mb-0">Teléfono 1</h6>
                                    <span name="tlfEmpresa" id="tlfEmpresa"></span>
                                </div>
                                <div class="mb-2 col-4">
                                    <h6 class="mb-0">Teléfono 2</h6>
                                    <span name="tlf2Empresa" id="tlf2Empresa"></span>
                                </div>
                                <div class="mb-2 col-4">
                                    <h6 class="mb-0">Fax</h6>
                                    <span name="faxEmpresa" id="faxEmpresa"></span>
                                </div>
                                <div class="mb-2 col-6">
                                    <h6 class="mb-0">Correo electrónico</h6>
                                    <span name="emailEmpresa" id="emailEmpresa"></span>
                                </div>
                                <div class="mb-2 col-6">
                                    <h6 class="mb-0">Sitio web</h6>
                                    <span name="webEmpresa" id="webEmpresa"></span>
                                </div>
                                <div class="mb-2 col-8">
                                    <h6 class="mb-0">Dirección de facturación</h6>
                                    <span name="dirEmpresa" id="dirEmpresa"></span>
                                </div>
                            </div>

                            <div class="row border rounded mx-1 my-2 p-1">
                                <div class="mb-2 col-4">
                                    <h6 class="mb-0">Sectores relacionados</h6>
                                    <span name="sectores" id="sectores"></span>
                                </div>
                                <div class="mb-2 col-4">
                                    <h6 class="mb-0">Socias relacionadas</h6>
                                    <span name="sociasRela" id="sociasRela"></span>
                                </div>
                            </div>

                            <div class="row border rounded mx-1 my-2 p-1">
                                <div class="mb-2 col-12">
                                    <h3 class="mb-0">Notas</h3>
                                    <span id="notasEmpresa" name="notasEmpresa"></span>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-4 d-flex align-items-center mb-3" id="sigEmpresa">
                                    <span class="px-1">Siguiente Empresa</span>
                                    <i class="bi bi-arrow-right-square-fill" style="font-size: 200%; cursor: pointer;" id="clickPasar"></i>
                                </div>

                            </div>
                                
                                          
                        </div>
                    
                    </div>
                                            
                    </div>

                    <div class="modal-footer" id="footerModal">
                        <div class="d-flex justify-content-between w-100" id="conEmpresa">
                            <div>
                                <a class="btn btn-primary" id="editarSocia">Editar Socia</a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                            <div>
                                <a class="btn btn-primary" id="editarEmpresa">Editar Empresa</a>
                            </div>
                        </div>
                        <div id="sinEmpresa">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <a class="btn btn-primary" id="editarSociaSinEmp">Editar Socia</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--FIN MODAL VER PERFIL -->

    <!-- MODAL VER EMPRESA-->
    <div class="modal fade" id="verEmpresaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloEmpresaModal"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body" id="deshabilitarModalEmpresa">
                        
                        <div class="row" id="fotoEmpresa">
                            <div class="col-12 col-sm-3 mb-3">
                                <div class="col-12 mb-2">

                                    <img src="" class="img-thumbnail profilepic" alt="" id="fotoEmpresaModal">
                                </div>
                            </div>
                        </div>
                        
                        <h3>Información de la empresa</h3>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="nombreEmpresaModal" id="nombreEmpresaModal" class="form-control form-control-sm">
                                    <label for="nombreEmpresaModal">Razón social</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="nifEmpresaModal" id="nifEmpresaModal" class="form-control form-control-sm">
                                    <label for="nifEmpresaModal">NIF</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="number" min="0" name="fundacionEmpresaModal" id="fundacionEmpresaModal" class="form-control form-control-sm">
                                    <label for="fundacionEmpresaModal">Año de fundación</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="number" min="0" name="num_trabajadoresEmpresaModal" id="num_trabajadoresEmpresaModal" class="form-control form-control-sm">
                                    <label for="num_trabajadoresEmpresaModal">Núm. trabajadores</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="webEmpresaModal" id="webEmpresaModal" class="form-control form-control-sm">
                                    <label for="webEmpresaModal">Sitio web</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-sm-3 mb-3">
                                <div class="col-auto">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="autonomaEmpresaModal" name="autonomaEmpresaModal" disabled>
                                        <label class="form-check-label" for="autonomaEmpresaModal">Es autónoma</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Descripción</span>
                                    <textarea class="form-control" id="descripcionEmpresaModal" name="descripcionEmpresaModal" rows="3"
                                        aria-label="Descripción"></textarea>
                                </div>
                            </div>

                        </div>
                        
                        
                        <h3>Datos de contacto</h3>
                        <div class="row">
                            <div class="col-12 col-md-8 col-lg-6">
                                <div class="form-floating mb-3">
                                    <input type="text" name="dirEmpresaModal" id="dirEmpresaModal" class="form-control form-control-sm">
                                    <label for="dirEmpresaModal">Dirección postal</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-3 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="number" name="cpEmpresaModal" id="cpEmpresaModal" class="form-control form-control-sm">
                                    <label for="cpEmpresaModal">Código postal</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="text" name="poblacionEmpresaModal" id="poblacionEmpresaModal" class="form-control form-control-sm">
                                    <label for="poblacionEmpresaModal">Población</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="text" name="provinciaEmpresaModal" id="provinciaEmpresaModal" class="form-control form-control-sm">
                                    <label for="provinciaEmpresaModal">Provincia</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="text" name="paisEmpresaModal" id="paisEmpresaModal" class="form-control form-control-sm">
                                    <label for="paisEmpresaModal">País</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="email" name="emailEmpresaModal" id="emailEmpresaModal" class="form-control form-control-sm">
                                    <label for="emailEmpresaModal">Correo electrónico</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="text" name="tlfEmpresaModal" id="tlfEmpresaModal" class="form-control form-control-sm">
                                    <label for="tlfEmpresaModal">Teléfono 1</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="text" name="tlf2EmpresaModal" id="tlf2EmpresaModal" class="form-control form-control-sm">
                                    <label for="tlf2EmpresaModal">Teléfono 2</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-floating mb-3">
                                    <input type="text" name="faxEmpresaModal" id="faxEmpresaModal" class="form-control form-control-sm">
                                    <label for="faxEmpresaModal">Fax</label>
                                </div>
                            </div>
                        </div>
                        
                        <h3>Sectores y Socias relacionadas</h3>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="sectoresModal" name="sectoresModal" aria-label="Notas"></textarea>
                                    <label for="sectoresModal">Sectores</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="sociasRelaModal" name="sociasRelaModal" aria-label="Notas"></textarea>
                                    <label for="sociasRelaModal">Socias</label>
                                </div>
                            </div>
                        </div>
                            
                        <h3>Otros</h3>
                        <div class="row">
                            <div class="col-12 col-md-5 col-lg-4">
                                <div class="form-floating mb-3">
                                    <input type="text" name="ibanEmpresaModal" id="ibanEmpresaModal" class="form-control form-control-sm">
                                    <label for="ibanEmpresaModal">IBAN</label>
                                </div>
                            </div>

                            <div class="col-12 col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-text">Notas</span>
                                    <textarea class="form-control" id="notasEmpresaModal" name="notasEmpresaModal" aria-label="Notas"></textarea>
                                </div>
                            </div>
                        </div>                                     
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <a class="btn btn-primary" id="editarEmpresaModal">Editar Empresa</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--FIN MODAL VER EMPRESA -->
    
    <!-- MODAL ENVIAR CORREO-->
    <div class="modal fade" id="mandarCorreoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Enviar Email Socias</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control form-control-sm" name="asunto" id="asunto">
                                    <label for="asunto">Asunto</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control form-control-sm" id="emailBody" name="emailBody" rows="20" style="height: 100%; width: 100%;" ></textarea>
                                    <label for="emailBody">Cuerpo del Correo Electrónico</label>
                                </div>
                            </div>
                        </div>       
                        
                        <input type="hidden" name="sociasElegidas" id="miArrayInput">
                                            
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" class="btn btn-primary" value="Enviar Mensaje">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--FIN MODAL ENVIAR CORREO -->


</div>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>

<script>
    
    const bodySocia = document.getElementById("deshabilitarModalSocia");
    const elementosSocia = bodySocia.querySelectorAll("input, textarea");

    // Deshabilitar todos los elementos del bodySocia
    for (let i = 0; i < elementosSocia.length; i++) {
        elementosSocia[i].readOnly  = true;
    }

    function abrirModalSocia(socia, empresa) {
        var cod = socia.cod;
        var nif = socia.nif;
        var alta = socia.alta;
        var nombre = socia.nombre;
        var apellidos = socia.apellidos;
        var email = socia.email;
        var metodo_pago = socia.metodo_pago;
        var dir = socia.dir;
        var cp = socia.cp;
        var poblacion = socia.poblacion;
        var provincia = socia.provincia;
        var pais = socia.pais;
        var es_autonoma = socia.es_autonoma;
        var tlf = socia.tlf;
        var movil = socia.movil;
        var fax = socia.fax;
        var iban = socia.iban;
        var cuota = socia.cuota;
        var fact_nombre = socia.fact_nombre;
        var fact_nif = socia.fact_nif;
        var fact_dir = socia.fact_dir;
        var fact_cp = socia.fact_cp;
        var fact_poblacion = socia.fact_poblacion;
        var fact_provincia = socia.fact_provincia;
        var fact_pais = socia.fact_pais;
        var cuota_cuantia = socia.cuota_cuantia;
        var notas = socia.notas;
        var empresas = socia.empresas;
        var premios = socia.premios;
        
        document.getElementById("tituloPerfilModal").innerHTML = `Perfil de ${nombre} ${apellidos}`;
        document.getElementById("nombreModal").innerHTML = nombre + " " + apellidos;
        document.getElementById("dniModal").innerHTML = nif;
        document.getElementById("telefonoModal").innerHTML = tlf;
        document.getElementById("movilModal").innerHTML = movil;
        document.getElementById("faxModal").innerHTML = fax;
        document.getElementById("correoModal").innerHTML = email;

        document.getElementById("razonSocialModal").innerHTML = ` ${fact_nombre}<br>${fact_dir}<br>${fact_cp} ${fact_poblacion}<br>${fact_provincia}, ${fact_pais}  `;
        document.getElementById("nifModal").innerHTML = fact_nif;

        document.getElementById("cuotaModal").innerHTML = cuota_cuantia;
        document.getElementById("ibanModal").innerHTML = iban;
        document.getElementById("metPagoModal").innerHTML = metodo_pago;
        document.getElementById("referidaModal").innerHTML = nombre;
        document.getElementById("notasModal").innerHTML = notas;
        
               
        var editarSoc = document.getElementById("editarSocia");
        editarSoc.setAttribute("href", `${rutaURL}/socias/editar/${cod}?pag=${(paginaActiva+1)}`);
        var editarSocSinEmp = document.getElementById("editarSociaSinEmp");
        editarSocSinEmp.setAttribute("href", `${rutaURL}/socias/editar/${cod}?pag=${(paginaActiva+1)}`);
        
       

        const arraySocias =  <?php echo json_encode($datos['socias'])?>;
        arraySocias.forEach(soc => {
            if (soc.cod == socia.cod) {

                if (soc.profilepic == '<?php echo RUTA_URL ?>/img/socias/defaultprofilepic.png') {
                    document.getElementById("foto").style = "display: none";
                }else{
                    document.getElementById("foto").style = "display: block";
                    document.getElementById("fotoPerfilModal").src = soc.profilepic;
                    document.getElementById("fotoPerfilModal").alt = "Imagen de perfil de " + soc.nombre + " " + soc.apellidos;
                }
                

            }
        });

        
        
        // CODIGO DE LA EMPRESA
        
        if (empresa == undefined) {
            document.getElementById("parteSocia").className = "col-12";
            document.getElementById("parteEmpresa").style = "display: none";

            //footer del modal
            document.getElementById("conEmpresa").classList.add("d-none");
            document.getElementById("sinEmpresa").style = "display: block";

            return;
        }

        const arrayEmpresas = <?php echo json_encode($datos['empresas'])?>;
        var contador = 0;

        if (empresas.length <= 1) {
            document.getElementById("sigEmpresa").style.visibility = "hidden";
        } else {
            document.getElementById("sigEmpresa").style.visibility = "visible";
        }

        var boton = document.getElementById("clickPasar");

        function mostrarEmpresa(emp) {
            console.log(emp);
            var nif = emp.nif;
            var nombre = emp.nombre;
            var dir = emp.dir;
            var cp = emp.cp;
            var poblacion = emp.poblacion;
            var provincia = emp.provincia;
            var pais = emp.pais;
            var iban = emp.iban;
            var email = emp.email;
            var tlf = emp.telefono;
            var tlf2 = emp.telefono_2;
            var fax = emp.fax;
            var num_trabajadores = emp.num_trabajadores;
            var year_fundacion = emp.year_fundacion;
            var descripcion = emp.descripcion;
            var es_autonoma = emp.es_autonoma;
            var notas = emp.notas;
            var web = emp.sitio_web;
            
            document.getElementById("parteSocia").className = "col-12 col-lg-6";
            document.getElementById("parteEmpresa").className = "col-12 col-lg-6 border-lg-start";
            document.getElementById("parteEmpresa").style = "display: block";

            //footer del modal
            document.getElementById("conEmpresa").classList.remove("d-none");
            document.getElementById("sinEmpresa").style = "display: none";

            document.getElementById("nombreEmpresa").innerHTML = nombre;
            document.getElementById("nifEmpresa").innerHTML = nif;
            document.getElementById("fundacionEmpresa").innerHTML = year_fundacion;
            document.getElementById("num_trabajadoresEmpresa").innerHTML = num_trabajadores;
            document.getElementById("webEmpresa").innerHTML = web;
            
            if (es_autonoma==1) {
                document.getElementById("autonomaEmpresa").checked = true;
            }else{
                document.getElementById("autonomaEmpresa").checked = false;
            }
                            
            document.getElementById("descripcionEmpresa").innerHTML = descripcion;
            document.getElementById("dirEmpresa").innerHTML = `${dir}<br>${cp} ${poblacion}<br>${provincia}, ${pais}`;
            document.getElementById("tlfEmpresa").innerHTML = tlf;
            document.getElementById("tlf2Empresa").innerHTML = tlf2;
            document.getElementById("faxEmpresa").innerHTML = fax;
            document.getElementById("ibanEmpresa").innerHTML = iban;
            document.getElementById("notasEmpresa").innerHTML = notas;
            
            var editarEmp = document.getElementById("editarEmpresa");
            editarEmp.setAttribute("href", `${rutaURL}/empresas/editar/${nif}`);

            if (emp.logo == '<?php echo RUTA_URL ?>/img/empresas/defaultlogo.jpg' ) {
                document.getElementById("fotoEmpresaSociaMod").style = "display: none";
            }else{
                document.getElementById("fotoEmpresaSociaMod").style = "display: block";
                document.getElementById("fotoEmpresaSociaMod").src = emp.logo;
                document.getElementById("fotoEmpresaSociaMod").alt = "Imagen de la empresa " + nombre;
            }
            
            const arraySociasEmp =  <?php echo json_encode($datos['socias'])?>;            
            var sociasRelacionadas = [];
            arraySociasEmp.forEach(soc => {
                
                soc.empresas.forEach(ele => {
                    if (ele.cif == nif) { 
                        var sociaRel = soc.nombre + " " + soc.apellidos + " (" +  soc.nif + ")";
                        sociasRelacionadas.push(sociaRel);
                    }
                });
                
            });
            document.getElementById("sociasRela").innerHTML = sociasRelacionadas.join("<br>");


            const arraySectores =  <?php echo json_encode($datos['sectoresEmpresas'])?>;
            var sectores = [];
            arraySectores.forEach(sect => {
                if (sect.eps_empresa == nif) {
                    var sector = sect.eps_sector;
                    sectores.push(sector);
                }
            });
            document.getElementById("sectores").innerHTML = sectores.join("<br>");
            
        }

        // El codigo comentado funciona igual que el forEach de debajo

        // mostrarEmpresa(arrayEmpresas.find(emp => emp.nif == empresas[contador].cif));
        arrayEmpresas.forEach(emp => {
            
            if (emp.nif ==  empresas[contador].cif) { 
                mostrarEmpresa(emp);
            }
        
        });

        boton.addEventListener("click", function() {

            if (contador == (empresas.length-1)) {
                contador = 0;
            }else{
                contador += 1;
            }
                
            // El codigo comentado funciona igual que el forEach de debajo

            // mostrarEmpresa(arrayEmpresas.find(emp => emp.nif == empresas[contador].cif));
            arrayEmpresas.forEach(emp => {
                    
                if (emp.nif ==  empresas[contador].cif) { 
                    
                    mostrarEmpresa(emp);
                }
            
            });
        });

    }


    function abrirModalEmpresa(empresa) {

        const arraySociasEmp =  <?php echo json_encode($datos['socias'])?>;            
        var sociasRelacionadas = [];
        arraySociasEmp.forEach(soc => {
            
            soc.empresas.forEach(ele => {
                if (ele.cif == empresa.cif) { 
                   
                    var sociaRel = soc.nombre + " " + soc.apellidos + " (" +  soc.nif + ")";
                    
                    sociasRelacionadas.push(sociaRel);
    
                }
            });
            
        });
        document.getElementById("sociasRelaModal").value = sociasRelacionadas.join("\n");
        

        // const arraySectores =  <?php echo json_encode($datos['sectoresEmpresas'])?>;
        // console.log(arraySectores);
        // arraySectores.forEach(sect => {
        //     if (sect.eps_empresa == empresa.cif) {
        //         var sector = sect.eps_sector;

        //         document.getElementById("sectoresModal").value = sector;
        //     }
        // });

        const arraySectores =  <?php echo json_encode($datos['sectoresEmpresas'])?>;
        var sectores = [];
        //console.log(sectores);
        arraySectores.forEach(sect => {
            if (sect.eps_empresa == empresa.cif) {
                var sector = sect.eps_sector;
                sectores.push(sector);
            }
        });
        document.getElementById("sectoresModal").innerHTML = sectores.join("\n");;


        const arrayEmpresas =  <?php echo json_encode($datos['empresas'])?>;
        arrayEmpresas.forEach(emp => {
            if (emp.nif == empresa.cif) {
                
                var nif = emp.nif;
                var nombre = emp.nombre;
                var dir = emp.dir;
                var cp = emp.cp;
                var poblacion = emp.poblacion;
                var provincia = emp.provincia;
                var pais = emp.pais;
                var iban = emp.iban;
                var email = emp.email;
                var tlf = emp.telefono;
                var tlf2 = emp.telefono_2;
                var fax = emp.fax;
                var num_trabajadores = emp.num_trabajadores;
                var year_fundacion = emp.year_fundacion;
                var descripcion = emp.descripcion;
                var es_autonoma = emp.es_autonoma;
                var notas = emp.notas;
                var web = emp.sitio_web;

                document.getElementById("tituloEmpresaModal").innerHTML = "Perfil de empresa: " + nombre;
                document.getElementById("nombreEmpresaModal").value = nombre;
                document.getElementById("nifEmpresaModal").value = nif;
                document.getElementById("fundacionEmpresaModal").value = year_fundacion;
                document.getElementById("num_trabajadoresEmpresaModal").value = num_trabajadores;
                document.getElementById("webEmpresaModal").value = web;
               
                if (es_autonoma==1) {
                    document.getElementById("autonomaEmpresaModal").checked = true;
                }else{
                    document.getElementById("autonomaEmpresaModal").checked = false;
                }
                                
                document.getElementById("descripcionEmpresaModal").value = fax;
                document.getElementById("dirEmpresaModal").value = dir;




                document.getElementById("cpEmpresaModal").value = cp;
                document.getElementById("poblacionEmpresaModal").value = poblacion;
                document.getElementById("provinciaEmpresaModal").value = provincia;
                document.getElementById("paisEmpresaModal").value = pais;
                document.getElementById("emailEmpresaModal").value = email;
                document.getElementById("tlfEmpresaModal").value = tlf;
                document.getElementById("tlf2EmpresaModal").value = tlf2;
                document.getElementById("faxEmpresaModal").value = fax;
                document.getElementById("ibanEmpresaModal").value = iban;
                document.getElementById("notasEmpresaModal").value = notas;


                var editarEmpModal = document.getElementById("editarEmpresaModal");
                editarEmpModal.setAttribute("href", `${rutaURL}/empresas/editar/${nif}`);


                if (emp.logo == '<?php echo RUTA_URL ?>/img/empresas/defaultlogo.jpg') {
                    document.getElementById("fotoEmpresa").style = "display: none";
                }else{
                    document.getElementById("fotoEmpresa").style = "display: block";
                    document.getElementById("fotoEmpresaModal").src = emp.logo;
                    document.getElementById("fotoEmpresaModal").alt = "Imagen de la empresa " + nombre;
                }
            }
        });
        
    }

    filtroActivo = 11;
    filtrarSocias();
</script>