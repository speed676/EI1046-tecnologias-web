var mainPagina = ".container"; //Indica la clase que contiene todo la zona principal
var imagen = ".cajaImagen"; //Indica la zona unicamente de la imagen central
var botonPulsado;
var indice = 0;

function transferComplete(event,updateNode,url_src) {
    // console.log("The transfer is complete.");
    // console.log(url_src);
    updateNode.innerHTML=event.target.responseText;
    //enviaVoto();
    enviaForm();
}

function transferCompleteWithVote(event,updateNode,url_src) {
    // console.log("The transfer is complete.");
    // console.log(url_src);
    //updateNode.innerHTML=event.target.responseText;
    //enviaVoto();

    json = JSON.parse(event.target.response);
    document.querySelector("#titulo").textContent = json[0].nombre;

    document.querySelector("#imagen").src = json[0].ubicacion;
    
    enviaForm();
}

function transferCompleteForJokes(event,updateNode,url_src) {

    //console.log(JSON.parse(event.target.response));
    chistes = JSON.parse(event.target.response);
    //console.log(chistes);
    ponElMotorDelosChistes(chistes);
}


function updateProgress (oEvent) {
    if (oEvent.lengthComputable) {
        var percentComplete = oEvent.loaded / oEvent.total;
        console.log("Progress:"+percentComplete);
    } else {// Tamaño total desconocido
        console.log("Progressing:");
    }
}

function transferFailed(evt) {
    console.log("An error occurred while transferring the file.");
}

function transferCanceled(evt) {
    console.log("The transfer has been canceled by the user.");
}

function sendFailed(evt) {
    console.log("An error occurred while transferring the file.");
}

// Activa evento formulario para realizar llamada  POST en Ajax
function enviaForm(){
    var form = document.querySelector('#subirImagen');

    if (form != null) {
        
        
            document.forms[0].addEventListener("submit",function (event) {
                event.preventDefault();
                //console.log("submit pegao!");
                var data = new FormData(event.target);

                EnviaAjax(event.target.getAttribute("action"),document.querySelector(mainPagina),data, "phtml");
            })
    }
}

// Activa evento formulario para realizar llamada  POST en Ajax
function enviaVoto(){
    var form = document.querySelector('#formMeGusta');

    if (form != null) {
            //console.log(form);   
            form.addEventListener("submit",function (event) {
                event.preventDefault();
                document.querySelector("#nombreImagenVoto").value = document.querySelector("#titulo").textContent;
                document.querySelector("#botonPulsado").value = botonPulsado;
                //console.log("---"+document.querySelector("#botonPulsado").value);

                var data = new FormData(event.target);
                console.log(data);
                
                EnviaAjax(event.target.getAttribute("action"),document.querySelector(imagen),data, "json", 1);

            });
    }
}

// Activa evento formulario para realizar llamada  POST en Ajax
function cargaAjaxChistes(){
    
    cargaAjax("?modulo=chistes",document.querySelector(imagen), "json", 1);

}

// Envia con Ajax el formulario de la página si existe y devuelve un phtml que muestra en la pantalla

function EnviaAjax(url_src,updateNode,data, tipo, esVoto){
    var ajaxSumeImagen=new XMLHttpRequest();
    ajaxSumeImagen.addEventListener("progress", function (event){updateProgress(event)});
    ajaxSumeImagen.addEventListener("load", function (event){
        switch(esVoto){
            case 0: //Caso general de ajax, carga de un contenido en la parte correspondiente
                transferComplete(event,updateNode,url_src);
            break;
            case 1: //Caso ajax para votacion de gato
                transferCompleteWithVote(event,updateNode,url_src);
            break;            
        }
        
    });
    ajaxSumeImagen.addEventListener("error", transferFailed);
    ajaxSumeImagen.addEventListener("abort", transferCanceled);
    ajaxSumeImagen.upload.addEventListener("error", sendFailed);


    ajaxSumeImagen.open("POST",url_src+"&tipo="+tipo);
    ajaxSumeImagen.send(data);
}


//Envia con AJAX todos los enlaces del nav y la respuesta la pone en la etiqueta main
function cargaAjax(url_src, updateNode, tipo, esChiste){
    var ajax=new XMLHttpRequest();
    ajax.addEventListener("progress",updateProgress);
    ajax.addEventListener("load", function (event){
        switch(esChiste){
            case 0: //no es chiste
                transferComplete(event,updateNode,url_src)
            break;
            case 1:
                transferCompleteForJokes(event,updateNode,url_src);
            break;
        }
        
    });
    ajax.addEventListener("error", transferFailed);
    ajax.addEventListener("abort", transferCanceled);
    ajax.open("GET",url_src+"&tipo="+tipo);
    ajax.send();
}


// Activa los hiperenlaces del menu de navegación  para realizar llamadas en Ajax
function detectoresAjax()
{   //Envia con AJAX todos los enlaces del nav y la respuesta la pone en la etiqueta main
    enlaces=document.querySelectorAll("nav a");
    if (enlaces.length > 0) {
        //console.log(enlaces);
        for (let  enlace of enlaces) {
            //console.log(enlace.getAttribute("data-ajax"));

            if (enlace.getAttribute("data-ajax") != "false" || enlace.getAttribute("data-ajax") == undefined) { //Si se establece un atributo distinto de data-ajax=true, no se suscribe (por defecto se suscribe)

                enlace.addEventListener("click",function (event) {
                    event.preventDefault();
                    //console.log("hola;"+event.target.getAttribute("href"));
                    
                    cargaAjax(event.target.getAttribute
                              ("href"),document.querySelector(mainPagina), "phtml", 0);
                    });
            }
        }
    }
    enviaForm();
    enviaVoto();
}

function detectoresBotonesVotos() {
    
    document.querySelector("#btnPeor").addEventListener("click",function (event) {
            // console.log("cachapum, submit!");
            botonPulsado = event.target.name;
            // console.log(botonPulsado);
    });
    
    document.querySelector("#btnMala").addEventListener("click",function (event) {
            // console.log("cachapum, submit!");
            botonPulsado = event.target.name;
            // console.log(botonPulsado);
    });

    document.querySelector("#btnBuena").addEventListener("click",function (event) {
            // console.log("cachapum, submit!");
            botonPulsado = event.target.name;
            // console.log(event.target.name);
    });

    document.querySelector("#btnMejor").addEventListener("click",function (event) {
            // console.log("cachapum, submit!");
            botonPulsado = event.target.name;
            // console.log(event.target.name);
     });
}


function ponElMotorDelosChistes(chistes) {

    frases = chistes;
    //console.log(chistes);
    indice = Math.random()*(frases.length);
    indice = Math.floor(indice);

    rotar(frases, indice);
}

function rotar(frases, indice) {
    if (indice >= frases.length) {
        indice = 0;
    }
    document.querySelector("#joke").innerHTML = frases[indice].chiste;
    indice = indice + 1;
    setTimeout(rotar, 5000, frases, indice);
}

document.addEventListener("DOMContentLoaded", function(event) {
    console.log("¡hola! bienvenido a nuestra web super chachi :)");
    detectoresAjax();
    detectoresBotonesVotos();

    cargaAjaxChistes();
  });
