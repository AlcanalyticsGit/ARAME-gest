<?php
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="ARAME - Registro de actividad.txt"');

$file = fopen('php://output', 'w');


foreach ($datos as $log) {
    fwrite($file, "{$log->timestamp} - {$log->usuario}: {$log->descripcion}\n");
}
fclose($file);
?>