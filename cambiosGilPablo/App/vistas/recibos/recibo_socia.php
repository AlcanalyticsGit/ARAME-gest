<?php

    $recibo = $datos;
    $year = $recibo->year;
    $semestre = $recibo->semestre;
    $concepto = $recibo->concepto;
    $recibo_cod = sprintf('%03d', $recibo->cod);
    $recibo_fecha = $recibo->fecha;
    $recibo_cuantia = $recibo->cuantia;
    $recibo_metodo_pago = $recibo->metodo_pago;
    $recibo_iban = $recibo->iban;
    $recibo_direccion = $recibo->direccion;
    $recibo_cp = $recibo->cp;
    $recibo_poblacion = $recibo->poblacion;
    $recibo_provincia = $recibo->provincia;
    $recibo_cod_completo = $semestre."-".$year."-".$recibo_cod;
    $ruta_logo_arame = RUTA_APP.'/ficheros/images/arame_logotipo.png';

    // die(print_r($datos));

    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Recibo '.str_replace("/", "", $recibo_cod_completo).' ARAME '.$datos->nombre.'</title>
        <link rel="stylesheet" href="'.RUTA_URL.'/css/style_recibos_arame_noflex.css">
    </head>';

    $html .= '
    <body class="recibo">
        <header class="row1">
            <table>
                <tbody>
                    <tr>
                        <td class="logo-arame">
                            <img src="'.RUTA_URL.'/img/logos/ARAME LOGOTIPO 2018-v1 - PNG.png"
                                alt="Logo ARAME" class="logo">
                        </td>
                        <td class="info-arame">
                            <p class="info-arame-destacado">ARAME</p>
                            <p>Paseo Independencia 34, Entlo izda.</p>
                            <p>50004 Zaragoza</p>
                            <p class="info-arame-destacado">CIF: G50347558</p>
                            <p>secretaria@arame.org</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>
        <div class="row2">
            <table>
                <tbody>
                    <tr>
                        <td class="div-hr">
                            <hr>
                        </td>
                        <td class="num-recibo">
                            <span>Recibo '.str_replace("-", "/", $recibo_cod_completo).'</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row3">
            <table>
                <tbody>
                    <tr>
                        <td class="info-socia" rowspan="2">
                            <p>'.$datos->nombre'</p>
                            <p>'.$datos->nif.'</p>
                            <p>'.$recibo_direccion.'</p>
                            <p>'.$recibo_cp." ".$recibo_poblacion.'</p>
                            <p>'.$recibo_provincia.'</p>
                        </td>
                        <td class="td-fecha" rowspan="1">
                            <table class="table-fecha">
                            <tr>
                                <th class="th-fecha">
                                    <span>Fecha</span>
                                </th>
                            </tr>
                            <tr>
                                <td class="td-fecha td-td-fecha">
                                    '.$recibo_fecha.'
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row4">
            <table>
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="concepto">'.$concepto.'</td>
                        <td class="cuantia">'.$recibo_cuantia.'€</td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="row5">
            <table  class="tabla detalles-pago">
                <tbody>
                    <tr>
                        <th class="recuadro detalles-pago th-detalles-pago">
                            <p class="titulo-detalles-pago">Detalles de pago</p>
                        </th>
                    </tr>
                    <tr>
                        <td class="recuadro detalles-pago td-detalles-pago">
                            <p class="modo-pago">'.$recibo_metodo_pago.'</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>';
    $html .= '</html>';

    $ruta = RUTA_APP."/ficheros/output/";
    $nombre_fichero = "Factura ".$recibo_cod_completo." ARAME - ".$datos->nombre;
    
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => '20',
        'margin_right' => '20',
        'margin_top' => '20',
        'margin_bottom' => '20'
        ]);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle($nombre_fichero);
    $mpdf->DefHTMLFooterByName(
        'FooterARAME',
        '<footer>
        <p>ARAME NIF G50347558. Inscrita en la Oficina Pública de Depósito de Estatutos de Asociaciones Empresariales y Sindicales Gobierno de Aragón, nº 72/11</p>
    </footer>'
      );
      $mpdf->SetHTMLFooterByName('FooterARAME');

    $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::DEFAULT_MODE); 

    // El parámetro F indica que debe guardarse como fichero en la ruta especificada, D significa descargar, I ver en el navegador, S enviar por correo:
        if(isset($datos['descarga'])) {
            $param = 'D';
        } else {
            $param = 'I';
        }
    $mpdf->Output($nombre_fichero.'.pdf', $param);

?>

<?php
// echo $html
?>

<!-- BACKUP HTML -->
<!-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>Recibo <?php echo str_replace("/", "", $recibo_cod_completo) ?> ARAME <?php echo $datos->nombre ?></title>
    <link rel="stylesheet" href="<?php echo RUTA_URL ?>/css/style_recibos_arame_noflex.css">
</head>

<body>
    <div class="recibo">
        <div class="container">
            <header class="row1">
                <div class="logo-arame">
                    <img src="<?php echo RUTA_URL ?>/img/logos/ARAME LOGOTIPO 2018-v1 - PNG.png" alt="Logo ARAME" class="logo">
                </div>
                <div class="info-arame">
                    <p class="info-arame-destacado">ARAME</p>
                    <p>Paseo Independencia 34, Entlo izda.</p>
                    <p>50004 Zaragoza</p>
                    <p class="info-arame-destacado">CIF: G50347558</p>
                    <p>secretaria@arame.org</p>
                </div>
            </header>

            <div class="row2">
            
                <div class="num-recibo">Recibo <?php echo $recibo_cod_completo ?></div>
                <hr class="div-hr">
            </div>

            <div class="row3">
                <div class="recuadro info-socia">
                        <div><?php echo $socia->nombre." ".$socia->apellidos ?></div>
                        <div><?php echo $socia->nif ?></div>
                        <div><?php echo $recibo_direccion ?></div>
                        <div><?php echo $recibo_cp." ".$recibo_poblacion ?></div>
                        <div><?php echo $recibo_provincia ?></div>
                </div>
                <div class="recuadro fecha"><span>Fecha: </span><?php echo $recibo_fecha ?></div>
            </div>

            <div class="row4">
                <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="concepto sep"><?php echo $concepto ?></td>
                            <td><?php echo $recibo_cuantia ?>€</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>

            <div class="row5">
                <div class="recuadro detalles-pago">
                    <p class="titulo-detalles-pago">Detalles de pago</p>
                    <p class="modo-pago"><?php echo $recibo_metodo_pago ?></p>
                </div>
            </div>

            <footer>
                <p>ARAME. CIF G50347558</p>
                <p>Inscrita en la Oficina Pública de Depósito de Estatutos de Asociaciones Empresariales y Sindicales
                    Gobierno de Aragón, nº 72/11</p>
            </footer>
        </div>
    </div>
</body>

</html> -->