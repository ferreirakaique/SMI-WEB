<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cpf_usuario = $_POST['cpf_usuario'];
  $senha_usuario = $_POST['senha_usuario'];

  $stmt_email = $conexao->prepare('SELECT * FROM usuarios WHERE cpf_usuario = ?');
  $stmt_email->bind_param('s', $cpf_usuario);
  $stmt_email->execute();
  $result_email = $stmt_email->get_result();

  if ($result_email->num_rows > 0) {
    $usuario = $result_email->fetch_assoc();

    if (password_verify($senha_usuario, $usuario['senha_usuario'])) {
      session_start();
      $_SESSION['id_usuario'] = $usuario['id_usuario'];
      $_SESSION['nome_usuario'] = $usuario['nome_usuario'];
      $_SESSION['email_usuario'] = $usuario['email_usuario'];
      $_SESSION['cpf_usuario'] = $usuario['cpf_usuario'];
      header('location:inicio.php');
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/login.css">
  <title>Tela de Login</title>
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
          <a href="../php/cadastrar.php" class="transition-link">Cadastrar conta</a>
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