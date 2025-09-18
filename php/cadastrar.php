<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/cadastrar.css">
  <title>Tela de Cadastro</title>
  <style>
    body {
      opacity: 0;
      transition: opacity 0.6s ease-in-out;
    }
    body.loaded {
      opacity: 1;
    }

    .voltar {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 16px;
      margin-bottom: 15px;
    }
    .voltar a {
      color: #fff;
      text-decoration: none;
    }
    .voltar a:hover {
      text-decoration: underline;
    }
  </style>
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
          <input type="text" name="usuario" placeholder=" " required>
          <label>Nome Completo</label>
        </div>

        <div class="input-container">
          <input type="text" name="cpf" placeholder=" " required>
          <label>CPF</label>
        </div>

        <div class="input-container">
          <input type="password" name="senha" placeholder=" " required>
          <label>Senha</label>
        </div>

        <button type="submit">Criar conta</button>    
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
        }, 600); 
      });
    });
  </script>
</body>
</html>
