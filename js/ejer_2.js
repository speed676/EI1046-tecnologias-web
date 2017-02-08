	// Añade eventos a los enlaces de los menus
	function hipervinculos() {
		// Cogemos todo el menu del usuario (contiene partes que no son links)
		var menu = document.querySelector(".menuUserAdmin");

		// Sacamos todos los enlaces del menu 
		var listaEnlaces = menu.getElementsByTagName('a');

		// Añadimos eventos a los enlaces
		for (var i = 0; i < listaEnlaces.length; i++) {
			listaEnlaces[i].addEventListener("click", function(event) {
			// Paramos la acción de los enlaces
			event.preventDefault();

			// Datos del enlace
			var link = this.getAttribute('href');

			// Mostramos el link en la pagina central
			document.querySelector('#ejer_2').textContent = "La URL del link es " + link;

			// Ocultamos el contenido de la pagina central (main)
			document.querySelector('.cajaImagen').style = "display: none;";
			document.querySelector('.cajaGusta').style = "display: none";
			});
		}
	}

	document.addEventListener("DOMContentLoaded", function() {
        hipervinculos();
    });