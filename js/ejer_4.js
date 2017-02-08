    
    document.addEventListener("DOMContentLoaded", function () {
    
        var canvas = document.querySelector("canvas");
        //Nuestro contexto de canvas
        var ctx = canvas.getContext("2d");  
        //Cuando dibujar
        var dibujar = false; 

        //Tamaños y color de fondo:
        var cw = canvas.width = 200;                
        var ch = canvas.height = 400;
        canvas.style = "background-color:white;"

        canvas.addEventListener('mousedown', function(evt) {
            dibujar = true;
            ctx.clearRect(0, 0, cw, ch);
            ctx.beginPath();

        }, false);

        //Evento al levantar el raton
        canvas.addEventListener('mouseup', function(evento) {
            dibujar = false;

        }, false);

        //Evento al salir del cuadro el raton
        canvas.addEventListener("mouseout", function(evento) {
           dibujar = false;
        }, false);

        //Evento al mover el raton, aqui es donde pinto
        canvas.addEventListener("mousemove", function(evento) {
            if (dibujar) {
                var m = mueve(canvas, evento);
                ctx.lineTo(m.x, m.y);
                ctx.stroke();
            }
        }, false);

    });

    function mueve(canvas, evento) {
        //El valor devuelto es un objeto TextRectangle que es la unión de los rectángulos devueltos por getClientRects()
        var ClientRect = canvas.getBoundingClientRect();
         //Devuelvo un objeto con x e y: Recondeo de la posicion del borde superior menos 
         //la izquierda (es para que sea un poco mas preciso he leido)
         //http://www.html5canvastutorials.com/tutorials/html5-canvas-text-align/
         //http://stackoverflow.com/questions/6073505/what-is-the-difference-between-screenx-y-clientx-y-and-pagex-y
        return { 
            x: Math.round(evento.clientX - ClientRect.left),
            y: Math.round(evento.clientY - ClientRect.top)
        }
    }

