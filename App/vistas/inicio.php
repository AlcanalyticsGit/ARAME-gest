<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>

<div class="row">
  
  <?php 
 
  if (tienePrivilegios($datos['usuarioSesion']->rol,[10,20])):?>
  <div class="g-3">

    <div class="d-inline-flex align-items-baseline p-0 m-0">
      <i class="h5 bi bi-gear-fill me-2 position-relative"></i>
      <h4 class="d-inline-block">Gestión</h4>
    </div>

    <div class="row g-3">

      <?php if (tienePrivilegios($datos['usuarioSesion']->rol,[10])):?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
        <a href="<?php echo RUTA_URL ?>/socias/"
          class="btn btn-lg btn-outline-primary w-100 h-100 d-flex justify-content-center align-items-center">
          <i class="bi bi-people-fill pe-2"></i>
          <div class="w-100">
            Socias
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
        <a href="<?php echo RUTA_URL ?>/empresas/"
          class="btn btn-lg btn-outline-primary w-100 h-100 d-flex justify-content-center align-items-center">
          <i class="bi bi-people-fill pe-2"></i>
          <div class="w-100">
            Empresas
          </div>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
        <a href="<?php echo RUTA_URL ?>/recibos/"
          class="btn btn-lg btn-outline-primary w-100 h-100 d-flex justify-content-center align-items-center">
          <i class="bi bi-people-fill pe-2"></i>
          <div class="w-100">
            Recibos
          </div>
        </a>
      </div>
      <?php endif ?>

  </div>

  <?php endif ?>



</div>

</div>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>