<?php namespace GeoPagos\Database;


class Modelo
{
    /**
     * La tabla usada por el modelo.
     *
     * @var string
     */
    protected $tabla;

    /**
     * Los atributos llenables manualmente. Exluido los autoincrementales, timestamps, etc.
     *
     * @var array
     */
    protected $llenable = [];

    /**
     * La PK del modelo
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Todos los atributos del modelo.
     *
     * @var array
     */
    protected $atributos = [];

    /**
     * Reglas que deben cumplir los atributos
     * @var array
     */
    protected $reglas=[];

    /**
     * se usa para las reglas de tipo fecha. Publico para que se pueda leer. Sin un getter por fuera de scope.
     * @var string
     */
    public $formatoFecha = 'Y-m-d';

    /**
     * Devuelve un nuevo Modelo. Si el $pk es proveido, lo busca y si lo encuentra lo carga desde la BD.
     * @param null $pk
     */
    public function __construct($pk=null)
    {
        // primero llenamos el array de atributos
        if ($this->primaryKey) {
            if (is_array($this->primaryKey)) {
                foreach ($this->primaryKey as $campo) {
                    $this->atributos[$campo] = null;
                }
            } else {
                $this->atributos[$this->primaryKey] = null;
            }
        }

        // agregamos a atributos los llenables, evitando duplicados de
        foreach($this->llenable as $campo) {
            if (!array_key_exists($campo,$this->atributos)) {
                $this->atributos[$campo] = null;
            }
        }

        // buscamos el objeto si existe en la DB
        if ($pk) {
            $this->buscarOFallar($pk);
        }
    }

    /**
     * Devuelve dinamicamente los atributos del modelo.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->obtenerAtributo($key);
    }

    /**
     * Establece dinamicamente los atributos del modelo.
     *
     * @param  string $key
     * @param  mixed $value
     * @throws \Exception
     */
    public function __set($key, $value)
    {
        if (array_key_exists($key, $this->atributos)) {
            $this->atributos[$key]=$value;
        } else {
            throw new \Exception('Atributo no encontrado: ' . $key);
        }
    }

    protected function obtenerAtributo($key) {
        if (array_key_exists($key, $this->atributos)) {
            return $this->atributos[$key];
        } else {
            throw new \Exception('Atributo no encontrado: ' . $key);
        }
    }

    protected function establecerAtributo($key, $value) {
        if (array_key_exists($key, $this->atributos)) {
            $this->atributos[$key]=$value;
        } else {
            throw new \Exception('Atributo no encontrado: ' . $key);
        }
    }

    /**
     * Toma el array associativo de atributos y con eso llena todos los atributos de este objeto
     * @param array $atributos
     */
    protected function hidratar($atributos) {
        foreach($atributos as $atributo=>$valor) {
            $this->establecerAtributo($atributo,$valor);
        }
    }

    /**
     * Devuelve un objeto generador de consulta, inyectandole esta clase como dependencia
     * @return ConsultaBuilder
     */
    protected static function nuevaConsulta() {
        return new ConsultaBuilder(static::class);
    }

    /**
     * Devuelve el modelo correspondiente instanciado adecuadamente o null de no existir la pk
     * @param $pk
     */
    public static function buscar($pk) {
        $resultado = self::nuevaConsulta()->buscar($pk);
        if (is_array($resultado)) {
            $objeto = new static;
            $objeto->hidratar($resultado);
            return $objeto;
        } else {
            return null;
        }
    }

    /**
     * Devuelve el modelo correspondiente instanciado adecuadamente o genera una exception
     * @param $pk
     */
    public static function buscarOFallar($pk) {
        $resultado = self::buscar($pk);
        if (!$resultado) {
            throw new \Exception('PK no encontrada');
        } else {
            return $resultado;
        }
    }

