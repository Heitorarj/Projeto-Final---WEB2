<?php
    include "headerAdmin.php";

    require_once __DIR__ . '/../../controllers/FabricanteController.php';

    $id = $_GET['id'] ?? null;
    $fabricante = FabricanteController::buscarPorId((int)$id);

    if (!$fabricante) {
        $_SESSION['erro'] = "Fabricante não encontrado!";
        header("Location: listarFabricantes.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Fabricante</title>

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

        <h3 class="text-center mb-4">Editar Fabricante</h3>

        <form action="../../actions/fabricante/processarFabricante.php" method="POST">
            
            <input type="hidden" name="id" value="<?= $fabricante['id'] ?>">

            <label class="fw-bold">Nome:</label>
            <input type="text" name="nome" class="form-control mb-3"
                   value="<?= $fabricante['nome'] ?>" required>

            <label class="fw-bold">Site:</label>
            <input type="text" name="site" class="form-control mb-3"
                   value="<?= $fabricante['site'] ?>" required>

            <button type="submit" name="acao" value="editar" class="btn btn-warning w-100">
                Salvar Alterações
            </button>

        </form>

        <a href="listarFabricantes.php" class="btn btn-secondary w-100 mt-3">Voltar</a>

    </div>
</div>

</body>
</html>
