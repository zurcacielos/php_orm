<?php
require_once 'clases\ConsultaBuilder.php';
require_once 'clases\Modelo.php';
require_once 'clases\Usuario.php';
require_once 'clases\Favorito.php';
require_once 'clases\UsuarioPago.php';
require_once 'clases\Pago.php';

use GeoPagos\Database\Usuario;
use GeoPagos\Database\Favorito;

// este archivo ejecuta diferentes pruebas mostrando si ocurrio una excepcion
// TODO: como no hay base de datos, la funcion buscar objetos por pk numerica, devuelve positivo si la pk es par, y nulo si no.
// TODO: la tabla pagos y usuariospagos podrian unificarse pero no esta en el enunciado

/**
 * Class testsUnitarios
 * Los metodos que empiecen con prueba van a ser ejecutados
 */
class testsUnitarios
{
    /**
     * @var Usuario
     */
    private $usuario;

    private function crearUsuario()
    {
        $this->usuario = new Usuario();
        $this->usuario->usuario = 'Fabian Sosa';
        $this->usuario->edad = 18;
        $this->usuario->clave = 'secreto';
        $this->usuario->guardar(); // genera el codigousuario
        return $this->usuario;
    }

    public function __construct()
    {
        $this->crearUsuario();
    }

    public function prueba_Edad_incorrecta()
    {
        $usuario = new Usuario();
        $usuario->usuario = 'Fabian Sosa';
        $usuario->edad = 17;
        $usuario->clave = 'secreto';
        $usuario->guardar();
    }

    public function prueba_Nombre_de_usuario_vacio()
    {
        $usuario = new Usuario();
        $usuario->edad = 18;
        $usuario->clave = 'secreto';
        $usuario->guardar();
    }

    public function prueba_Guardar_usuario_correctamente()
    {
        $usuario = new Usuario();
        $usuario->usuario = 'Fabian Sosa';
        $usuario->edad = 18;
        $usuario->clave = 'secreto';
        if ($usuario->guardar()) {
            echo "El usuario se guardo correctamente";
        }
    }

    public function prueba_Eliminar_Usuario()
    {
        $usuario = Usuario::buscar(22);
        if ($usuario->eliminar()) {
            echo "El usuario fue eliminado";
        }
    }

    public function prueba_Favorito_con_PK_invalida()
    {
        $this->usuario->agregarFavorito(13);
        echo "Favorito 13 agregado correctamente????";
    }

    public function prueba_Favorito_con_PK_correcta()
    {
        $this->usuario->agregarFavorito(44);
        echo "Favorito 44 agregado correctamente";
    }

    public function prueba_Agregar_Pago_fecha_incorrecta()
    {
        $this->usuario->agregarPago(4876, '2015-09-08');
        echo "Favorito 44 agregado correctamente";
    }

    public function prueba_Agregar_Pago_fecha_correcta()
    {
        $this->usuario->agregarPago(4876, date($this->usuario->formatoFecha));
        echo "Favorito 44 agregado correctamente";
    }

    public function prueba_Agregar_Pago_importe_cero()
    {
        $this->usuario->agregarPago(0, date($this->usuario->formatoFecha));
        echo "importe 0 agregado correctamente???";
    }
}