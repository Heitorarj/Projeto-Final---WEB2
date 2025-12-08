<?php
    include "headerAdmin.php";
    require_once __DIR__ . '/../../controllers/CategoriaController.php';

    $categorias = CategoriaController::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Lista de Categorias</title>

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

    <h3 class="text-center mb-4">Lista de Categorias</h3>

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
                <th>Nome da Categoria</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($categorias as $cat): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><?= $cat['nome'] ?></td>

                <td>
                    <a href="categoriaEditar.php?id=<?= $cat['id'] ?>" 
                       class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <form action="../../actions/categoria/deleteCategoria.php" method="POST">
                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                    <button type="submit" name="acao" value="excluir"
                     class="btn btn-danger btn-sm mt-1">
                         excluir
                         
                    </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

    <a href="categoriaCadastro.php" class="btn btn-primary w-100 mt-3">
         Nova Categoria
    </a>

</div>

</body>
</html>
