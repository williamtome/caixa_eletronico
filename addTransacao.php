<?php 
session_start();
require 'conexao.php';
if (isset($_POST['tipo'])) {
	$tipo = $_POST['tipo'];
	$valor = str_replace(",", ".", $_POST['valor']);
	$valor = floatval($valor);

	// $now = date("Y-m-d H:i:s");
	$sql = "INSERT INTO historico (id_conta, tipo, valor, data_operacao)
			 VALUES(:id_conta, :tipo, :valor, NOW());";
	$sql = $pdo->prepare($sql);
	$sql->bindValue(":id_conta", $_SESSION['banco']);
	$sql->bindValue(":tipo", $tipo);
	$sql->bindValue(":valor", $valor);
	$sql->execute();

	if ($tipo == '0') {
		$sql = "UPDATE contas SET saldo = saldo + :valor WHERE id = :id";
		$sql = $pdo->prepare($sql);
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":id", $_SESSION['banco']);
		$sql->execute();
	} else {
		$sql = "UPDATE contas SET saldo = saldo - :valor WHERE id = :id";
		$sql = $pdo->prepare($sql);
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":id", $_SESSION['banco']);
		$sql->execute();
	}

	header("Location: index.php");
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Adicionar Transação</title>
	</head>
	<body>
		<form method="POST">
			Tipo de Transação:
			<select name="tipo">
				<option value="0">Depósito</option>
				<option value="1">Saque</option>
			</select><br><br>
			Valor: <br>
			<input type="text" name="valor" pattern="[0-9.,]{1,}"><br><br>

			<input type="submit" value="Adicionar">
		</form>
	</body>
</html>