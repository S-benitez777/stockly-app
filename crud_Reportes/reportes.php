<?php

$conexion = new mysqli('localhost', 'root', '', 'sistema_de_gestion_de_inventarios', 3307);
if ($conexion->connect_error) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error al conectar con la base de datos",
        "detalle" => $conexion->connect_error
    ]);
    exit;
}
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $result = $conexion->query("SELECT * FROM reporte_ventas");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case 'crear':
        $stmt = $conexion->prepare("INSERT INTO reporte_ventas (tipo_reporte, ventas_totales, cantidad_ventas, mejor_vendido, menos_vendido) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdiss", $_POST['tipo_reporte'], $_POST['ventas_totales'], $_POST['cantidad_ventas'], $_POST['mejor_vendido'], $_POST['menos_vendido']);
        $stmt->execute();
        echo json_encode(['status' => 'success']);
        break;

    case 'actualizar':
        $stmt = $conexion->prepare("UPDATE reporte_ventas SET tipo_reporte=?, ventas_totales=?, cantidad_ventas=?, mejor_vendido=?, menos_vendido=? WHERE id_reporte=?");
        $stmt->bind_param("sdissi", $_POST['tipo_reporte'], $_POST['ventas_totales'], $_POST['cantidad_ventas'], $_POST['mejor_vendido'], $_POST['menos_vendido'], $_POST['id_reporte']);
        $stmt->execute();
        echo json_encode(['status' => 'updated']);
        break;

    case 'eliminar':
        $conexion->query("DELETE FROM reporte_ventas WHERE id_reporte = " . intval($_POST['id']));
        echo json_encode(['status' => 'deleted']);
        break;

    case 'exportar_pdf':
        require 'vendor/autoload.php'; // Asegúrate de instalar dompdf con composer
    

        $result = $conexion->query("SELECT * FROM reporte_ventas");
        $html = "<h2>Listado de Reportes</h2><table border='1' cellpadding='5'><tr><th>ID</th><th>Tipo</th><th>Total</th><th>Cantidad</th><th>Mejor Vendido</th><th>Menos Vendido</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $html .= "<tr><td>{$row['id_reporte']}</td><td>{$row['tipo_reporte']}</td><td>{$row['ventas_totales']}</td><td>{$row['cantidad_ventas']}</td><td>{$row['mejor_vendido']}</td><td>{$row['menos_vendido']}</td></tr>";
        }
        $html .= "</table>";

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("reportes.pdf", ["Attachment" => false]);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción inválida']);
}
$conexion->close();
