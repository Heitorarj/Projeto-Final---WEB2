<?php
    include "headerAdmin.php";

    require_once __DIR__ . '/../../controllers/CategoriaController.php';

    $id = $_GET['id'] ?? null;
    $categoria = CategoriaController::buscarPorId((int)$id);

    if (!$categoria) {
        $_SESSION['erro'] = "Categoria não encontrada!";
        header("Location: listarCategorias.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f4f6f9; }
        .form-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            margin-top: 120px;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="col-md-6 offset-md-3 form-box">

        <h3 class="text-center mb-4">Editar Categoria</h3>

        <form action="../../actions/categoria/processarCategoria.php" method="POST">

            <input type="hidden" name="id" value="<?= $categoria['id'] ?>">

            <label class="fw-bold">Nome da Categoria:</label>
            <input type="text" name="nome" class="form-control mb-3"
                   value="<?= $categoria['nome'] ?>" required>

            <button type="submit" name="acao" value="editar" class="btn btn-warning w-100">
                Salvar Alterações
            </button>

        </form>

        <a href="listarCategorias.php" class="btn btn-secondary w-100 mt-3">
            Voltar
        </a>

    </div>
</div>

</body>
</html>
