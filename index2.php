<?php

require "infra/conexao.php";

// CADASTRAR

if (isset($_POST['cadastrar'])) {

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    $sql = "INSERT INTO usuarios2 (nome, email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ss", $nome, $email);
    $stmt->execute();

    header("Location: index2.php");
    exit();
}

// DELETAR
if (isset($_GET['excluir'])) {

    $id = $_GET['excluir'];

    $sql = "DELETE FROM usuarios2 WHERE id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index2.php");
    exit();
}

// EDITAR
if (isset($_POST['editar'])) {

    $id = $_POST['id'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';

    $sql = "UPDATE usuarios2 SET nome = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("ssi", $nome, $email, $id);
    $stmt->execute();

    header("Location: index2.php");
    exit();
}

// BUSCAR USUÁRIOS (para preencher a tabela e, se necessário, o form de edição)
$sql = "SELECT id, nome, email FROM usuarios2 ORDER BY id DESC";
$usuarios = $conn->query($sql);

// Se veio ?editar=ID pela URL, busca esse usuário específico para preencher o formulário
$usuarioEditar = null;
if (isset($_GET['editar'])) {
    $idEditar = $_GET['editar'];
    $stmt = $conn->prepare("SELECT id, nome, email FROM usuarios2 WHERE id = ?");
    $stmt->bind_param("i", $idEditar);
    $stmt->execute();
    $usuarioEditar = $stmt->get_result()->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Usuários</title>
</head>
<body>

    <h2><?= $usuarioEditar ? "Editar Usuário" : "Cadastro de Usuários" ?></h2>
    <form method="POST">

        <?php if ($usuarioEditar): ?>
            <input type="hidden" name="id" value="<?= $usuarioEditar['id'] ?>">
        <?php endif; ?>

        <label>Nome:</label>
        <input type="text" name="nome" value="<?= $usuarioEditar ? htmlspecialchars($usuarioEditar['nome']) : '' ?>" required>
        <br><br>
        <label>Email:</label>
        <input type="email" name="email" value="<?= $usuarioEditar ? htmlspecialchars($usuarioEditar['email']) : '' ?>" required>
        <br><br>

        <?php if ($usuarioEditar): ?>
            <button type="submit" name="editar">Salvar edição</button>
            <a href="index2.php">Cancelar</a>
        <?php else: ?>
            <button type="submit" name="cadastrar">Cadastrar</button>
        <?php endif; ?>
    </form>

    <hr>

    <h2>Usuários Cadastrados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>

        <?php while ($usuario = $usuarios->fetch_assoc()): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td>
                    <a href="index2.php?editar=<?= $usuario['id'] ?>">Editar</a>
                    |
                    <a href="index2.php?excluir=<?= $usuario['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>