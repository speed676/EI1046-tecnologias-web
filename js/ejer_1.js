    // Abre una nueva ventana con el contenido interno del main y le aplica un estilo
    function nuevaVentana() {
            
        //Creamos la ventana desde el objeto window, sin título sin descripción y con una anchura
        var ventana = window.open("", "", "width=800,height=600");
        //Guardamos una referencia al estilo para ahorrarnos palabras al usarlo
        var estilo = document.styleSheets;    

        var padre, hijo;

        // Head
        padre = ventana.document.querySelector('head');
        hijo = document.createElement('title');
        // Le ponemos como title el nombre de la imagen
        hijo.textContent = document.querySelector('#titulo').textContent;
        padre.appendChild(hijo);

        // Body
        padre = ventana.document.querySelector('body');
        hijo = document.createElement('h2');
        hijo.textContent = document.querySelector('#titulo').textContent;
        padre.appendChild(hijo);

        hijo = document.createElement('img');
        // Cogemos la fuente src de la imagen
        hijo.src = document.querySelector('#imagen').src;
        // Aplicamos un stilo para adecuarla a la ventana
        hijo.style = "max-width:100%;";
        padre.appendChild(hijo);

        // Agregamos el panel de impresión a la ventana
        ventana.print();
    }