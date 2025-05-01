<?php

require "options.php";
require "dblogon.php";
require "logging.php";
include_once "session.inc";

session_init();

// Verifica se a sessão ainda é válida
if (session_check()) {
    echo "Erro ao verificar sessão.";
    exit;
}

if ($Planetid > 0) {
    // Atualiza o tempo de uso do jogador antes de desconectar
    mysqli_query($db,
        "UPDATE user SET uptime = SEC_TO_TIME(UNIX_TIMESTAMP(last) - UNIX_TIMESTAMP(login_date) + TIME_TO_SEC(uptime)) 
         WHERE planet_id = '$Planetid' AND planet_id IN (SELECT id FROM planet WHERE (mode & 0xF) = 2)");

    // Marca o planeta como desconectado
    mysqli_query($db,
        "UPDATE planet SET mode = ((mode & 0xF0) + 1) 
         WHERE id = '$Planetid' AND (mode & 0xF) = 2");

    do_log_me(2, 1, ""); // Log de logout
}

session_kill();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Logout - MyPHPpa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex flex-column justify-content-center align-items-center min-vh-100">
    <div class="card text-dark bg-light shadow p-4" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <h4 class="card-title">Logout realizado</h4>
            <p class="mb-0">Goodbye and have a nice day :-)</p>
        </div>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-primary">Voltar para a tela de login</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
