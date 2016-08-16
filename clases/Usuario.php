<?php namespace GeoPagos\Database;


class Usuario extends Modelo
{
    /**
     * La tabla usada por el modelo.
     *
     * @var string
     */
    protected $tabla = 'usuarios';

    /**
     * Atributos llenables manualmente
     *
     * @var array
     */
    protected $llenable = ['usuario','clave','edad'];

    /**
     * La PK del modelo
     *
     * @var string
     */
    protected $primaryKey = 'codigousuario';

    /**
     * Reglas de validacion
     * @var array
     */
    public $reglas = [
        'usuario'               => 'requerido',
        'edad'                  => 'requerido|min:18',
    ];

    /**
     * hace una eliminacion permanente del usuario. En una implementacion real debieramos marcarlo como eliminado si
     * tiene pagos asociados y tener en cuenta mas casos.
     */
    public function eliminar()
    {
        if (parent::eliminar()) {
            $this->eliminarTodosLosFavoritos();
            // elimina los favoritos que apunten a este usuario
            Favorito::eliminarDonde(['codigousuariofavorito'=>$this->codigousuario]);
            // TODO: debieramos completar nuestro ORM, para que elimine pagos y usuariospagos y evitar huerfanos
            return true;
        }
    }

    public function agregarFavorito($pk) {
        $fav = new Favorito();
        $fav->codigousuario = $this->codigousuario;
        $fav->codigousuariofavorito = $pk;
        $fav->guardar();
        return $this;
    }

    public function eliminarFavorito($pk) {
        return Favorito::buscarOFallar($pk)->eliminar();
    }

    public function eliminarTodosLosFavoritos() {
        // eliminar los favoritos que este usuario poseía
        Favorito::eliminarDonde(['codigousuario'=>$this->codigousuario]);
    }

    public function agregarPago($importe, $fecha) {
        $pago = new Pago();
        $pago->importe = $importe;
        $pago->fecha = $fecha;
        if ($pago->guardar()) {
            // como no hay DB, el codigopago lo tenemos que agregar manualmente inventandolo
            $pago->codigopago = 66;
            $up = new UsuarioPago();
            $up->codigopago = $pago->codigopago;
            $up->codigousuario = $this->codigousuario;
            return $up->guardar();
        }

        return false;
    }

    /**
     * Elimina los pagos de este usuario, y los registros en la tabla de relacion asociados
     * @param $pk
     * @return bool
     */
    public function eliminarPago($pk) {
        //TODO: debiera eliminar los registros de la tabla usuariospagos tambien. Excede esta implementacion.
        return Pago::buscarOFallar($pk)->eliminar();
    }
}