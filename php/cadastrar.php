<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nome = $_POST['nome'];
  $email = $_POST['email'];
  $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
  $cpf = $_POST['cpf'];

  $stmt = $conexao->prepare('INSERT INTO usuarios (nome_usuario,email_usuario,senha_usuario,cpf_usuario) VALUES(?,?,?,?)');
  $stmt->bind_param('sssi', $nome, $email, $senha, $cpf);
  $stmt->execute();
  $cadastro = true;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/cadastrar.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Tela de Cadastro</title>
</head>

<body>
  <div class="login-container">
    <img src="../img/LOGO_SMI_BRANCA.png" alt="Logo">

    <div class="voltar">
      <a href="login.php" class="transition-link">⬅ Voltar</a>
    </div>

    <div id="formulario-login">
      <h2>CADASTRAR</h2>
      <form method="POST" action="">

        <div class="input-container">
          <input type="text" name="nome" placeholder=" " required>
          <label>Nome Completo</label>
        </div>

        <div class="input-container">
          <input type="email" name="email" placeholder=" " required>
          <label>Email</label>
        </div>

        <div class="input-container">
          <input type="password" name="senha" placeholder=" " required>
          <label>Senha</label>
        </div>

        <div class="input-container">
          <input type="number" name="cpf" placeholder=" " required>
          <label>CPF</label>
        </div>

        <button type="submit">Criar conta</button>
      </form>
    </div>
  </div>
  <?php if (isset($cadastro) && $cadastro): ?>
    <script>
      Swal.fire({
        title: "Cadastro realizado com sucesso",
        text: "Você será redirecionado para a página de login",
        confirmButtonColor: "#0a2c61",
        icon: "success"
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'login.php';
        }
      });
    </script>
  <?php endif; ?>
  <script>
    window.onload = () => document.body.classList.add("loaded");
  
    document.querySelectorAll(".transition-link").forEach(link => {
      link.addEventListener("click", function(e) {
        e.preventDefault();
        const destino = this.href;
        document.body.classList.remove("loaded");
        setTimeout(() => {
          window.location.href = destino;
        }, 600);
      });
    });
  </script>
</body>

</html>