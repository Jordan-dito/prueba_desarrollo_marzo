<?php

include './conexion.php';

// Crear una instancia de la clase ConexionBD
$conexionBD = new ConexionBD();

// Obtener la conexión PDO
$pdo = $conexionBD->obtenerConexion();
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula'];
    $cargo = $_POST['cargo'];
    $hora_entrada = $_POST['hora_entrada'];
    $hora_salida = $_POST['hora_salida'];
    $fecha_actual = date('Y-m-d');

    // Verificar si el empleado ya existe
    $sql_verificar_empleado = "SELECT id FROM empleados WHERE cedula = ?";
    $stmt_verificar_empleado = $pdo->prepare($sql_verificar_empleado);
    $stmt_verificar_empleado->execute([$cedula]);
    $row = $stmt_verificar_empleado->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        // El empleado ya existe, obtener el ID
        $empleado_id = $row['id'];
    } else {
        // El empleado no existe, insertarlo en la tabla
        $sql_insertar_empleado = "INSERT INTO empleados (nombre, apellido, cedula, cargo) VALUES (?, ?, ?, ?)";
        $stmt_insertar_empleado = $pdo->prepare($sql_insertar_empleado);
        if ($stmt_insertar_empleado->execute([$nombre, $apellido, $cedula, $cargo])) {
            // Obtener el ID del empleado insertado
            $empleado_id = $pdo->lastInsertId();
        } else {
            echo "<script>alert('Error al registrar el empleado'); window.location.href = '../index.php';</script>";
            exit(); // Salir del script si hay un error
        }
    }

    // Verificar si ya existe un registro de asistencia para el empleado en la fecha actual
    $sql_verificar_asistencia = "SELECT id FROM registros_asistencia WHERE empleado_id = ? AND fecha = ?";
    $stmt_verificar_asistencia = $pdo->prepare($sql_verificar_asistencia);
    $stmt_verificar_asistencia->execute([$empleado_id, $fecha_actual]);
    $row_asistencia = $stmt_verificar_asistencia->fetch(PDO::FETCH_ASSOC);
    if (!$row_asistencia) {
        // No existe un registro de asistencia para el empleado en la fecha actual, insertar nuevo registro
        $sql_asistencia = "INSERT INTO registros_asistencia (empleado_id, fecha, hora_entrada, hora_salida) VALUES (?, ?, ?, ?)";
        $stmt_asistencia = $pdo->prepare($sql_asistencia);
        if ($stmt_asistencia->execute([$empleado_id, $fecha_actual, $hora_entrada, $hora_salida])) {
            echo "<script>alert('¡Empleado y asistencia registrados correctamente!'); window.location.href = '../index.php';</script>";
        } else {
            echo "<script>alert('Error al registrar la asistencia'); window.location.href = '../index.php';</script>";
        }
    } else {
        echo "<script>alert('Ya existe un registro de asistencia para este empleado en la fecha actual'); window.location.href = '../index.php';</script>";
    }
}
?>
