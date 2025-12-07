<?php
session_start();
include "headerAdmin.php";

require_once __DIR__ . '/../../controllers/CategoriaController.php';
require_once __DIR__ . '/../../controllers/FabricanteController.php';

$categorias = CategoriaController::listar();
$fabricantes = FabricanteController::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f4f4f4; padding:40px; }
        .card-form { background:white; padding:30px; border-radius:12px; }
    </style>
</head>

<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Cadastro de Produto</h1>

        <!-- BOTÃO DE LISTAR PRODUTOS -->
        <a href="produtosCadastrados.php" class="btn btn-primary fw-bold px-4">
            Ver Produtos Cadastrados
        </a>
    </div>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card-form shadow">

        <form method="POST" action="../../actions/produto/criar.php">

            <label class="fw-bold">Nome do Produto</label>
            <input type="text" name="nome" class="form-control mb-3" placeholder="Ex: Camisa Flamengo 2024" required>

            <label class="fw-bold">Descrição</label>
            <textarea name="descricao" class="form-control mb-3" rows="3" placeholder="Descrição do produto"></textarea>

            <div class="row">
                <div class="col-md-6">
                    <label class="fw-bold">Preço de Custo</label>
                    <input type="number" name="preco_custo" step="0.01" class="form-control mb-3" placeholder="Ex: 100.00">
                </div>
                <div class="col-md-6">
                    <label class="fw-bold">Preço de Venda</label>
                    <input type="number" name="preco_venda" step="0.01" class="form-control mb-3" placeholder="Ex: 199.90" required>
                </div>
            </div>

            <label class="fw-bold">Estoque</label>
            <input type="number" name="estoque" class="form-control mb-3" value="0" min="0">

            <label class="fw-bold">Categoria</label>
            <select name="categoria_id" class="form-select mb-3" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>">
                        <?php echo htmlspecialchars($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="fw-bold">Fabricante</label>
            <select name="fabricante_id" class="form-select mb-3" required>
                <option value="">Selecione um fabricante</option>
                <?php foreach ($fabricantes as $fabricante): ?>
                    <option value="<?php echo $fabricante['id']; ?>">
                        <?php echo htmlspecialchars($fabricante['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="fw-bold">Link da Imagem do Produto</label>
            <input type="url" name="imagem" class="form-control mb-3" placeholder="https://exemplo.com/imagem.jpg">
            <small class="text-muted d-block mb-3">Cole o link (URL) da imagem do produto</small>

            <button type="submit" class="btn btn-dark w-100 fw-bold">Cadastrar Produto</button>
        </form>

    </div>
</div>

</body>
</html>
