
//Este script recoge una referencia al img que muestra la imagen y le pone un nuevo objeto URL con la fuente a la imagen seleccionada
var loadFile = function(event) {
    var output = document.querySelector('#output');
    // console.log(URL.createObjectURL(event.target.files[0]));
    // console.log(output.src);
    output.src = URL.createObjectURL(event.target.files[0]);
};