    /**
     * Persite el objeto
     */
    public function guardar() {
        if ($this->esValido()) {
            $resultado = self::nuevaConsulta()->establecerModelo($this)->guardar();
            //TODO: aqui podrian guardarse los auto numericos generados durante la consulta en el objeto
            // como no hay db, agregamos un numero par a la PK, si es de pk simple y no esta creada aun (modo insercion)
            if (!is_array($this->primaryKey) && empty($this->obtenerAtributo($this->primaryKey))) {
                $numeroParAlAzar = mt_rand() & ~1;
                $this->establecerAtributo($this->primaryKey,$numeroParAlAzar);
            }
            return $this;
        }
    }

    /**
     * Parsea todas las reglas del modelo, y verifica si cada una de ellas es valida.
     * Podriamos utilizar un parser mas respetable, algo ya hecho en la web, o un DSL (Domain Specific Language)
     * Podriamos utilizar una jerarquia de clases de validacion pero creo que esta fuera de la idea de este test
     * El poner estas cosas aca, viola el principio de responsabilidad unica, y hace muy largo este archivo.
     */
    public function esValido() {
        foreach($this->reglas as $campo => $valorRegla) {
            // si la regla es sobre un campo inexistente falla
            $valor = $this->obtenerAtributo($campo);
            $tipo = null;
            $definiciones = explode('|',$valorRegla);
            foreach ($definiciones as $definicion) {
                $terminos = explode(':',$definicion);
                switch($terminos[0]) {
                    case 'requerido':
                        if ($valor===null || $valor==='') {
                            throw new \Exception('El atributo ' . $campo . ' es requerido.');
                        }
                        break;
                    case 'min':
                        if (count($terminos)!=2) {
                            throw new \Exception('La regla "min" se define como "min:numero", donde numero es entero');
                        }
                        if ($tipo=='fecha' && $terminos[1]=='hoy') {
                            $terminos[1] = date($this->formatoFecha);
                        }

                        if ($valor<$terminos[1]) {
                            throw new \Exception('El atributo ' . $campo . ' debe ser mayor o igual a ' . $terminos[1]);
                        }
                        break;
                    case 'mayor':
                        if (count($terminos)!=2) {
                            throw new \Exception('La regla "mayor" se define como "mayor:numero"');
                        }
                        if ($tipo=='fecha' && $terminos[1]=='hoy') {
                            $terminos[1] = date($this->formatoFecha);
                        }

                        if ($valor<=$terminos[1]) {
                            throw new \Exception('El atributo ' . $campo . ' debe ser mayor a ' . $terminos[1]);
                        }
                        break;
                    case 'fk':
                        if (count($terminos)!=2) {
                            throw new \Exception('La regla "fk" se define como "fk:Modelo", donde Modelo es una sublase valida de Modelo');
                        }
                        $nombreCompletoDeClase = '\\GeoPagos\\Database\\'.$terminos[1];
                        if (!class_exists($nombreCompletoDeClase)) {
                            throw new \Exception('La regla "fk" se define como "fk:Modelo", pero ' . $terminos[1] . ' no es una clase valida o accesible');
                        }
                        // para eso instanciamos la clase pasando el valor del campo, usando reflection
                        $r = new \ReflectionClass($nombreCompletoDeClase);
                        $fk = $r->newInstanceArgs([$valor]);

                        if (!$fk) {
                            throw new \Exception('La regla "fk" para ' . $campo . ' no se ha cumplido');
                        }
                        break;
                    case 'tipo': //TODO: notese que en esta implementacion, tipo debe estar primero en la regla
                        if (count($terminos)!=2) {
                            throw new \Exception('La regla "tipo" se define como "tipo:X", donde X es fecha,entero,email');
                        }
                        if (!in_array($terminos[1],['fecha'])) {
                            throw new \Exception('El atributo ' . $campo . ' tiene una regla con tipo invalido: ' . $terminos[1]);
                        }
                        $tipo = $terminos[1];
                        break;
                }
            }
        }

        return true;
    }

    /**
     * Elimina el modelo
     * @return bool
     */
    public function eliminar() {
        return self::nuevaConsulta()->establecerModelo($this)->eliminar();
    }

    /**
     * Elimina todos los modelos que cumplan con los atributos
     * @return bool
     */
    public static function eliminarDonde($atributos) {
        return self::nuevaConsulta()->eliminarDonde($atributos);
    }

}