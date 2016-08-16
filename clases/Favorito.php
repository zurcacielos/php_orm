<?php namespace GeoPagos\Database;


class Favorito extends Modelo
{
    /**
     * La tabla usada por el modelo.
     *
     * @var string
     */
    protected $tabla = 'favoritos';

    /**
     * Atributos llenables manualmente
     *
     * @var array
     */
    protected $llenable = ['codigousuario','codigousuariofavorito'];

    /**
     * La PK del modelo
     *
     * @var string
     */
    protected $primaryKey = ['codigousuario','codigousuariofavorito'];

    /**
     * Reglas de validacion
     * @var array
     */
    public $reglas = [
        'codigousuario'         => 'requerido|fk:Usuario',
        'codigousuariofavorito' => 'requerido|fk:Usuario',
    ];
}