
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - FUT STORE</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
        }
        header {
            background: #0a0a23;
            padding: 15px;
            text-align: center;
        }
        header h1 {
            color: white;
        }
        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
        }
        .btn-custom {
            background: #0a0a23;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background: #1b1b4d;
        }
    </style>
</head>

<body>

<header>
    <h1>FUT STORE</h1>
</header>

<section class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="text-center fw-bold">Cadastro</h2>
            <p class="text-center mb-4">Crie sua conta para comprar camisas</p>

            <div class="form-card shadow">
                <form method="POST" action="processa_cadastro.php">

     
                    <label class="fw-bold">Nome</label>
                    <input type="text" class="form-control mb-3" name="nome" required>

                 
                    <label class="fw-bold">Login</label>
                    <input type="text" class="form-control mb-3" name="login" required>

          
                    <label class="fw-bold">Senha</label>
                    <input type="password" class="form-control mb-3" name="senha" required>

             
                    <label class="fw-bold">Tipo de Usuário</label>
                    <select name="tipo" class="form-select mb-4" required>
                        <option value="0">Cliente</option>
                        <option value="1">Administrador</option>
                    </select>

                    <button class="btn btn-custom w-100">Cadastrar</button>

                </form>

                <p class="text-center mt-3">
                    Já tem conta? <a href="login.php">Entrar</a>
                </p>
            </div>

        </div>
    </div>
</section>

</body>
</html>
