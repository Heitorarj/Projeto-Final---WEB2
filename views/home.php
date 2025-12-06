<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>FUT STORE - Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f4f4;
        }
        header {
            background: #0a0a23;
            padding: 20px;
            text-align: center;
        }
        header h1 {
            color: #fff;
            font-weight: bold;
        }
        .hero {
            background: url('img/banner.jpg') center/cover no-repeat;
            height: 350px;
            position: relative;
        }
        .hero-text {
            background: rgba(0,0,0,0.65);
            color: #fff;
            padding: 25px;
            border-radius: 10px;
            width: 55%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .card:hover {
            transform: scale(1.03);
            transition: 0.3s;
        }
        .btn-custom {
            background: #0a0a23;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background: #1b1b4d;
        }
        /* Ajuste do carrossel */
        .carousel-item img {
            height: 450px;
            object-fit: cover;
        }
    </style>
</head>

<body>

<header>
    <h1>FUT STORE</h1>
</header>


<section class="hero">
    <div class="hero-text text-center">
        <h2 class="fw-bold">As melhores camisas de futebol estão aqui!</h2>
        <p>Times europeus, brasileiros e coleções exclusivas.</p>
    </div>
</section>


<section class="container text-center my-5">
    <h2 class="fw-bold mb-4">Acesse sua conta ou crie uma nova!</h2>

    <div class="row justify-content-center g-4">

        <div class="col-md-4">
            <div class="card shadow border-0 p-4">
                <h3 class="fw-bold">Cadastro</h3>
                <p>Crie sua conta e aproveite descontos exclusivos.</p>
                <a href="Registro/cadastro.php" class="btn btn-custom btn-lg">Cadastrar</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 p-4">
                <h3 class="fw-bold">Login</h3>
                <p>Já tem conta? Entre agora mesmo.</p>
                <a href="Registro/login.php" class="btn btn-custom btn-lg">Entrar</a>
            </div>
        </div>

    </div>
</section>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
