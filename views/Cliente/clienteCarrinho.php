<?php
session_start();
include "headerCliente.php";

require_once __DIR__ . '/../../controllers/VendaController.php';

$carrinho = VendaController::obterCarrinho();
$total = VendaController::calcularTotalCarrinho();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - FUT STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
            padding-top: 70px;
        }
        nav {
            background: #0a0a23 !important;
        }
        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: bold;
        }
        .cart-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
        }
        .produto-img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="container mt-4">
    <h2 class="fw-bold text-center mb-4">Meu Carrinho</h2>

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

    <div class="cart-card shadow">

        <?php if (empty($carrinho)): ?>
            <div class="text-center py-5">
                <h4 class="text-muted">Seu carrinho está vazio</h4>
                <a href="clienteDashboard.php" class="btn btn-dark mt-3">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <?php foreach ($carrinho as $item): ?>
                <div class="row mb-3 border-bottom pb-3 align-items-center">
                    <div class="col-md-2">
                        <?php if (!empty($item['imagem'])): ?>
                            <img src="<?php echo htmlspecialchars($item['imagem']); ?>" 
                                 class="produto-img" 
                                 alt="<?php echo htmlspecialchars($item['nome']); ?>"
                                 onerror="this.src='https://via.placeholder.com/100?text=Produto'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/100?text=Produto" 
                                 class="produto-img" 
                                 alt="Produto">
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4">
                        <h5 class="fw-bold"><?php echo htmlspecialchars($item['nome']); ?></h5>
                        <p class="text-muted mb-0">Preço unitário: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                    </div>

                    <div class="col-md-2">
                        <form method="POST" action="../../actions/carrinho/atualizar.php" class="d-inline">
                            <input type="hidden" name="produto_id" value="<?php echo $item['produto_id']; ?>">
                            <input type="number" name="quantidade" class="form-control" 
                                   value="<?php echo $item['quantidade']; ?>" 
                                   min="1" 
                                   onchange="this.form.submit()">
                        </form>
                    </div>

                    <div class="col-md-2 text-end">
                        <p class="fw-bold text-success mb-0">
                            R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?>
                        </p>
                    </div>

                    <div class="col-md-2 text-end">
                        <a href="../../actions/carrinho/remover.php?id=<?php echo $item['produto_id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Deseja remover este item?')">
                            Remover
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="row mt-4">
                <div class="col-md-12">
                    <h4 class="text-end fw-bold">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h4>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <a href="clienteDashboard.php" class="btn btn-secondary w-100">Continuar Comprando</a>
                </div>
                <div class="col-md-6">
                    <form method="POST" action="../../actions/venda/criar.php">
                        <?php foreach ($carrinho as $item): ?>
                            <input type="hidden" name="itens[<?php echo $item['produto_id']; ?>][produto_id]" 
                                   value="<?php echo $item['produto_id']; ?>">
                            <input type="hidden" name="itens[<?php echo $item['produto_id']; ?>][quantidade]" 
                                   value="<?php echo $item['quantidade']; ?>">
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-success w-100 fw-bold">Finalizar Compra</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
