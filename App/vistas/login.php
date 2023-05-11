<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>

<section class="vh-100">

  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-5 col-xl-4">

        <div class="card bg-principal text-light shadow-lg bg-principal-gradient-login" style="border-radius: 2rem;">
          <div class="card-body p-5 text-center">

            <form method="POST" class="pb-md-5 pt-md-4">
              <div class="row d-flex justify-content-center mb-5">
                <div class="col-12 col-sm-10 col-md-9 col-lg-8 col-xl-8 px-0">
                  <img src="<?php echo RUTA_URL?>/img/logos/ARAME_blanco.png" class="img-fluid" alt="Logo ARAME">
                </div>
              </div>
              <!-- <h3 class="mb-2 text-light">Acceso</h3> -->
              <div class="text-dark">
                <div class="form-floating mb-3">
                  <input type="email" class="form-control" name="loginUser" id="loginUser"
                    placeholder="Correo electrónico" autocomplete="on">
                  <label for="floatingInput">Correo electrónico</label>
                </div>
                <div class="form-floating mb-4">
                  <input type="password" class="form-control" name="loginPass" id="loginPass" placeholder="Contraseña">
                  <label for="floatingPassword">Contraseña</label>
                </div>
                <div>
                  <button class="btn btn-outline-light btn-lg px-5 boton-login" type="submit">Entrar</button>
                </div>
              </div>
            </form>
            <div class="text-end">
              <a class="nav-link" href="<?php echo RUTA_URL.'/login/recuperacion' ?>">¿Has olvidado tu contraseña?</a>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>