function init() {
}




$(document).ready(function () {
    // Soporte button click handler
    $("#btnsoporte").click(function () {
        if ($('#rol_id').val() == 1) {
            $('#lbltitulo').html("Acceso Soporte");
            $('#btnsoporte').html("Acceso Usuario");
            $('#rol_id').val(2);
            $("#imgtipo").attr("src", "public/2.jpg");
        } else {
            $('#lbltitulo').html("Acceso Usuario");
            $('#btnsoporte').html("Acceso Soporte");
            $('#rol_id').val(1);
            $("#imgtipo").attr("src", "public/1.jpg");
        }
    });

    // Operador button click handler
    $("#btnoperador").click(function () {
        if ($('#rol_id').val() == 1) {
            $('#lbltitulo').html("Acceso Operador");
            $('#btnoperador').html("Acceso Usuario");
            $('#rol_id').val(3);
            $("#imgtipo").attr("src", "public/3.png");
        } else {
            $('#lbltitulo').html("Acceso Usuario");
            $('#btnoperador').html("Acceso Operador");
            $('#rol_id').val(1);
            $("#imgtipo").attr("src", "public/1.jpg");
        }
    });

    // Monitor button click handler
    $("#btnmonitor").click(function () {
        if ($('#rol_id').val() == 1) {
            $('#lbltitulo').html("Acceso Monitor");
            $('#btnmonitor').html("Acceso Usuario");
            $('#rol_id').val(4);
            $("#imgtipo").attr("src", "public/4.png");
        } else {
            $('#lbltitulo').html("Acceso Usuario");
            $('#btnmonitor').html("Acceso Monitor");
            $('#rol_id').val(1);
            $("#imgtipo").attr("src", "public/1.jpg");
        }
    });
});
init();