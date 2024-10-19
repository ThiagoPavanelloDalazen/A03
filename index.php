<?php
include 'conexao.php';


if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

$sql = "SELECT * FROM formulario_php";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="estilo.css"> 
</head>
<body>
    <h1>Bem-vindo à Página Inicial</h1>
    <p> 
        <a href="cadastro.html">Cadastrar novo registro</a>
    </p>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Data de Nascimento</th>
                    <th>CEP</th>
                    <th>Interesses</th>
                    <th>Sexo</th>
                    <th>Foto</th> 
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['data_nascimento']); ?></td>
                        <td><?php echo htmlspecialchars($row['cep']); ?></td>
                        <td><?php echo htmlspecialchars($row['categoria_interesse']); ?></td>
                        <td><?php echo htmlspecialchars($row['sexo']); ?></td>
                        <td>
                            <?php if (!empty($row['foto'])): ?> 
                                <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($row['nome_completo']); ?>" width="100">
                            <?php else: ?>
                                <p>Sem foto</p>
                            <?php endif; ?>

                            
                        </td>
                        
                                            
                                            
                        <?php 
                       
                        echo "<p>Caminho da foto: " . htmlspecialchars($row['foto']) . "</p>";
                        ?>

                        <td>
                            <a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a> |
                            <a href="excluir.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este registro?');">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Nenhum registro encontrado.</p>
    <?php endif; ?>

    <?php $mysqli->close(); ?> 
</body> 
</html>
