<header class="sticky-top shadow-sm bg-light">

    <nav class="navbar navbar-expand-md navbar-dark bg-principal-gradient">
        <div class="container-fluid">
            <a href="<?php echo RUTA_URL?>" class="navbar-brand d-flex"><img src="<?php echo RUTA_URL?>/img/logos/ARAME_blanco.png" class="logo-cabecera" alt="Logo ARAME"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <?php if(isset($datos['menuActivo'])): ?>
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <?php if(tienePrivilegios($datos['usuarioSesion']->rol,[10,20,30])):?>
                        <li class="nav-item">
                            <a class="nav-link <?php if($datos['menuActivo'] == 1 ): ?> text-uppercase active<?php endif ?>"
                                aria-current="page" href="<?php echo RUTA_URL ?>/socias">
                            <?php echo $datos['usuarioSesion']->rol == 30 ? 'Mi ficha de socia' : 'Socias' ?>
                            </a>
                        </li>
                    <?php endif ?>

                    <?php if(tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
                        <li class="nav-item">
                            <a class="nav-link<?php if($datos['menuActivo'] == 2 ): ?> text-uppercase active<?php endif ?>"
                                aria-current="page" href="<?php echo RUTA_URL ?>/empresas">Empresas</a>
                        </li>
                    <?php endif ?>

                    <?php if(tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
                        <li class="nav-item">
                            <a class="nav-link<?php if($datos['menuActivo'] == 3 ): ?> text-uppercase active<?php endif ?>"
                                aria-current="page" href="<?php echo RUTA_URL ?>/recibos">Recibos</a>
                        </li>
                    <?php endif ?>

                    <?php if(tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
                        <li class="nav-item">
                            <a class="nav-link<?php if($datos['menuActivo'] == 4 ): ?> text-uppercase active<?php endif ?>"
                                aria-current="page" href="<?php echo RUTA_URL ?>/premios">Premios</a>
                        </li>
                    <?php endif ?>

                    <?php if(tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
                        <li class="nav-item">
                            <a class="nav-link<?php if($datos['menuActivo'] == 5 ): ?> text-uppercase active<?php endif ?>"
                                aria-current="page" href="<?php echo RUTA_URL ?>/usuarios">Usuarios</a>
                        </li>
                    <?php endif ?>
                   
                </ul>
                <?php endif ?>
                <?php if ($this->datos['usuarioSesion']->rol==10) : ?>
                    <?php if(isset($_POST['debug_toggle'])) {
                        if (isset($_SESSION['modo_depuracion']) && $_SESSION['modo_depuracion']) {
                            $_SESSION['modo_depuracion'] = false;
                        } else {
                            $_SESSION['modo_depuracion'] = true;
                        }
                    } ?>

                    <form method="POST" class="col-auto pe-3">
                        <div class="form-check form-switch">
                            <input class="btn btn-warning" type="submit" id="debug_toggle" name="debug_toggle" title="Activa o desactiva el modo depuración" value="<?php echo isset($_SESSION['modo_depuracion']) && $_SESSION['modo_depuracion'] ? 'Desactivar ' : 'Activar '?>Modo Depuración">
                        </div>
                    </form>
                <?php endif; ?>

                <?php if(isset($this->datos['usuarioSesion']) && $this->datos['usuarioSesion']!=""):?>
                <span class="navbar-text">
                <?php if (isset($datos['usuarioSesion']->username)) echo "Sesión iniciada como " ?>
                </span>    
                <div class="btn-group">
                <button class="btn btn-none text-light dropdown-toggle border-0 p-2" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                <?php echo mb_strtoupper($datos['usuarioSesion']->nombre) ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-lg-end" aria-labelledby="defaultDropdown">
                    <li><a class="dropdown-item" href="<?php echo RUTA_URL.'/usuarios/editar/'.$datos['usuarioSesion']->username ?>">Mis datos de usuario</a></li>
                    <?php if($this->datos['usuarioSesion']->rol==10) : ?>
                        <li><a class="dropdown-item" href="<?php echo RUTA_URL."/admin/descargarLogs/" ?>">Descargar registro de actividad</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="<?php echo RUTA_URL ?>/login/logout">Cerrar sesión</a></li>
                </ul>
                </div>








                <?php endif ?>

            </div>
        </div>
    </nav>

</header>