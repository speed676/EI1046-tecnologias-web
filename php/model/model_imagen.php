<?php
# Importar modelo de abstracción de base de datos
require_once('./php/core/db_abstract_model.php');


class Imagen extends DBAbstractModel {

    ############################### PROPIEDADES ################################
    public $nombre;
    public $usuario;
    public $ubicacion;
    public $total_votos;
    public $peor;
    public $mala;
    public $buena;
    public $mejor;

    ################################# MÉTODOS ##################################
    # Traer datos de una imagen
    public function read($nombre='') {
        if($nombre!= '') {
            $this->query = "
                SELECT      nombre, ubicacion, total_votos, peor, mala, buena, mejor
                FROM        imagenes
                WHERE       nombre = '$nombre'
            ";
            $this->get_results_from_query();
        }

        if(count($this->rows) == 1) {
            /*foreach ($this->rows[0] as $propiedad=>$valor) {
                $this->$propiedad = $valor;
            }*/

            $this->mensaje = 'Imagen encontrado';
        } 
        else {
            $this->mensaje = 'Imagen no encontrado';
        }

        return $this->rows;
    }

        # Traer datos de una imagen
    public function readAdmin($nombre='') {
        if($nombre!= '') {
            $this->query = "
                SELECT      nombre, usuario, ubicacion, total_votos, peor, mala, buena, mejor
                FROM        imagenes
                WHERE       nombre = '$nombre'
            ";
            $this->get_results_from_query();
        }

        if(count($this->rows) == 1) {
            /*foreach ($this->rows[0] as $propiedad=>$valor) {
                $this->$propiedad = $valor;
            }*/

            $this->mensaje = 'Imagen encontrado';
        } 
        else {
            $this->mensaje = 'Imagen no encontrado';
        }

        return $this->rows;
    }

    # Crear un nueva imagen
    public function create($nombre, $usuario, $ubicacion) {
        $this->read('$nombre');

        if ($this->nombre != '$nombre') {
            $this->query = "
                    INSERT INTO     imagenes
                    (nombre, usuario, ubicacion)
                    VALUES
                    ('$nombre', '$usuario', '$ubicacion')
            ";

            $this->execute_single_query();
            $this->mensaje = 'Imagen agregada exitosamente';
        }
        else {
            $this->mensaje = "Ya existe una imagen con ese nombre";
        }
    }

    # Eliminar una imagen
    public function delete($nombre='') {
        $this->query = "
                DELETE FROM     imagenes
                WHERE           nombre = '$nombre'
        ";
    
        $this->execute_single_query();
        $this->mensaje = 'Imagen eliminada';
    }

    # Leer todas las imagenes de la base de datos     
    public function readAll() {
        $this->query = "
                SELECT     *
                FROM        imagenes
        ";

        $this->get_results_from_query();
        
        if(count($this->rows) > 1) {
            //procesar la tabla bien
            $this->mensaje = 'Imagenes encontradas';
        } 
        else {
            $this->mensaje = 'Imagenes no encontrados';
        }

        return $this->rows;
    }

    // Leer todas las imagenes de un usuario
    public function readAllUser($usuario) {
        $this->query = "
               SELECT       nombre, ubicacion, total_votos, peor, mala, buena, mejor
               FROM         imagenes
               WHERE        usuario = '$usuario'
        ";

        $this->get_results_from_query();

        if (count($this->rows) > 1) {
            $this->mensaje = "Imagenes encontradas";
        }
        else {
            $this->mensaje = "Imagenes no encontradas";
        }

        return $this->rows;
    }

    // Anyade un voto a la imagen
    public function update($nombre_imagen, $voto) {
        $this->query = "
                UPDATE      imagenes
                SET         $voto = $voto + 1,
                            total_votos = total_votos + 1
                            
                WHERE       nombre = '$nombre_imagen'
        ";

        //var_dump($nombre_imagen."--------".$voto);
        $this->execute_single_query();
        $this->mensaje = 'Imagen modificado';
    }

    # Leer las 10 imagenes con mas votos     
    public function readTen() {
        $this->query = "
                SELECT      nombre, mejor
                FROM                 imagenes
                ORDER BY mejor DESC LIMIT 10
        ";

        $this->get_results_from_query();
        
        if(count($this->rows) > 1) {
            //procesar la tabla bien
            $this->mensaje = 'Imagenes encontradas';
        } 
        else {
            $this->mensaje = 'Imagenes no encontrados';
        }

        return $this->rows;
    }

    // Seleccionar una imagen aleatoria
    public function readAleatoria() {
        $this->query = "
                SELECT      nombre, ubicacion
                FROM        imagenes
                ORDER BY RAND() LIMIT 1
        ";

        $this->get_results_from_query();
        $this->mensaje = 'Imagen encontrado';
        //var_dump($this->rows);
        return $this->rows;
    }

    # Método constructor
    function __construct() {
        $this->db_name = 'al204328';
    }

    # Método destructor del objeto
    function __destruct() {
        unset($this);
    }
}

//$content=file_get_contents($filename);
//echo $this->fetch($file, $id);
?>
