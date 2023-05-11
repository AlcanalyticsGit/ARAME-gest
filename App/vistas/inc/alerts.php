<?php
    switch (isset($_GET['res']) ? $_GET['res'] : '') {
        case 'error':
            $msg = '<strong>¡Vaya!</strong> Ha ocurrido un error al realizar la operación';
            $alert = 'danger';
            break;
        
        case 'success':
            $msg = '<strong>¡Bien!</strong> La operación se ha realizado con éxito';
            $alert = 'success';
            break;
        
        case 'recuperacion':
            $msg = '<strong>¡Éxito!</strong> Se ha enviado una nueva contraseña a tu dirección de correo electrónico.';
            $alert = 'success';
            break;
        
        case 'loginerror':
            $msg = '<strong>Acceso denegado</strong>. El usuario o la contraseña introducidos no son correctos. Por favor, comprueba los datos y vuelve a intentarlo.';
            $alert = 'danger';
            break;

        case 'unauthorized':
            $msg = '<strong>Acceso denegado</strong>. El usuario actual no tiene permiso para realizar la operación.';
            $alert = 'danger';
            break;
        
        default:
            $msg = '';
            $alert = '';
            break;
    }
?>

<!-- Alert -->
<?php if($msg!=''): ?>
    <div class="fixed-top mt-5 pt-5 d-flex justify-content-center">
        <div class="<?php echo "alert alert-{$alert} alert-dismissible fade show shadow-lg col-12 col-md-10 col-lg-8" ?>" role="alert">
            <?php echo $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php endif; ?>

<!-- jQuery para que el alert desaparezca automáticamente -->
<script type="text/javascript">
    $(document).ready(function () {
    window.setTimeout(function() {
        $(".alert").fadeTo(1000, 0).slideUp(1000, function(){
            $(this).remove(); 
        });
    }, 5000);
    });
</script>