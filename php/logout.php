<?php
session_start();
session_unset(); // remove todas as variáveis da sessão
session_destroy(); // encerra a sessão

// Redireciona o usuário para a página de login
header("Location: login.php");
exit();
?>
