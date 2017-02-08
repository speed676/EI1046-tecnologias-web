<?php
# Importar modelo de abstracción de base de datos
require_once('./php/core/db_abstract_model.php');


class Chiste extends DBAbstractModel {

    ############################### PROPIEDADES ################################
    public $titulo;
    public $usuario;
    public $chiste;

    ################################# MÉTODOS ##################################
    # Traer datos de un chiste
    public function read($titulo='') {
        if ($titulo != '') {
            $this->query = "
                   SELECT   titulo, chiste
                   FROM     chistes
                   WHERE    titulo = '$titulo'
            ";

            $this->get_results_from_query();
        }

        if (count($this->rows) == 1) {
            $this->mensaje = 'Chiste encontrado';
        }
        else {
            $this->mensaje = 'Chiste no encontrado';
        }

        return $this->rows;
    }

    # Subir un nuevo chiste
    public function create($titulo, $usuario, $chiste) {
        // Comprobamos que no hay un chiste con titulo igual al nuevo chiste
        $this->read('$titulo');

        if ($this->titulo != '$titulo') {
            $this->query = "
                   INSERT INTO  chistes
                   (titulo, usuario, chiste)
                   VALUES 
                   ('$titulo', '$usuario', '$chiste')
            ";

            $this->execute_single_query();
            $this->mensaje = "Chiste agregado exitosamente";
        }
        else {
            $this->mensaje = "El chiste ya existe";
        }
    }

    // Borrar un chiste
    public function delete($titulo) {
        $this->query = "
               DELETE FROM      chistes
               WHERE            titulo = '$titulo'
        ";

        $this->execute_single_query();
        $this->mensaje = "Chiste eliminado correctamente";
    }

    // Leer todos los chistes de la base de datos
    public function readAll() {
        $this->query = "
               SELECT       *
               FROM         chistes
        ";

        $this->get_results_from_query();

        if (count($this->rows) > 1) {
            $this->mensaje = "Chistes encontrados";
        }
        else {
            $this->mensaje = "Chistes no encontrados";
        }

        return $this->rows;
    }

    // Leer SOLO todos los chistes de la base de datos
    public function readAllJokes() {
        $this->query = "
               SELECT       chiste
               FROM         chistes
        ";

        $this->get_results_from_query();

        if (count($this->rows) > 1) {
            $this->mensaje = "Chistes encontrados";
        }
        else {
            $this->mensaje = "Chistes no encontrados";
        }

        return $this->rows;
    }


    // Leer todos los chistes de un usuario
    public function readAllUser($usuario) {
        $this->query = "
               SELECT       titulo, chiste
               FROM         chistes
               WHERE        usuario = '$usuario'
        ";

        $this->get_results_from_query();

        if (count($this->rows) > 1) {
            $this->mensaje = "Chistes encontrados";
        }
        else {
            $this->mensaje = "Chistes no encontrados";
        }

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
