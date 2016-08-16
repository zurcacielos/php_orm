<?php namespace GeoPagos\Database;


class UsuarioPago extends Modelo
{
    /**
     * La tabla usada por el modelo.
     *
     * @var string
     */
    protected $tabla = 'usuariospagos';

    /**
     * Atributos llenables manualmente
     *
     * @var array
     */
    protected $llenable = ['codigousuario','codigopago'];

    /**
     * La PK del modelo
     *
     * @var string
     */
    protected $primaryKey = ['codigousuario','codigopago'];

    /**
     * Reglas de validacion
     * @var array
     */
    public $reglas = [
        'codigousuario'         => 'requerido|fk:Usuario',
        'codigopago'            => 'requerido|fk:Pago',
    ];
}