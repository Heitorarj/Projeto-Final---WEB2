
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - FUT STORE</title>

    <!-- Bootstrap -->
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
            margin: 0;
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
        <div class="col-md-5">

            <h2 class="text-center fw-bold">Login</h2>
            <p class="text-center mb-4">Acesse sua conta</p>

            <div class="form-card shadow">
                <form method="POST" action="processa_login.php">

                    <label class="fw-bold">Email</label>
                    <input type="email" class="form-control mb-3" name="email" required>

                    <label class="fw-bold">Senha</label>
                    <input type="password" class="form-control mb-4" name="senha" required>

                    <button class="btn btn-custom w-100">Entrar</button>

                </form>

                <p class="text-center mt-3">
                    Não tem conta? <a href="cadastro.php">Cadastrar</a>
                </p>
            </div>

        </div>
    </div>
</section>

</body>
</html>
