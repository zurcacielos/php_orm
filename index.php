<?php
// TODO: como no hay base de datos, la funcion buscar objetos por pk numerica, devuelve positivo si la pk es par, y nulo si no.
// TODO: Con eso simulamos que si se busca por un pk par, el objeto existe.
// TODO: la tabla pagos y usuariospagos podrian unificarse pero no esta en el enunciado
ob_start();
require_once 'testsUnitarios.php';


// recorremos todos los metodos a testear de la clase testUnitarios
$tests = new testsUnitarios();
$metodos = get_class_methods($tests);

foreach ($metodos as $metodo) {
    if (substr($metodo,0,6)=='prueba') {
        echo "<h4>" . substr($metodo,7) . "</h4>";
        try {
            $tests->$metodo();
        } catch (\Exception $e) {
            echo "<span style='color:red'>" .$e->getMessage() . '</span>';
        }
    }
}

$content = ob_get_clean();
$pagina = 'index';
require('layout.php');



