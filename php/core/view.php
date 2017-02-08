<?php


/*evitar codigo malicioso*/
function _H($cadena){echo htmlspecialchars($cadena,ENT_QUOTES);}
    
// function retornar_vista($vista, $data=array(),$rows=array()) {
//     global $cabecera;
//     global $menu;

//     require_once("./php/core/phtml/".$cabecera);
//     require_once("./php/core/phtml/".$menu);
//     require_once("./php/".$vista);
// 	require_once("./php/core/phtml/publicidad_pie.phtml");
	
// 	//echo($vista);
// }

function retornar_vista($vista, $data=array(),$rows=array()) {
    
    global $cabecera;
    global $menu;
	$tipo="";

	if (array_key_exists("tipo",$_REQUEST)) 
		$tipo=$_REQUEST["tipo"];

	switch ($tipo){
		case "phtml":
			require_once("./php/".$modulo.$vista);
			break;
		case "json":
			header('Content-Type: application/json');
			echo json_encode($rows);
			break;
		default:
			//var_dump($rows[0]['nombre']."----".$rows[0]['ubicacion']);
		    require_once("./php/core/phtml/".$cabecera);
		    require_once("./php/core/phtml/".$menu);
		    require_once("./php/".$vista);
			require_once("./php/core/phtml/publicidad_pie.phtml");
	}
}


?>

