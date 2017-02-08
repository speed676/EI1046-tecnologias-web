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

	/*Petición, (subtitulo, vista, action de los forms)*/
	$peticiones=array(
		"perfil" => array("Perfil usuario", "view_perfil", ""),
		"update_contrasenya" => array("Cambiar contrasenya", "form_mod_contrasenya", ""),
		"update_email" => array("Cambiar email", "form_mod_email", ""),
        "create_perfil" => array("Nuevo usuario", "view_perfil", ""),

		"create_imagen" => array("Subir imagen", "form_create_imagen", ""),
		"lista_imagenes" => array("Listado de imagenes", "view_lista_imagenes", ""),
		"delete_imagen" => array("Borrar imagen", "view_lista_imagenes", ""),

		"create_chiste" => array("Subir chiste", "form_create_chiste", ""),
		"lista_chistes" => array("Listado de chistes", "view_lista_chistes", ""),
		"delete_chiste" => array("Borrar chiste", "view_lista_chistes", "")
		);
	
    $vistas = array(
        "view_perfil" => array("perfil.phtml", "Perfil usuario"),
        "form_mod_contrasenya" => array("form_mod_contrasenya.phtml", "Formulario Cambiar Contrasenya"),
        "form_mod_email" => array("form_mod_email.phtml", "Formulario Cambiar Email"),

        "form_create_imagen" => array("form_create_imagen.phtml", "Formulario subir imagen"),
        "view_lista_imagenes" => array("lista_imagenes.phtml", "Listado de imagenes del usuario"),

        "form_create_chiste" => array("form_create_chiste.phtml", "Formulario Subir Chiste"),
        "view_lista_chistes" => array("lista_chistes.phtml", "Listado de chistes del usuario")
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
        case "perfil":
        	// Ejecutara estas funciones si es redirigido a esta pagian a traves de un formulario
        	if (isset($_POST['contrasenya_nueva']) && !isset($_POST['nombre'])) {
        		$usuario->update_password($modulo, $_POST['contrasenya_nueva']);
        	}

        	if (isset($_POST['email'])  && !isset($_POST['nombre'])) {
        		$usuario->update_email($modulo, $_POST['email']);
        	}

            $rows = $usuario->read($modulo);
            break;

        case "update_contrasenya":
            $data['action'] = "?modulo=$modulo&peticion=perfil";
            break;

        case "update_email":
        	$data['action'] = "?modulo=$modulo&peticion=perfil";
        	break;

        case "create_perfil":
            if (isset($_POST['nombre']) && isset($_POST['contrasenya']) && isset($_POST['email'])) {
                $usuario->create($_POST);
            } 

            $rows = $usuario->read($_REQUEST['nombre']);
            break;


        /*********************** Acciones sobre imagenes **************************************/
        case "create_imagen":
            $data['action'] = "?modulo=$modulo&peticion=lista_imagenes";
            break;

        case "lista_imagenes":
            // Usamos $_FILES para comprobar si se esta subiendo una imagen
            if (!empty($_FILES)) {

                $nombre_fichero = $_FILES['fichero_usuario']['name'];

                // Saco el nombre del fichero sin la extension
                $nombre = explode(".", $nombre_fichero);
                $nombre = $nombre[0];

                //$extension_fichero = pathinfo($_FILES, PATHINFO_EXTENSION);
                $direccion = "./img/".basename($_FILES['fichero_usuario']['name']);

                // Movemos la imagen al directorio donde estan las imagenes 
                // y guardamos en la base de datos datos de la imagen
                if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $direccion)) {
                    // Cambiar el permiso de la imagen para poder visualizar cualquier usuario
                    chmod($direccion, 0644);
                    $imagen->create($nombre, $modulo, $direccion);
                }
                else {
                    $data['error'] = "No se ha subido correctamente la imagen";
                }
            }

        	$rows = $imagen->readAllUser($modulo);
        	break;

        case "delete_imagen":
        	if (!isset($_REQUEST['nombre'])) {
        		$data['error'] = "Indica el nombre de la imagen";
        	}

        	$imagen->delete($_REQUEST['nombre']);
        	$rows = $imagen->readAllUser($modulo);
        	break;


        /*********************** Acciones sobre chistes ***************************************/
        case "create_chiste":
        	$data['action'] = "?modulo=$modulo&peticion=lista_chistes";
        	break;
        
        case "lista_chistes":
        	/* Si es redirigida desde formulario subir chiste, 
        	   creamos un nuevo chiste en la base de datos */
        	if (isset($_POST['titulo']) && isset($_POST['chiste'])) {
        		$chiste->create($_POST['titulo'], $modulo, $_POST['chiste']);
        	}

        	$rows = $chiste->readAllUser($modulo);
        	break;

        case "delete_chiste":
        	if (!isset($_REQUEST['titulo'])) {
        		$data['error'] = "Indica el titulo del chiste";
        		break;
        	}

        	$chiste->delete($_REQUEST['titulo']);
        	$rows = $chiste->readAllUser($modulo);
        	break;


        default:
      		$data=array_merge($data,$_POST);
    }
    
	retornar_vista($modulo."/phtml/".$vistas[$peticion[1]][0], $data,$rows);
}

user_handler();
?>
