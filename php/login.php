<?php
include('conexao.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cpf_usuario = trim($_POST['cpf_usuario']);
  $senha_usuario = $_POST['senha_usuario'];

  $stmt = $conexao->prepare('SELECT * FROM usuarios WHERE cpf_usuario = ? LIMIT 1');
  $stmt->bind_param('s', $cpf_usuario);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();

    if ($usuario['status_usuario'] !== 'ativo') {
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'warning',
            title: 'Usuário inativo',
            text: 'Entre em contato com o administrador.',
          });
        });
      </script>";
    } elseif (password_verify($senha_usuario, $usuario['senha_usuario'])) {
      $_SESSION['id_usuario'] = $usuario['id_usuario'];
      $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
      $_SESSION['email_usuario'] = $usuario['email_usuario'];
      $_SESSION['cpf_usuario'] = $usuario['cpf_usuario'];
      $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
      $_SESSION['setor'] = $usuario['setor'];

      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'success',
            title: 'Login realizado com sucesso!',
            text: 'Redirecionando para a página inicial...',
            timer: 2000,
            showConfirmButton: false
          }).then(() => {
            window.location.href = 'inicio.php';
          });
        });
      </script>";
    } else {
      echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
          Swal.fire({
            icon: 'error',
            title: 'Senha incorreta',
            text: 'Verifique e tente novamente.',
          });
        });
      </script>";
    }
  } else {
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
          icon: 'error',
          title: 'Usuário não encontrado',
          text: 'Verifique o CPF digitado.',
        });
      });
    </script>";
  }

  $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tela de Login</title>
  <link rel="stylesheet" href="../css/login.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="login-container">
    <img src="../img/LOGO_SMI_BRANCA.png" alt="">
    <div id="formulario-login">
      <h2>ENTRAR</h2>
      <form method="POST">
        <div class="input-container">
          <input type="text" name="cpf_usuario" id="usuario" placeholder=" " required>
          <label for="usuario">CPF</label>
        </div>

        <div class="input-container">
          <input type="password" name="senha_usuario" id="senha" placeholder=" " required>
          <label for="senha">Senha</label>
        </div>

        <div class="links-login">
          <a href="../php/esqueci_senha.php" class="transition-link">Esqueci a senha</a>
        </div>
        <button type="submit">Entrar</button>
      </form>
    </div>
  </div>

  <script>
    window.onload = () => document.body.classList.add("loaded");

    document.querySelectorAll(".transition-link").forEach(link => {
      link.addEventListener("click", function(e) {
        e.preventDefault();
        const destino = this.href;
        document.body.classList.remove("loaded");
        setTimeout(() => {
          window.location.href = destino;
        }, 400);
      });
    });
  </script>
</body>
</html>
