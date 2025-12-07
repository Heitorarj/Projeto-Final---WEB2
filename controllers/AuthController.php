<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Administrador.php';

class AuthController
{
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/Registro/login.php');
            exit();
        }

        try {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                $_SESSION['erro'] = "Email e senha são obrigatórios";
                header('Location: ../../views/Registro/login.php');
                exit();
            }

            $usuario = Usuario::login($email, $senha);

            if (!$usuario) {
                $_SESSION['erro'] = "Email ou senha inválidos";
                header('Location: ../../views/Registro/login.php');
                exit();
            }

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            $_SESSION['logado'] = true;

            if ($usuario['tipo'] == 1) {
                header('Location: ../../views/Admin/adminDashboard.php');
            } else {
                header('Location: ../../views/Cliente/clienteDashboard.php');
            }
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../../views/Registro/login.php');
            exit();
        }
    }

    public static function register(): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/Registro/cadastro.php');
            exit();
        }

        try {
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $tipo = $_POST['tipo'] ?? 0;

            if (empty($email) || empty($senha)) {
                $_SESSION['erro'] = "Email e senha são obrigatórios";
                header('Location: ../../views/Registro/cadastro.php');
                exit();
            }

            $data = [
                'nome' => $nome,
                'email' => $email,
                'senha' => $senha,
                'tipo' => (int)$tipo
            ];

            Usuario::create($data);

            $_SESSION['sucesso'] = "Cadastro realizado com sucesso!";
            header('Location: ../../views/Registro/login.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../../views/Registro/cadastro.php');
            exit();
        }
    }

    public static function logout(): void
    {
        self::startSession();
        $_SESSION = [];
        session_destroy();
        header('Location: ../../views/Registro/login.php');
        exit();
    }

    public static function verificarLogin(): bool
    {
        self::startSession();
        return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
    }

    public static function verificarAdmin(): bool
    {
        self::startSession();
        return self::verificarLogin() && isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 1;
    }

    public static function requireLogin(): void
    {
        if (!self::verificarLogin()) {
            header('Location: ../../views/Registro/login.php');
            exit();
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        
        if (!self::verificarAdmin()) {
            $_SESSION['erro'] = "Acesso negado.";
            header('Location: ../../views/Cliente/clienteDashboard.php');
            exit();
        }
    }

    public static function getUsuarioLogado(): ?array
    {
        self::startSession();
        
        if (!self::verificarLogin()) {
            return null;
        }

        return [
            'id' => $_SESSION['usuario_id'] ?? null,
            'nome' => $_SESSION['usuario_nome'] ?? '',
            'email' => $_SESSION['usuario_email'] ?? '',
            'tipo' => $_SESSION['usuario_tipo'] ?? 0
        ];
    }
}