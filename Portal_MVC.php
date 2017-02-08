<?php
# Importar modelo de abstracción de base de datos
require_once('./php/core/db_abstract_model.php'); 
require_once('./php/model/model_imagen.php'); 
require_once('./php/model/model_chiste.php');
# Importar Vista general
require_once('./php/core/view.php');

function handler() {
  
    global $modulo;
    global $data;
    $modules = array("user","salir", "loguear", "registrar", "votar", "chistes", "imagen");
    $modulo="";

	//Compruebo que me piden acceder a un módulo implementado y que hay una petición.
    //En caso contrario muestro la página principal
    if (array_key_exists("modulo",$_GET) &&  array_search($_GET["modulo"], $modules) >= 0) {
    	$modulo= $_GET["modulo"];
    }

    switch ($modulo) {
        case 'imagen':
            // var_dump("patata");

            //Si me viene el nombre por GET cargo esa imagen en el centro
            if (isset($_GET['imagen'])) {
                $imagen = new Imagen();        
                $rows = $imagen->read($_GET['imagen']);
                //Si existe la imagen en la bd
                if ($rows != null) {
                    $data["top"] = $imagen->readTen();
                    retornar_vista("core/phtml/main.phtml", $data, $rows);
                }else{
                    header("location: " . $_SERVER['SCRIPT_URI']);
                }
            }else{
                header("location: " . $_SERVER['SCRIPT_URI']);
            }

        break;
        case 'chistes':

            $chiste = new Chiste();
            $rows = $chiste->readAllJokes();
            retornar_vista("core/phtml/main.phtml", $data, $rows);

            break;

        case 'votar':
            
            if (isset($_POST['nombreImagen'])) {
                $nombreImagen = $_POST['nombreImagen'];
            }

            if (isset($_POST['botonPulsado'])) {
                $voto  = $_POST['botonPulsado'];
            }

            $imagen = new Imagen();

            $imagen->update($nombreImagen, $voto);

            $rows = $imagen->readAleatoria();

            retornar_vista("core/phtml/main.phtml", $data, $rows);

            break;

        case "user":
            if ($_SESSION['USUARIO']['nombre'] == 'user') {
    			require_once("php/".$modulo."/controller.php");
            }
            else {
                $data['error'] = "Acceso denegado";
                // Me lleva a la pagina principal
                header("location: " . $_SERVER['SCRIPT_URI']);
            }

            break;

        case "admin":
            if ($_SESSION['USUARIO']['nombre'] == 'admin' ) {
        	   require_once("php/".$modulo."/controller.php");
            }
            else {
                $data['error'] = "Acceso denegado";
                // Me lleva a la pagina principal
                header("location: " . $_SERVER['SCRIPT_URI']);
            }
            
        	break;    

        case "salir":
            $_SESSION = array();
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 3600,
                      $params["path"], $params["domain"],
                      $params["secure"], $params["httponly"]
                      );
            session_destroy();
            header("location: " . $_SERVER['SCRIPT_URI']) ;
            exit();

        case "registrar":
        	if (!isset($_SESSION['USUARIO'])) {
        		$data['action'] = "?modulo=user&peticion=create_perfil";
        		retornar_vista("core/phtml/form_registrar.phtml", $data);
        	}
            else {
                header("location: " . $_SERVER['SCRIPT_URI']);
            }

        	break;

        case "loguear":
        	if (($_POST['nombre'] == "user" && $_POST['contrasenya'] == "user") || ($_POST['nombre'] == "admin" && $_POST['contrasenya'] == "admin")) {
                $_SESSION['USUARIO'] = $_REQUEST;
            }

            header("location: " . $_SERVER['SCRIPT_URI']) ;
            break;

        default:

            $imagen = new Imagen();
            $rows = $imagen->readAleatoria();
            $data["top"] = $imagen->readTen();

            retornar_vista("core/phtml/main.phtml", $data, $rows);
    }
}

    session_start();

    if (!isset($_SESSION['USUARIO'])) {   
        $cabecera = "cabecera.phtml";
        $menu = "lista.phtml";
    }
    else {
    	$data['visitante'] = $_SESSION['USUARIO']["nombre"];
        $cabecera = "cabecera_logueado.phtml";

        if ($data['visitante'] == "admin") {
        	$menu = "menu_admin.phtml";
        }
        else {
        	$menu = "menu_user.phtml";
        }
    }
    
    handler();
   
?>
