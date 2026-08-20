<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "crud_aula";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// CADASTRAR
if (isset($_POST['cadastrar'])) {
    
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    
    $sql = "INSERT INTO usuarios (nome, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("ss", $nome, $email);
    $stmt->execute();
    
    header("Location: index.php");
    exit();
}

// DELETAR
if (isset($_GET['excluir'])) {
    
    $id = $_GET['excluir'];
    
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: index.php");
    exit();
}

// EDITAR
if (isset($_POST['editar'])) {
    
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    
    $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    $stmt->bind_param("ssi", $nome, $email, $id);
    $stmt->execute();
    
    header("Location: index.php");
    exit();
}

// BUSCAR USUÁRIOS
$sql = "SELECT id, nome, email FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuários</title>
</head>
<body>

    <h2>Cadastro de Usuário</h2>
    <form method="POST">
        
        <label>Nome:</label>
        <input type="text" name="nome" required>
        <br><br>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        <br><br>
        
        <button type="submit" name="cadastrar">
            Cadastrar
        </button>
    </form>
    
    <br><hr><br>
    
    <h2>Usuários Cadastrados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        
        <?php while ($usuario = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= $usuario['nome'] ?></td>
                <td><?= $usuario['email'] ?></td>
                <td>
                    <a href="index.php?excluir=<?= $usuario['id'] ?>">
                        Excluir
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
