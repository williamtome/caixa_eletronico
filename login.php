<?php 
session_start();
require 'conexao.php';

if (isset($_POST['agencia']) && !empty($_POST['agencia'])) {
    $agencia = addslashes($_POST['agencia']);
    $conta = addslashes($_POST['conta']);
    $senha = addslashes($_POST['senha']);

    $sql = "SELECT * FROM contas WHERE agencia = ? AND conta = ? AND senha = ?;";
    $sql = $pdo->prepare($sql);
    $sql->execute(array($agencia, $conta, md5($senha)));

    if ($sql->rowCount() > 0) {
        $contas = $sql->fetch();

        $_SESSION['banco'] = $contas['id'];
        header("Location: index.php");
        exit;
    }
}
?>

<html>

<head>
    <title>Caixa Eletronico</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section id="logo">
        <img src="images/logo-bradesco.png" alt="">
        <div id="login">
            <form method="POST">
                <label>Agencia: </label><br>
                <input type="text" name="agencia"><br><br>
                <label>Conta: </label><br>
                <input type="text" name="conta"><br><br>
                <label>Senha: </label><br>
                <input type="password" name="senha"><br><br>
                <button class="button" name="enviar" style="padding-top: 0;
						background-image: url('images/padlock.png');">
                    Enviar
                </button>
            </form>
        </div>
    </section>
</body>

</html> 