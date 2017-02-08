 <?php

require_once('./php/model/model_usuario.php');
require_once('./php/model/model_chiste.php');
require_once('./php/model/model_imagen.php');

#CRUD es el acrónimo de Crear, Obtener, Actualizar y Borrar (del original en inglés: create, read, update and delete)
#Portal principal

function user_handler() {
    global $modulo;
    global $data;
    $uri = $_SERVER['REQUEST_URI'];
    $data['direccion'] = "http://al204328.al.nisu.org/P2/Ej_18/img/";

	/*Petición, (subtitulo, vista, action de los forms)*/
	$peticiones=array(
		"create_usuario" => array("Nuevo usuario", "form_create_usuario", ""),
		"lista_usuarios" => array("Listado de usuarios", "view_lista_usuarios", ""),
		"update_usuario" => array("Modificar usuario", "form_mod_usuario"),
		"delete_usuario" => array("Borrar usuario", "view_lista_usuarios", ""),
		"buscar_usuario" => array("Buscar usuario", "form_buscar_usuario", "perfil"),
		"perfil" => array("Datos usuario", "view_perfil", ""),
		"modificado" => array("Usuario modificado", "view_perfil", ""),

		"lista_imagenes" => array("Listado de imagenes", "view_lista_imagenes", ""),
		"delete_imagen" => array("Borrar imagen", "view_lista_imagenes", ""),
		"buscar_imagen" => array("Buscar imagen", "form_buscar_imagen", "imagen"),
		"imagen" => array("Datos imagen", "view_imagen", ""),

		"lista_chistes" => array("Listado de chistes", "view_lista_chistes", ""),
		"delete_chiste" => array("Borrar chiste", "view_lista_chistes", ""),
		"buscar_chiste" => array("Buscar chiste", "form_buscar_chiste", "chiste"),
		"chiste" => array("Datos chiste", "view_chiste", "")
		);
	
    $vistas = array(
    	"form_create_usuario" => array("form_create_usuario.phtml", "Formulario nuevo usuario"),
    	"view_lista_usuarios" => array("lista_usuarios.phtml", "Listado de usuarios"),
    	"form_mod_usuario" => array("form_mod_usuario.phtml", "Formulario modificar datos usuario"),
    	"form_buscar_usuario" => array("form_buscar_usuario.phtml", "Formulario buscar usuario"),
    	"view_perfil" => array("perfil.phtml", "Datos usuario"),

    	"view_lista_imagenes" => array("lista_imagenes.phtml", "Listado de imagenes"),
    	"form_buscar_imagen" => array("form_buscar_imagen.phtml", "Formulario buscar imagen"),
    	"view_imagen" => array("imagen.phtml", "Datos imagen"),

    	"view_lista_chistes" => array("lista_chistes.phtml", "Listado de chistes"),
    	"form_buscar_chiste" => array("form_buscar_chiste.phtml", "Formulario buscar chiste"),
    	"view_chiste" => array("chiste.phtml", "Datos chiste")
    	);

	
    //echo $_GET["peticion"];	
    if (array_key_exists("peticion",$_REQUEST) && array_key_exists($_REQUEST["peticion"],$peticiones)) {
   		$pet=$_GET["peticion"];
   	}
    else {
       header("location: " . $_SERVER['SCRIPT_URI']) ;
       exit();
    }
    
    $peticion= $peticiones[$pet];
   
	$rows=array();
    $usuario = new Usuario();
    $imagen = new Imagen();
    $chiste = new Chiste();
    #$data =array('subtitulo'=>$vistas[$peticion[1]][1],'mensaje'=>$usuario->mensaje,'error'=>"peticion:".$pet);
    $data = array_merge($data,array('subtitulo'=>$vistas[$peticion[1]][1],'mensaje'=>$usuario->mensaje,'action'=>"?modulo=user&peticion=$peticion[2]",'error'=>"0 errores"));
    //los datos del formulario de usuarios se envian con $_POST.

    switch ($pet) {
    	/*********************** Acciones sobre usuarios *************************************/
    	case "create_usuario":
    		$data['action'] = "?modulo=$modulo&peticion=lista_usuarios";
            break;

    	case "lista_usuarios":
    		// Si me llega datos de un formulario create, creo el nuevo usuario
    		if (!empty($_POST)) {
    			$usuario->create($_POST);
    		}
    		
    		$rows = $usuario->readAll();
        	break;

    	case "update_usuario":
            if (!isset($_REQUEST['nombre'])) {
                $data['error'] = "Indica el nombre del usuario";
                break;
            }

            $rows = $usuario->read($_REQUEST['nombre']);

            foreach ($rows[0] as $key => $value) {
            	$data[$key] = $value;
            }

            $data['action'] = "?modulo=$modulo&peticion=modificado&nombre=".$_GET['nombre'];
            break;

        case "modificado":
        	if (empty($_POST)) {
        		$data['error'] = "Error al recuperar datos del formulario";
        		break;
        	}

        	$usuario->update($_POST);
        	$rows = $usuario->read($_REQUEST['nombre']);
        	break;

    	case "delete_usuario":
            if (!isset($_REQUEST['nombre'])) {
                $data['error'] = "Indica el nombre del usuario";
                break;
            }

            $usuario->delete($_REQUEST['nombre']);
            $rows = $usuario->readAll();
            break;

    	case "buscar_usuario":
            $data['action'] = "?modulo=$modulo&peticion=$peticion[2]";
            break;

    	case "perfil":
            if (!isset($_REQUEST['nombre'])) {
                $data['error'] = "Indica el nombre del usuario";
                break;
            }

            $rows = $usuario->read($_REQUEST['nombre']);
            break;      


    	/*********************** Acciones sobre imagenes **************************************/
    	case "lista_imagenes":
            $rows = $imagen->readAll();
            break;

    	case "delete_imagen":
            if (!isset($_REQUEST['nombre'])) {
                $data['error'] = "Indica el nombre de la imagen";
            }

            $imagen->delete($_REQUEST['nombre']);
            $rows = $imagen->readAll();
            break;

    	case "buscar_imagen":
            $data['action'] = "?modulo=$modulo&peticion=$peticion[2]";
            break;

    	case "imagen":
            if (!isset($_REQUEST['nombre'])) {
                $data['error'] = "Indica el nombre de la imagen";
                break;
            }

            $rows = $imagen->readAdmin($_REQUEST['nombre']);
            break;


    	/*********************** Acciones sobre chistes ***************************************/
    	case "lista_chistes":
    		$rows = $chiste->readAll();
    		break;

    	case "delete_chiste":
    		if (!isset($_REQUEST['titulo'])) {
                $data['error'] = "Indica el titulo del chiste";
                break;
            }

            $chiste->delete($_REQUEST['titulo']);
            $rows = $chiste->readAll();
            break;

    	case "buscar_chiste":
    		$data['action'] = "?modulo=$modulo&peticion=$peticion[2]";
            break;

    	case "chiste":
    		if (!isset($_REQUEST['titulo'])) {
                $data['error'] = "Indica el titulo del chiste";
                break;
            }

            $rows = $chiste->read($_REQUEST['titulo']);
            break;


        default:
      		$data=array_merge($data,$_POST);
    }
    
	retornar_vista($modulo."/phtml/".$vistas[$peticion[1]][0], $data,$rows);
}

user_handler();
?>
