<?php
session_start();
include "headerCliente.php";

require_once __DIR__ . '/../../controllers/ProdutoController.php';
require_once __DIR__ . '/../../controllers/CategoriaController.php';

$produtos = ProdutoController::buscarComEstoque();
$categorias = CategoriaController::listar();

$categoria_filtro = $_GET['categoria'] ?? 'todas';
if ($categoria_filtro !== 'todas') {
    $produtos = ProdutoController::buscarPorCategoria((int)$categoria_filtro);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Produtos - FUT STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f6f6;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            transition: .2s;
        }

        .product-card:hover {
            transform: scale(1.02);
            box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            border-radius: 10px;
        }

        .btn-buy {
            background: #111;
            color: white;
            font-weight: bold;
        }

        .btn-buy:hover {
            background: #333;
            color: white;
        }
    </style>
</head>

<body>

    <div style="margin-top:90px;"></div>

    <div class="container">

        <h2 class="fw-bold text-center mb-4">Produtos Disponíveis</h2>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['sucesso'];
                unset($_SESSION['sucesso']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $_SESSION['erro'];
                unset($_SESSION['erro']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-4">
                <label class="fw-bold">Filtrar por categoria:</label>
                <select id="categoriaFiltro" class="form-select" onchange="window.location.href='?categoria=' + this.value">
                    <option value="todas" <?php echo $categoria_filtro === 'todas' ? 'selected' : ''; ?>>Todas</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $categoria_filtro == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php if (empty($produtos)): ?>
            <div class="alert alert-info text-center">
                Nenhum produto disponível no momento.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-md-4">
                        <div class="product-card">
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>"
                                    class="product-img"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>"
                                    onerror="this.src='https://via.placeholder.com/300x260?text=<?php echo urlencode($produto['nome']); ?>'">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/300x260?text=<?php echo urlencode($produto['nome']); ?>"
                                    class="product-img"
                                    alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                            <?php endif; ?>

                            <h4 class="mt-3"><?php echo htmlspecialchars($produto['nome']); ?></h4>
                            <?php if (!empty($produto['descricao'])): ?>
                                <p class="text-muted small"><?php echo htmlspecialchars(substr($produto['descricao'], 0, 300)); ?>.</p>
                            <?php endif; ?>
                            <p class="text-success fw-bold">R$ <?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></p>
                            <p class="text-muted small">Estoque: <?php echo $produto['estoque']; ?> unidades</p>

                            <form method="POST" action="../../actions/carrinho/adicionar.php">
                                <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                                <div class="input-group mb-2">
                                    <input type="number" name="quantidade" class="form-control" value="1" min="1" max="<?php echo $produto['estoque']; ?>">
                                    <button type="submit" class="btn btn-buy">Adicionar ao Carrinho</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>