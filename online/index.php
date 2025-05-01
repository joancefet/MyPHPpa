<?php

require "options.php";
require "dblogon.php";
require "logging.php";
include_once "get_ip.php";
include_once "check_ip.php";
include_once "auth_check.php";
include_once "session.inc";

$imgpath = $_COOKIE["imgpath"] ?? "img";

if (!check_ip(get_ip())) {
    header("Location: http://web.de");
    exit;
}

$loginError = false;
$loginErrorMsg = "";
session_kill();

if (isset($_POST['submit']) && $_POST['login'] && $_POST['password']) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    session_init();

    $loginEscaped = mysqli_real_escape_string($db, $login);
    $passwordEscaped = mysqli_real_escape_string($db, $password);

    $result = mysqli_query($db, "SELECT user.planet_id, planet.mode,
                                        user.last + interval 5 minute < now(),
                                        user.last IS NOT NULL
                                 FROM user, planet
                                 WHERE login='$loginEscaped'
                                   AND password='$passwordEscaped'
                                   AND planet.id=user.planet_id");

    if ($result && mysqli_num_rows($result) == 1) {
        $myrow = mysqli_fetch_row($result);

        if (($myrow[1] & 0x0F) == 4) {
            $result = mysqli_query($db, "SELECT last_sleep+INTERVAL 48 HOUR > now(), last_sleep+INTERVAL 48 HOUR FROM user WHERE planet_id='$myrow[0]'");
            $stat = mysqli_fetch_row($result);
            if ($stat[0] == 1) {
                $loginError = true;
                $loginErrorMsg = "Sua conta está em férias até $stat[1].";
            }
        } elseif (($myrow[1] & 0x0F) == 3 && (!isset($_POST['force']) || $_POST['force'] != 1)) {
            $result = mysqli_query($db, "SELECT last_sleep+INTERVAL 6 HOUR < now(), last_sleep+INTERVAL 6 HOUR FROM user WHERE planet_id='$myrow[0]'");
            $stat = mysqli_fetch_row($result);
            if ($stat[0] == 0) {
                $loginError = true;
                $loginErrorMsg = "Sua conta está em modo de descanso até $stat[1].";
            }
        } elseif ($myrow[1] != 0 && $myrow[2] == 0 && $myrow[3] == 1 && $myrow[0] > 2) {
            $loginError = true;
            $loginErrorMsg = "Sua conta ainda está logada ou você saiu há menos de 5 minutos.";
        } elseif ($myrow[1] == 0) {
            $res = mysqli_query($db, "SELECT data FROM logging WHERE class=2 AND type=5 AND planet_id='$myrow[0]'");
            $reason = mysqli_num_rows($res) > 0 ? mysqli_fetch_row($res)[0] : "Motivo não especificado";
            $loginError = true;
            $loginErrorMsg = "Sua conta foi banida. Motivo: \"$reason\".";
        } else {
            setcookie("Valid", md5($round), time() + 432000);
            session_create(get_ip(), $login, md5($password), $myrow[0], $imgpath);
            mysqli_query($db, "UPDATE planet SET mode=((mode & 0xF0) + 2) WHERE id='$myrow[0]'");
            mysqli_query($db, "UPDATE user SET last=NOW(), login_date=NOW() WHERE planet_id='$myrow[0]'");
            do_log_id($myrow[0], 1, 1, get_ip());
            do_log_id($myrow[0], 1, 2, get_type());
            header("Location: overview.php");
            exit;
        }
    } else {
        session_kill();
        $loginError = true;
        $loginErrorMsg = "Login ou senha incorretos!";
    }
}

$mytick = mysqli_fetch_row(mysqli_query($db, "SELECT tick FROM general"))[0];
$numuser = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM user"))[0];
$numonline = mysqli_fetch_row(mysqli_query($db, "SELECT COUNT(*) FROM planet WHERE mode=2 OR mode=0xF2"))[0];
$tdate = file_exists($tickfile . '.run') ? date("d/M H:i e", filemtime($tickfile . '.run')) : "<b><span class='text-danger'>Ticker parado</span></b>";

?><!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyPHPpa - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex flex-column justify-content-center align-items-center min-vh-100">
  <div class="text-center mb-4">
    <img src="img/logo.jpg" alt="MyPHPpa logo" width="290" height="145">
  </div>

  <?php if ($loginError): ?>
    <div class="alert alert-warning text-center w-100" style="max-width: 500px;">
      <?= $loginErrorMsg ?>
    </div>
  <?php endif; ?>

  <div class="card text-dark shadow p-4" style="max-width: 400px; width: 100%;">
    <h4 class="card-title text-center mb-3">Acesse sua conta</h4>
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
      <div class="mb-3">
        <label for="login" class="form-label">Usuário</label>
        <input type="text" class="form-control" id="login" name="login" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="d-grid mb-3">
        <button type="submit" name="submit" class="btn btn-primary">Entrar</button>
      </div>
    </form>
    <div class="text-center">
      <a href="sendpass.php">Esqueceu a senha?</a><br>
      <a href="help.php">Sobre o MyPHPpa</a>
    </div>
  </div>

  <div class="mt-4 text-center">
    <p>Total de usuários registrados: <strong><?= $numuser ?></strong></p>
    <p>Usuários online: <strong><?= $numonline ?></strong></p>
    <p>MyT: <strong><?= $mytick ?></strong> | Último tick: <strong><?= $tdate ?></strong></p>
    <?php if ($mytick > $end_of_round): ?>
      <p class="text-warning">Cadastro fechado</p>
    <?php else: ?>
      <a href="signup.php" class="btn btn-outline-light">Criar nova conta</a>
    <?php endif; ?>
  </div>

  <footer class="mt-4 text-center text-secondary small">
    <p>Contato: <a href="mailto:MyPHPpa@web.de">MyPHPpa@web.de</a></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>