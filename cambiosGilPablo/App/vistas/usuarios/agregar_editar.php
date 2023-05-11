<?php require_once RUTA_APP.'/vistas/inc/cabecera.php' ?>
<?php require_once RUTA_APP.'/vistas/usuarios/script_inicio_usuarios.php' ?>

<?php
    isset($datos['usuario']->username  ) ? $accion = "Modificar" : $accion = "Crear";
?>

<form method="POST" class="container-fluid p-0 ps-3" enctype="multipart/form-data">

    <div class="row mb-4">
        <h4>Datos de usuario</h4>
        <div class="row">

            <div class="col-12 col-md-8">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <div class="form-floating mb-3">
                            <input type="email" name="username" id="username" class="form-control form-control-sm"
                                value="<?php echo isset($datos['usuario']) ? $datos['usuario']->username : '' ; ?>"
                                placeholder="Correo electrónico" required>
                            <label for="username">Correo electrónico</label>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="form-floating mb-3">
                            <input type="password" name="pass" id="pass" class="form-control form-control-sm"
                                placeholder="Contraseña">
                            <label for="pass">Contraseña</label>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <div class="form-floating mb-3">
                            <input type="text" name="nombre" id="nombre" class="form-control form-control-sm"
                                value="<?php echo isset($datos['usuario']) ? $datos['usuario']->nombre : '' ; ?>"
                                placeholder="Nombre" required>
                            <label for="nombre">Nombre</label>
                        </div>
                    </div>

                    <?php if ($this->datos['usuarioSesion']->rol<=20) : ?>
                        <div class="col-12 col-lg-3">
                            <div class="form-floating mb-3">
                                <select class="form-select" id="rol" name="rol" aria-label="Rol de usuario" onchange="mostrarSociaVinculada()">
                                    <option class="d-none" selected>
                                        Seleccionar Rol</option>
                                    <?php foreach ($datos['roles'] as $rol) : ?>
                                        <?php if ($rol->nivel>=$this->datos['usuarioSesion']->rol) : ?>
                                        <option value="<?php echo $rol->nivel ?>" <?php echo (isset($datos['usuario']) && $datos['usuario']->rol==$rol->nivel) ? 'selected' : '' ; ?>><?php echo $rol->nombre ?>
                                        </option>
                                        <?php endif; ?>
                                    <?php endforeach ?>
                                </select>
                                <label for="rol">Rol de usuario</label>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <?php if ($this->datos['usuarioSesion']->rol<=20) : ?>
                    <div class="row">
                        <div class="col-12 col-xs-7 col-sm-8 col-md-9 mb-3 d-none" id="sociaVinculada">
                            <div class="form-floating">
                                <input class="form-control" list="datalistOptions" id="socia" name="socia"
                                    placeholder="Socia vinculada"
                                    value="<?php echo (isset($datos['usuario'])) ? $datos['usuario']->socia : '' ; ?>">
                                <datalist id="datalistOptions">
                                    <?php foreach ($datos['socias'] as $socia) : ?>
                                    <option value="<?php echo $socia->cod ?>">
                                        <?php echo $socia->nombre.' '.$socia->apellidos ?>
                                    </option>
                                    <?php endforeach ?>
                                </datalist>
                                <label for="socia" class="form-label">Socia vinculada</label>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>

    <div class="row pt-2 d-flex justify-content-center mb-4">
        <div class="w-auto">
            <input type="submit" class="btn btn-primary"
                value="<?php echo ($accion=='Crear' ? "Registrar usuario" : "Guardar cambios")?>">
        </div>
    </div>

</form>

<script>
    mostrarSociaVinculada();
</script>

<?php require_once RUTA_APP.'/vistas/inc/pie.php' ?>