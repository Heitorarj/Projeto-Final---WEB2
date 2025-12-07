<?php
    include "headerAdmin.php";

    require_once __DIR__ . '/../../controllers/FabricanteController.php';

    $fabricantes = FabricanteController::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Fabricantes Cadastrados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; }

        .table-box {
            background: white;
            padding: 25px;
            margin-top: 90px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
    </style>
</head>

<body>

<div class="container table-box">

    <h3 class="text-center mb-4">Lista de Fabricantes</h3>

    <!-- Mensagens -->
    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
        </div>
    <?php endif; ?>


    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Site</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($fabricantes as $fab): ?>
            <tr>
                <td><?= $fab['id'] ?></td>
                <td><?= $fab['nome'] ?></td>
                <td><?= $fab['site'] ?></td>

                <td>
                    <!-- Botão editar → leva para fabricanteCadastro preenchida -->
                    <a href="fabricanteEditar.php?id=<?= $fab['id'] ?>" 
                       class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <!-- Botão excluir → envia ao controller -->
                    <a href="../../actions/fabricante/adminDashboard.php?acao=excluir&id=<?= $fab['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Tem certeza que deseja excluir este fabricante?');">
                        Excluir
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

    <a href="fabricanteCadastro.php" class="btn btn-success w-100 mt-3">
         Novo Fabricante
    </a>

</div>

</body>
</html>
