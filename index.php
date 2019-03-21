<?php 
session_start();
require 'conexao.php';

if (isset($_SESSION['banco']) && !empty($_SESSION['banco'])) {
	$id = $_SESSION['banco'];
	$sql = "SELECT * FROM contas WHERE id = :id;";
	$sql = $pdo->prepare($sql);
	$sql->bindValue(':id', $id);
	$sql->execute();

	if ($sql->rowCount()>0) {
		$info = $sql->fetch();
	} else {
		header("Location: login.php");
		exit;
	}
} else {
	header("Location: login.php");
	exit;
}
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Caixa Eletronico</title>
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
	</head>
	<body>
		<header class="nome_banco">
			<img src="images/bradesco-nav.jpg" alt="Bradesco">
		</header>

		<section>
			<div class="informacoes">
				<strong>Titular: <ins><?= $info['titular']; ?></ins></strong><br>
				Agencia: <?= $info['agencia']; ?><br>
				Conta: <?= $info['conta']; ?><br>
				Saldo: <?= $info['saldo']; ?><br>
				<a href="sair.php">Sair</a>
			</div>
		</section>
		
		<section class="transacoes">
			<h3>Movimentação/Extrato</h3>
	
			<a class="btn btn-primary" href="addTransacao.php">Adicionar Transação</a><br><br>
	
			<table class="table table-hover" id="tbl_transacoes">
				<tr>
					<th>Data</th>
					<th>Valor</th>
				</tr>
				<?php 
				$sql = "SELECT * FROM historico WHERE id_conta = :id_conta ORDER BY data_operacao DESC;";
				$sql = $pdo->prepare($sql);
				$sql->bindValue(':id_conta', $id);
				$sql->execute();
	
				if ($sql->rowCount() > 0){
					foreach ($sql->fetchAll() as $dado){
						?>	
						<tr>
							<td class="data"><?= date('d/m/Y H:i', strtotime($dado['data_operacao'])); ?></td>
							<td class="valor">
								<?php if ($dado['tipo'] == '0'): ?>						
								<font color="green">R$ <?= $dado['valor']; ?></font>
								<?php else: ?>
								<font color="red">- R$ <?= $dado['valor']; ?></font>
								<?php endif; ?>	
							</td>
						</tr>
						<?php
						}
					}
				?>
			</table>
		</section>
		<script src="js/script.js"></script>
	</body>
</html>