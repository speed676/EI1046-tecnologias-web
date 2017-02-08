	//Funcion que suscribe el evento submit del login (si está)
    function detectores() {
    	// Cogemos todo el form
        var form = document.querySelector("#submitEvent");

        form.addEventListener("submit", function (event) {
        	// Paramos la accion del submit del formulario
            event.preventDefault();

			// Sacamos del form todos los text (password no, por tema de seguridad)
            var listaInputs = form.getElementsByTagName('input');
            // Para formulario de chiste
        	var textoLargo = form.getElementsByTagName('textarea');
         
        	// Si ha introducido datos, hacemos aparecer la tabla y almacenamos los datos
        	if (listaInputs.length > 0 || textoLargo != '') {
        		//document.querySelector('#producttable').style = "";
                //document.querySelector('.cajaImagen').style = "display: none;";
                //document.querySelector('.cajaGusta').style = "display: none;";
                document.querySelector('#producttable').style = "border: solid;";


	         	// Descontamos los inputs de submit y reset
	            for (var i = 0; i < listaInputs.length; i++) {
		            if (listaInputs[i].value != '' && listaInputs[i].getAttribute('name') != "submit" && listaInputs[i].getAttribute('name') && "reset") {  
		                cargaTabla(listaInputs[i].getAttribute('name'), listaInputs[i].value);
		            }
		        }

		        // Mostramos los datos de la etiqueta textarea
		        if (textoLargo[0].value != '') {
		        	cargaTabla(textoLargo[0].getAttribute('name'), textoLargo[0].value);
		        }
		    }
        });
    }

    function cargaTabla(variable, contenido) {
        // Test to see if the browser supports the HTML template 
        // element by checking for the presence of the template element's content attribute.
        if (document.querySelector('template').content) {
            
            // Instantiate the table with the existing HTML tbody and the row with the template
            var table = document.querySelector('#productrow'),
            td = table.content.querySelectorAll("td");
            td[0].textContent = variable;
            td[1].textContent = contenido;

            // Clone the new row and insert it into the table
            var tb = document.getElementsByTagName("tbody");
            var clone = document.importNode(table.content, true);
            tb[0].appendChild(clone);

        }

    }

    document.addEventListener("DOMContentLoaded", function() {
        detectores();
    });
