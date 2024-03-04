<?php
include './bd/conexion.php';

// Crear una instancia de la clase ConexionBD
$conexionBD = new ConexionBD();

// Obtener la conexión PDO
$pdo = $conexionBD->obtenerConexion();


?>


<!DOCTYPE html>
<html lang="en">
<!-- head -->
<?php include './head.php'; ?>

<body class="sb-nav-fixed">
    <!-- navbar -->
    <?php include './navbar.php'; ?>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

                <!-- menu lateral -->
                <?php include './menu_lateral.php'; ?>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <div class="row mt-4">

                    </div>

                    <div class="col-12 col-md-6 col-xxl-9 d-flex order-2 order-xxl-2">
                        <div class="card bg-light flex-fill">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Modulo de Asistencia</h5>

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Reporte de Asistencia Semanal</h5>
                                        <div class="mb-3">
                                            <label for="selectMes" class="form-label">Seleccionar mes:</label>
                                            <select id="selectMes" class="form-select">
                                                <option value="1">Enero</option>
                                                <option value="2">Febrero</option>
                                                <option value="3">Marzo</option>
                                                <option value="4">Abril</option>
                                                <option value="5">Mayo</option>
                                                <option value="6">Junio</option>
                                                <option value="7">Julio</option>
                                                <option value="8">Agosto</option>
                                                <option value="9">Septiembre</option>
                                                <option value="10">Octubre</option>
                                                <option value="11">Noviembre</option>
                                                <option value="12">Diciembre</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="selectSemana" class="form-label">Seleccionar semana:</label>
                                            <select id="selectSemana" class="form-select">
                                                <option value="1">Semana 1</option>
                                                <option value="2">Semana 2</option>
                                                <option value="3">Semana 3</option>
                                                <option value="4">Semana 4</option>
                                                <option value="5">Semana 5</option>
                                            </select>
                                        </div>
                                        <button id="btnGenerarReporte" class="btn btn-primary">Generar Reporte</button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6>Reporte de asistencia para el mes y semana seleccionados</h6>

                                    <!-- Botón para generar el PDF -->
                                    <button id="btnGenerarPDF">Generar PDF</button>

                                    <table id="tablaAsistencia" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                              
                                            
                                                <th>Hora de Entrada</th>
                                                <th>Hora de Salida</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Aquí se cargarán dinámicamente los registros de asistencia -->
                                        </tbody>
                                    </table>



                                    <script>
                                        $(document).ready(function() {


                                            $('#btnGenerarReporte').click(function() {
                                                // Obtener los valores seleccionados de mes y semana
                                                var mes = $('#selectMes').val();
                                                var semana = $('#selectSemana').val();

                                                // Enviar los valores al script PHP mediante AJAX
                                                $.ajax({
                                                    url: './bd/generar_datos_semanal.php', // Ruta al script PHP que procesará la solicitud
                                                    method: 'POST',
                                                    data: {
                                                        mes: mes,
                                                        semana: semana
                                                    },
                                                    success: function(data) {
                                                        // Manejar la respuesta del servidor (los datos del reporte)
                                                        // Por ejemplo, puedes actualizar la tabla con los nuevos datos
                                                        $('#tablaAsistencia tbody').html(data);
                                                    },
                                                    error: function(xhr, status, error) {
                                                        // Manejar errores en caso de que ocurran durante la solicitud AJAX
                                                        console.error(error);
                                                    }
                                                });
                                            });





                                        });
                                    </script>


                                    <script>
                                        $('#btnGenerarPDF').click(function() {

                                            // Obtener el valor seleccionado del mes
                                            var selectMes = document.getElementById("selectMes");
                                            var mesSeleccionado = selectMes.options[selectMes.selectedIndex].text;

                                            // Obtener el valor seleccionado de la semana
                                            var selectSemana = document.getElementById("selectSemana");
                                            var semanaSeleccionada = selectSemana.options[selectSemana.selectedIndex].text;
                                            var doc = new jsPDF(); // Crear una nueva instancia de jsPDF

                                            // Establecer estilos de texto
                                            doc.setFont('helvetica');
                                            doc.setFontType('bold');
                                            doc.setFontSize(16);


                                            doc.text('Reporte de Asistencia Semanal - ' + mesSeleccionado + ' - ' + semanaSeleccionada, 50, 20);

                                            // Agregar encabezado con imagen
                                            var imgData = 'data:image/png;base64,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'; // Ruta de la imagen
                                            doc.addImage(imgData, 'JPEG', 5, 2, 20, 20); // Agregar la imagen al PDF

                                            // Establecer estilos para las celdas de la tabla
                                            var cellWidth = 40;
                                            var cellHeight = 10;
                                            var fontSize = 12;
                                            var padding = 2;

                                            // Definir estilos para los encabezados de columna
                                            doc.setFontType('bold');
                                            doc.setFontSize(fontSize);
                                            doc.setFillColor(255, 255, 255); // Establecer color de fondo blanco
                                            doc.setTextColor(0, 0, 0); // Establecer color de texto
                                            doc.setDrawColor(0); // Establecer color de borde

                                            // Agregar nombres de columnas
                                            doc.rect(10, 30, cellWidth, cellHeight, 'S'); // S es para dibujar solo los bordes
                                            doc.text('Fecha', 12, 36); // Ajustar posición del texto para centrarlo verticalmente
                                            doc.rect(50, 30, cellWidth, cellHeight, 'S');
                                            doc.text('Nombre', 52, 36);
                                            
                                            doc.rect(90, 30, cellWidth, cellHeight, 'S');
                                            doc.text('Apellido', 92, 36);

                                         

                                       
                                            doc.rect(130, 30, cellWidth, cellHeight, 'S');
                                            doc.text('Hora de Entrada', 132, 36);
                                            doc.rect(170, 30, cellWidth, cellHeight, 'S');
                                            doc.text('Hora de Salida', 172, 36);

                                            // Definir estilos para las filas de datos
                                            doc.setFontType('normal');
                                            doc.setFontSize(fontSize - 1);
                                            doc.setFillColor(255, 255, 255); // Establecer color de fondo blanco
                                            doc.setTextColor(0, 0, 0); // Establecer color de texto
                                            doc.setDrawColor(0); // Establecer color de borde

                                            // Iterar sobre las filas de la tabla y agregar los datos al PDF
                                            var yPos = 40;
                                            $('#tablaAsistencia tbody tr').each(function(index, row) {
                                                var xPos = 10;
                                                $(row).find('td').each(function(index, col) {
                                                    doc.rect(xPos, yPos, cellWidth, cellHeight, 'S'); // S es para dibujar solo los bordes
                                                    doc.text($(col).text(), xPos + padding, yPos + (cellHeight / 2) + padding, {
                                                        align: 'left',
                                                        baseline: 'middle'
                                                    });
                                                    xPos += cellWidth;
                                                });
                                                yPos += cellHeight;
                                            });

                                            // Guardar o descargar el PDF
                                            doc.save('reporte.pdf');
                                        });
                                    </script>







                                    <script>
                                        $(document).ready(function() {
                                            $('#tablaAsistencia').DataTable({

                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>








                    <!-- Agregar los scripts de DataTables y PDF export aquí -->




                </div>
        </div>
    </div>
    </div>


    </main>

    <!-- menu footer.php -->
    <?php include './footer.php'; ?>
    </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    
    <script src="./js/scripts.js"></script>



</body>

</html>