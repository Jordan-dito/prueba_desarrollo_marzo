<?php
// Incluir el archivo de conexión a la base de datos
include './conexion.php';

// Crear una instancia de la clase ConexionBD
$conexionBD = new ConexionBD();

// Obtener la conexión PDO
$pdo = $conexionBD->obtenerConexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el mes y la semana seleccionados
    $mes = $_POST['mes'];
    $semana = $_POST['semana'];

    // Calcular la fecha de inicio del mes
    $fecha_inicio_mes = date('Y-m-d', strtotime(date('Y') . '-' . $mes . '-01'));
    // Calcular la fecha de inicio de la semana
    $fecha_inicio_semana = date('Y-m-d', strtotime("{$fecha_inicio_mes} + " . ($semana - 1) . " weeks"));

    // Realizar la consulta SQL para obtener los registros de asistencia del mes y semana seleccionados
    $sql = "SELECT fecha, nombre, apellido, hora_entrada, hora_salida
            FROM registros_asistencia
            JOIN empleados ON registros_asistencia.empleado_id = empleados.id
            WHERE YEAR(fecha) = YEAR(:fecha_inicio_semana)
            AND MONTH(fecha) = MONTH(:fecha_inicio_semana)
            AND WEEK(fecha, 1) = WEEK(:fecha_inicio_semana, 1)";

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);

    // Ejecutar la consulta con los parámetros
    $stmt->execute([
        'fecha_inicio_semana' => $fecha_inicio_semana
    ]);

    // Obtener los resultados de la consulta
    $registros_asistencia = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Iniciar el buffer de salida
    ob_start();

    // Verificar si hay registros
    if (empty($registros_asistencia)) {
        // Si no hay registros, mostrar un mensaje
        echo '<tr><td colspan="5">No hay registros para la semana seleccionada</td></tr>';
    } else {
        // Construir el HTML de las filas de la tabla de asistencia
        foreach ($registros_asistencia as $registro) {
            echo '<tr>';
            echo '<td>' . $registro['fecha'] . '</td>';
            echo '<td>' . $registro['nombre'] . '</td>';
            echo '<td>' . $registro['apellido'] . '</td>';
            echo '<td>' . $registro['hora_entrada'] . '</td>';
            echo '<td>' . $registro['hora_salida'] . '</td>';
            echo '</tr>';
        }
    }

    // Obtener el contenido del buffer de salida y limpiarlo
    $html_filas = ob_get_clean();

    // Enviar las filas de la tabla de asistencia como respuesta AJAX
    echo $html_filas;
}
?>
