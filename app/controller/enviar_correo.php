<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $email_usuario = htmlspecialchars($_POST['email']);
    $destinatario = htmlspecialchars($_POST['destinatario']); // Correo del freelancer
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $presupuesto = htmlspecialchars($_POST['presupuesto']);
    $fecha = htmlspecialchars($_POST['fecha']);

    $subject = "Nuevo encargo de $nombre";
    $message = "
    Nombre: $nombre\n
    Correo: $email_usuario\n
    Presupuesto: $presupuesto\n
    Fecha Límite: $fecha\n
    Descripción del Encargo:\n$descripcion
    ";

    $headers = "From: $email_usuario";

    // Guardar los datos de correo en el JSON si no existen
    $usuariosJson = file_get_contents("../data/usuarios.json");
    $usuarios = json_decode($usuariosJson, true);

    $found = false;
    foreach ($usuarios as &$usuario) {
        if ($usuario['email'] == $destinatario) {
            // Si el correo ya existe, agregar los datos del encargo
            if (!isset($usuario['correos'])) {
                $usuario['correos'] = [];
            }
            $usuario['correos'][] = [
                'usuario' => $nombre,
                'descripcion' => $descripcion,
                'presupuesto' => $presupuesto,
                'fecha' => $fecha
            ];
            $found = true;
            break;
        }
    }

    // Si no se encontró el usuario, agregarlo
    if (!$found) {
        $usuarios[] = [
            'email' => $destinatario,
            'correos' => [
                [
                    'usuario' => $nombre,
                    'descripcion' => $descripcion,
                    'presupuesto' => $presupuesto,
                    'fecha' => $fecha
                ]
            ]
        ];
    }

    file_put_contents("../data/usuarios.json", json_encode($usuarios, JSON_PRETTY_PRINT));

    header('Location: ../view/negocios.php');

}
?>

