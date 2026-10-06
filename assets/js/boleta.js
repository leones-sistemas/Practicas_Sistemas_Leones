$("#buscar_fechas").click(function(){
    let desde = $("#inicio").val()
    let hasta = $("#fin").val()
    window.location = ruta + "boleta/"+desde+"/"+hasta;
})
document.addEventListener("DOMContentLoaded", () => {

    const buscador = document.getElementById("buscar_personal");
    const filas = document.querySelectorAll("#tabla_asistencias tbody tr");

    buscador.addEventListener("input", function() {

        const texto = this.value.toLowerCase().trim();

        filas.forEach(fila => {

            const nombre = fila.cells[0].textContent.toLowerCase();

            fila.style.display =
                nombre.includes(texto)
                    ? ""
                    : "none";

        });

    });

});