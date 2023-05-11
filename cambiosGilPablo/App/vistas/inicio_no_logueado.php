<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="img/favicon/favicon.jpg">
  <title><?php echo NOMBRE_SITIO ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?php echo RUTA_URL ?>/css/styles.css">
  <script src="js/main.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php require_once RUTA_APP.'/vistas/inc/header_no_logueado.php' ?>


  <div class="container">
    <div class="col-12">
      <h1>ImpactGest</h1>
      <p>Versión 0.1</p>
    </div>
    
    <p>Laboratorio de Impacto</p>

    <?php print_r($this->datos); ?>
  </div>

  <?php  require_once RUTA_APP.'/vistas/inc/footer.php' ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
  </script>
  <script src="<?php echo RUTA_URL?>/js/main.js"></script>
</body>

</html>