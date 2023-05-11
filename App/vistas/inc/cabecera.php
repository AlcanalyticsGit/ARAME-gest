<?php limpiarCache();
date_default_timezone_set('Europe/Madrid');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="<?php echo RUTA_URL ?>/img/favicon/favicon.ico">
    <title><?php echo NOMBRE_SITIO ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo RUTA_URL ?>/css/styles.css">
    <?php if (isset($_SESSION['modo_depuracion']) && $_SESSION['modo_depuracion']) : ?>
        <link rel="stylesheet" href="<?php echo RUTA_URL ?>/css/styles_modo_depuracion.css">
    <?php endif; ?>

    <script src="<?php echo RUTA_URL ?>/scripts/main.js"></script>

    <?php if(isset($this->datos['controlador'])): ?>
    <script src="<?php echo RUTA_URL ?>/scripts/<?php echo $this->datos['controlador'] ?>.js"></script>
    <?php endif ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>


</head>

<body class="d-flex flex-column min-vh-100">
    <div class="fondo"></div>
    <div class="superposicion-carga d-flex aligns-items-center justify-content-center" id="superposicion-carga"
        style="display:none !important;">
        <div class="loader"></div>
    </div>

    <?php if (!isset($datos['login'])) :?>
    <header>
        <?php require_once RUTA_APP.'/vistas/inc/header.php' ?>
    </header>

    <div class="container-fluid px-5 d-flex pt-4 pb-2">
        <?php if($datos['usuarioSesion']->rol < 30) : ?>
        <?php // if(isset($this->datos['usuarioSesion']->username)): ?>
            
        <nav class="px-3" aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
            <ol class="breadcrumb">
                <!-- NIVEL 1 -->
                <li class="breadcrumb-item d-inline-flex">

                    <a href="<?php echo RUTA_URL ?>" class="nav-link nav-link-primary text-uppercase"><i
                            class="bi bi-house-fill"> </i>Inicio</a>
                </li>

                <!-- NIVEL 2 -->
                <?php if(isset($this->datos['controlador'])): ?>
                <li class="breadcrumb-item d-inline-flex">
                    <div>
                        <a href="<?php echo RUTA_URL.'/'.$this->datos['controlador'] ?>"
                            class="nav-link nav-link-primary text-uppercase"><?php echo $this->datos['controlador'] ?></a>
                    </div>
                </li>
                <?php endif ?>

                <!-- NIVEL 3 -->
                <!-- Socias -->
                <?php if($this->datos['menuActivo']==1): ?>
                    <?php if(isset($this->datos['socia']) && !isset($this->datos['socia']->cod)): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="#"
                                    class="nav-link nav-link-primary text-uppercase">Nueva socia</a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php if(isset($this->datos['socia']->cod)): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="<?php echo RUTA_URL.'/socias/editar/'.$this->datos['socia']->cod ?>"
                                    class="nav-link nav-link-primary text-uppercase"><?php echo $this->datos['socia']->nombre.' '.$this->datos['socia']->apellidos ?></a>
                            </strong></div>
                    </li>
                    <?php endif ?>
                <?php endif; ?>

                <!-- Empresas -->
                <?php if($this->datos['menuActivo']==2): ?>
                    <?php if(isset($this->datos['empresa']->nif)): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="<?php echo RUTA_URL.'/empresas/editar/'.$this->datos['empresa']->nif ?>"
                                    class="nav-link nav-link-primary text-uppercase"><?php echo $this->datos['empresa']->nombre?></a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php if(isset($this->datos['empresa']) && !isset($this->datos['empresa']->nif)): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="#"
                                    class="nav-link nav-link-primary text-uppercase">Nueva empresa</a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                <?php endif; ?>

                <!-- Recibos -->
                <?php if($this->datos['menuActivo']==3): ?>
                    <?php if(isset($this->datos['reciboCompleto'])): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="<?php echo RUTA_URL.'/recibo/editar/'.$this->datos['reciboCompleto']['recibo']->year."/".$this->datos['reciboCompleto']['recibo']->semestre."/".$this->datos['reciboCompleto']['recibo']->cod ?>"
                                    class="nav-link nav-link-primary text-uppercase"><?php echo $this->datos['reciboCompleto']['recibo']->year."/".$this->datos['reciboCompleto']['recibo']->semestre."/".$this->datos['reciboCompleto']['recibo']->cod." ".$this->datos['reciboCompleto']['socia']->nombre." ".$this->datos['reciboCompleto']['socia']->apellidos ?></a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                <?php endif; ?>
                
                <!-- Premios -->
                <?php if($this->datos['menuActivo']==4): ?>
                    <?php if(isset($this->datos['premio']->year)): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="<?php echo RUTA_URL.'/premios/editar/'.$this->datos['premio']->year."/".$this->datos['premio']->socia_cod ?>"
                                    class="nav-link nav-link-primary text-uppercase"><?php echo $this->datos['premio']->year." - ".$this->datos['premio']->socia_nombre." ". $this->datos['premio']->socia_apellidos ?></a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php if(isset($this->datos['premio']) && !isset($this->datos['premio']->year)): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="#"
                                    class="nav-link nav-link-primary text-uppercase">Nuevo premio</a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                <?php endif; ?>

                <!-- Usuarios -->
                <?php if($this->datos['menuActivo']==5): ?>
                    <?php if(isset($this->datos['usuario'])): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="<?php echo RUTA_URL.'/usuarios/editar/'.$this->datos['usuario']->username ?>"
                                    class="nav-link nav-link-primary text-uppercase"><?php echo $this->datos['usuario']->nombre ?></a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php if(!isset($this->datos['usuario']) && isset($this->datos['socias'])): ?>
                    <li class="breadcrumb-item d-inline-flex">
                        <div><strong>
                                <a href="#"
                                    class="nav-link nav-link-primary text-uppercase">Nuevo usuario</a>
                            </strong>
                        </div>
                    </li>
                    <?php endif ?>
                    <?php endif; ?>
                    
                </ol>
            </nav>
            <?php // endif ?>
            
        </div>
        <?php endif; ?>
        <?php endif ?>
    </div>
    <main class="container-fluid px-5">

    <div class="row align-items-center" id="divAlerts">
        <?php require_once RUTA_APP.'/vistas/inc/alerts.php' ?>
    </div>

        <!-- FIN CABECERA -->
        <!-- INICIO CONTENIDO -->