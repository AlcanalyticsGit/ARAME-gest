<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>

<section class="vh-100">

  <div class="container py-5 h-100">
    <!-- MENSAJE DE ERROR -->
    <?php if (isset($datos['error'])) : ?>
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
              <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path
                  d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
              </symbol>
            </svg>
            <div class="alert alert-warning d-flex align-items-center" role="alert">
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:">
                <use xlink:href="#exclamation-triangle-fill" /></svg>
              <div>
                <?php echo $datos['error'] ?>
              </div>
            </div>
            <?php endif ?>
            <!-- FIN MENSAJE ERROR -->
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-5 col-xl-4">
        <div class="card bg-principal text-light shadow-lg bg-principal-gradient-login" style="border-radius: 2rem;">
          <div class="card-body p-5 text-center">

            <div class="col-12 d-flex pb-3">
              <a href="<?php echo RUTA_URL.'/login' ?>" class="nav-link"><span><i class="bi bi-arrow-left-circle pe-1"></i>Volver</span></a>
            </div>

            <form method="POST" class="">
             
              <!-- <h3 class="mb-2 text-light">Acceso</h3> -->
              <div class="text-dark">
                <div class="text-light mb-4 text-start">
                  <h4>Restablecer contraseña</h4>
                  <p>Introduce tu dirección de correo electrónico y haz click en "Enviar" para recibir una contraseña nueva.</p>
                </div>
                <div class="form-floating mb-3 d-flex">
                  <input type="email" class="form-control" name="email" id="email"
                    placeholder="Nombre de usuario">
                  <label for="floatingInput">Correo electrónico</label>
                </div>
                <div>
                  <button class="btn btn-outline-light btn-lg px-5 boton-login" type="submit">Enviar</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>