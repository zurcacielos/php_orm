<?php namespace GeoPagos\Database;


class Pago extends Modelo
{
    /**
     * La tabla usada por el modelo.
     *
     * @var string
     */
    protected $tabla = 'pagos';

    /**
     * Atributos llenables manualmente
     *
     * @var array
     */
    protected $llenable = ['importe','fecha'];

    /**
     * La PK del modelo
     *
     * @var string
     */
    protected $primaryKey = 'codigopago';

    /**
     * Reglas de validacion
     * @var array
     */
    public $reglas = [
        'importe'               => 'requerido|mayor:0',
        'fecha'                 => 'tipo:fecha|requerido|min:hoy',
    ];
}