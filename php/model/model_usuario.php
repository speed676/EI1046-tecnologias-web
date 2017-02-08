<?php
# Importar modelo de abstracción de base de datos
require_once('./php/core/db_abstract_model.php');


class Usuario extends DBAbstractModel {

    ############################### PROPIEDADES ################################
    public $nombre;
    private $contrasenya;
    public $email;

    ################################# MÉTODOS ##################################
    # Traer datos de un usuario
    public function read($nombre='') {
        if($nombre!= '') {
            $this->query = "
                SELECT      nombre, contrasenya, email
                FROM        usuarios
                WHERE       nombre = '$nombre'
            ";
            $this->get_results_from_query();
        }

        if(count($this->rows) == 1) {
            /*foreach ($this->rows[0] as $propiedad=>$valor) {
                $this->$propiedad = $valor;
            }*/

            $this->mensaje = 'Usuario encontrado';
        } 
        else {
            $this->mensaje = 'Usuario no encontrado';
        }

        // Eliminamos la contrasenya para que no se muestre en la pantalla
        unset($this->rows[0]['contrasenya']);

        return $this->rows;
    }

    # Crear un nuevo usuario
    public function create($user_data=array()) {
        foreach ($user_data as $campo=>$valor) {
            $$campo = $valor;
        }

        $this->query = "
                INSERT INTO     usuarios
                (nombre, contrasenya, email)
                VALUES
                ('$nombre', '$contrasenya', '$email')
        ";

        $this->execute_single_query();
        $this->mensaje = 'Usuario agregado exitosamente';
    }

    # Modificar un usuario
    public function update($datos) {
    	// Elimino un dato del formulario: repetir contrasenya
    	if (array_key_exists("contrasenya2", $datos)) {
    		unset($datos['contrasenya2']);
    	}
    	
		$this->query = "
                UPDATE      usuarios
                SET         contrasenya = :contrasenya,
							email = :email
                WHERE       nombre = :nombre
        ";

		$this->get_results_from_query($datos);

		$this->execute_single_query();
        $this->mensaje = 'Usuario modificado';
    }

    # Cambia la contrasenya de un usuario
    public function update_password($nombre, $contrasenya) {
        $this->query = "
               UPDATE       usuarios
               SET          contrasenya = '$contrasenya'
               WHERE        nombre = '$nombre'
               ";

        $this->execute_single_query();
        $this->mensaje = 'Contrasenya modificada';
    }

    # Cambia el email de un usuario
    public function update_email($nombre, $email) {
        $this->query = "
               UPDATE       usuarios
               SET          email = '$email'
               WHERE        nombre = '$nombre'
               ";

        $this->execute_single_query();
        $this->mensaje = "Email modificado";
    }

    # Eliminar un usuario
    public function delete($nombre='') {
        $this->query = "
                DELETE FROM     usuarios
                WHERE           nombre = '$nombre'
        ";
	
        $this->execute_single_query();
        $this->mensaje = 'Usuario eliminado';
    }

    # Leer todos los usuarios de la base de datos     
	public function readAll() {
	    // id, nombre, apellido, email, clave
        $this->query = "
            SELECT     *
            FROM        usuarios
        ";

        $this->get_results_from_query();
        
        if(count($this->rows) > 1) {
			//procesar la tabla bien
			// Elimino las contrasenyas para no mostrar en la pantalla
			// El & es para modificar directamente sobre rows, sobre los elementos del array
	        foreach ($this->rows as &$row) {
	            unset($row['contrasenya']);
	        }
	        
            $this->mensaje = 'Usuarios encontrado';
        } 
        else {
            $this->mensaje = 'Usuario no encontrado';
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
