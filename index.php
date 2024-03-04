<?php
include './bd/conexion.php';

// Crear una instancia de la clase ConexionBD
$conexionBD = new ConexionBD();

// Obtener la conexión PDO
$pdo = $conexionBD->obtenerConexion();

// Obtener la fecha actual en Ecuador en el formato correcto (YYYY-MM-DD)
$fecha_actual = date("Y-m-d");

// Realizar la consulta SQL para la fecha actual
$sql = "SELECT empleados.nombre, empleados.apellido, empleados.cedula, empleados.cargo, registros_asistencia.fecha, registros_asistencia.hora_entrada, registros_asistencia.hora_salida
        FROM empleados
        JOIN registros_asistencia ON empleados.id = registros_asistencia.empleado_id
        WHERE DATE(registros_asistencia.fecha) = :fecha_actual";

// Preparar la consulta
$stmt = $pdo->prepare($sql);

// Ejecutar la consulta con la fecha actual como parámetro
$stmt->execute(['fecha_actual' => $fecha_actual]);
// Obtener los datos de los registros de asistencia
$registros_asistencia = $stmt->fetchAll();
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
                        <div class="col-12 col-md-3 col-xxl-3 d-flex order-1 order-xxl-1">
                            <div class="card bg-primary text-white flex-fill">
                                <div class="card-header" style="background-color: #222E3C;">
                                    <h5 class="card-title mb-0">Registrar asistencia</h5>
                                </div>
                                <div class="card-body" style="background-color: #222E3C;">
                                    <form action="./bd/insertar_asistencia.php" method="post">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label"><i class="fas fa-user me-1"></i>Nombre:</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" placeholder="Ingrese su nombre" required title="Ingrese solo letras">
                                        </div>
                                        <div class="mb-3">
                                            <label for="apellido" class="form-label"><i class="fas fa-user me-1"></i>Apellido:</label>
                                            <input type="text" class="form-control" id="apellido" name="apellido" pattern="[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+" placeholder="Ingrese su apellido" required title="Ingrese solo letras">
                                        </div>
                                        <div class="mb-3">
    <label for="cedula" class="form-label"><i class="fas fa-id-card me-1"></i>Cédula:</label>
    <input type="text" class="form-control" id="cedula" name="cedula" maxlength="10" placeholder="Ingrese su cédula" required>
    <small id="cedulaHelp" class="form-text text-muted">Ingrese una cédula válida (10 dígitos).</small>
    <div id="cedulaAlert" class="alert alert-danger" role="alert" style="display:none;">
        Cédula incorrecta
    </div>
</div>

<script>
    document.getElementById('cedula').addEventListener('blur', function() {
        var cedula = this.value.trim();
        var regex = /^[0-9]{10}$/;

        if (!regex.test(cedula)) {
            document.getElementById('cedulaAlert').style.display = 'block';
            this.value = '';
            this.focus();
            setTimeout(function() {
                document.getElementById('cedulaAlert').style.display = 'none';
            }, 3000); // Ocultar el mensaje después de 3 segundos
        } else {
            var provincia = parseInt(cedula.substring(0, 2));
            if (provincia < 1 || provincia > 24) {
                document.getElementById('cedulaAlert').style.display = 'block';
                this.value = '';
                this.focus();
                setTimeout(function() {
                    document.getElementById('cedulaAlert').style.display = 'none';
                }, 3000); // Ocultar el mensaje después de 3 segundos
            } else {
                var digitoVerificador = parseInt(cedula.charAt(9));
                var coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
                var suma = 0;
                var digitoCalculado;
                for (var i = 0; i < 9; i++) {
                    digitoCalculado = parseInt(cedula.charAt(i)) * coeficientes[i];
                    suma += (digitoCalculado >= 10) ? digitoCalculado - 9 : digitoCalculado;
                }
                var digitoVerificadorCalculado = 10 - (suma % 10);
                if (digitoVerificadorCalculado != digitoVerificador) {
                    document.getElementById('cedulaAlert').style.display = 'block';
                    this.value = '';
                    this.focus();
                    setTimeout(function() {
                        document.getElementById('cedulaAlert').style.display = 'none';
                    }, 3000); // Ocultar el mensaje después de 3 segundos
                }
            }
        }
    });
</script>


                                        <div class="mb-3">
    <label for="cargo" class="form-label"><i class="fas fa-user-tie me-1"></i>Cargo:</label>
    <select class="form-select" id="cargo" name="cargo" required>
        <option value="">Seleccione un cargo</option>
        <option value="Doctor">Doctor</option>
        <option value="Odontólogo">Odontólogo</option>
        <option value="Enfermero">Enfermero</option>
        <option value="Administrativo">Administrativo</option>
        <option value="Ginecólogo">Ginecólogo</option>
        <option value="Pediatra">Pediatra</option>
        <option value="Cardiólogo">Cardiólogo</option>
        <option value="Neurólogo">Neurólogo</option>
          <option value="Ingeniero">Ingeniero </option>
                 <option value="Desarrollador Web Full Stack">Desarrollador Web Full Stack </option>
        <!-- Agrega más opciones según sea necesario -->
    </select>
</div>

                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="hora_entrada" class="form-label"><i class="fas fa-clock me-1"></i>Hora de Entrada:</label>
                                                <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" placeholder="Ingrese la hora de entrada" required min="06:00" max="10:00">
                                            </div>

                                            <div class="col">
                                                <label for="hora_salida" class="form-label"><i class="fas fa-clock me-1"></i>Hora de Salida:</label>
                                                <input type="time" class="form-control" id="hora_salida" name="hora_salida" placeholder="Ingrese la hora de salida">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fecha_actual" class="form-label"><i class="fas fa-calendar me-1"></i>Fecha  de hoy:</label>
                                            <input type="hidden" class="form-control" id="fecha_actual" name="fecha_actual" value="<?php echo date('Y-m-d'); ?>">
                                            <!-- Campo oculto para enviar la fecha actual al PHP -->
                                            <input type="text" class="form-control" value="<?php echo date('d/m/Y'); ?>" readonly>
                                            <!-- Campo solo de visualización -->
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Registrar</button>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-8 col-xxl-9 d-flex order-2 order-xxl-2">
                            <div class="card bg-light flex-fill">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Registro de asistencia actual con la fecha de <?php echo strftime('%d de %B de %Y'); ?></h5>

                                    <table id="example" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Cédula</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>Cargo</th>
                                           
                                                <th>Hora de Entrada</th>
                                                <th>Hora de Salida</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($registros_asistencia as $fila) : ?>
                                                <tr>
                                                    <td><?= $fila['cedula'] ?></td>
                                                    <td><?= $fila['nombre'] ?></td>
                                                    <td><?= $fila['apellido'] ?></td>
                                                    <td><?= $fila['cargo'] ?></td>
                                                  
                                                    <td><?= $fila['hora_entrada'] ?></td>
                                                    <td><?= $fila['hora_salida'] ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#example').DataTable();
                        });
                    </script>
                    

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
    
    <script src="./js/scripts.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
</body>

</html>