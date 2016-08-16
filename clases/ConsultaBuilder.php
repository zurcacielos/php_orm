<?php namespace GeoPagos\Database;


class ConsultaBuilder
{
    /**
     * la clase sobre la que se hace la consulta
     * @var null
     */
    protected $claseModelo = null;

    protected $modelo = null;

    /**
     * Devuelve una instancia de ConsultaBuilder y la asocia a la claseModelo
     * @param $claseModelo
     */
    public function __construct($claseModelo)
    {
        $claseModelo = $claseModelo;
    }

    /**
     * associa un modelo, para luego armar las consultas correspondientes, en base al valor de sus campos
     * @param $modelo
     * @return $this
     */
    public function establecerModelo($modelo) {
        $this->modelo = $modelo;
        return $this;
    }

    /**
     * TODO: aqui deberiamos buscar en la base de datos. Al no tenerla suponemos que si la PK es par el objeto existe
     * y si es impar el objeto no existe. Si existe devolvemos datos al azar.
     * @param $pk
     */
    public function buscar($pk) {
        if (!is_array($pk)) {
            // busca por pk simple, de encontrarlo
            if (is_numeric($pk) && $pk%2==0) {
                // simulamos que el objeto existe
                //TODO: devolver mock objects
                return [];

            }
        } else {
            // busca por pk de mas de un campo
        }
    }

    /**
     * TODO: Guarda el $modelo actual asociado
     */
    public function guardar() {
        // en este momento tenemos la $claseModelo y el $modelo
        // 1. recorremos los atributos llenables del modelo y con eso generamos el INSERT SQL
        // 2. obtenemos del DB Factory una instancia de la connection a la Base de Datos, segun configuracion
        // 3.
        return true;
    }

    /**
     * TODO: Elimina el objeto en la base de datos
     */
    public function eliminar() {
        // en este momento tenemos la $claseModelo y el $modelo
        // con eso sabemos cual es la pk y el valor, para armar el DELETE SQL
        return true;
    }

    /**
     * TODO: eliminar generando un where con los $atributos
     * @return bool
     */
    public function eliminarDonde($atributos) {
        // en este momento tenemos la $claseModelo y el $modelo
        // con eso sabemos cual es la pk y el valor, para armar el DELETE SQL
        return true;
    }

}