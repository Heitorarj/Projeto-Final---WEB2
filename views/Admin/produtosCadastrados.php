<?php
session_start();
include "headerAdmin.php";

require_once __DIR__ . '/../../controllers/ProdutoController.php';

$produtos = ProdutoController::listar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos Cadastrados - Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f4f4f4; padding:40px; }
        .produto-card { border-radius:12px; }
        .produto-img {
            height: 250px;
            object-fit: cover;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <h1 class="fw-bold mb-4">Produtos Cadastrados</h1>

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

    <?php if (empty($produtos)): ?>
        <div class="alert alert-info">
            Nenhum produto cadastrado ainda. <a href="adminDashboard.php">Cadastrar primeiro produto</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4">
                    <div class="card shadow produto-card">
                        <?php if (!empty($produto['imagem'])): ?>
                            <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                 class="card-img-top produto-img" 
                                 alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                 onerror="this.src='https://via.placeholder.com/300x250?text=Imagem+Indisponivel'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x250?text=Sem+Imagem" 
                                 class="card-img-top produto-img" 
                                 alt="Sem imagem">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                            <p class="card-text text-muted mb-1">
                                <strong>Preço:</strong> R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?>
                            </p>
                            <p class="card-text text-muted mb-1">
                                <strong>Estoque:</strong> <?php echo $produto['estoque']; ?> unidades
                            </p>
                            <p class="card-text text-muted mb-1">
                                <strong>Categoria:</strong> <?php echo htmlspecialchars($produto['categoria_nome']); ?>
                            </p>
                            <p class="card-text text-muted mb-3">
                                <strong>Fabricante:</strong> <?php echo htmlspecialchars($produto['fabricante_nome']); ?>
                            </p>
                            <a href="../../actions/produto/deletar.php?id=<?php echo $produto['id']; ?>" 
                               class="btn btn-danger w-100 fw-bold"
                               onclick="return confirm('Deseja realmente excluir este produto?')">
                                Excluir
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
