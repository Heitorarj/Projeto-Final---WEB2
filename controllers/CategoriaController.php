<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../interfaces/iDao.php';
require_once __DIR__ . '/../models/Categoria.php';

class CategoriaController
{
    private static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function listar(): array
    {
        try {
            return Categoria::findAll();
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return [];
        }
    }

    public static function buscarPorId(int $id): ?array
    {
        try {
            return Categoria::read($id);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return null;
        }
    }

    public static function criar(): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }

        try {
            $nome = $_POST['nome'] ?? '';

            if (empty($nome)) {
                throw new Exception("Nome da categoria é obrigatório");
            }

            $data = ['nome' => $nome];
            Categoria::create($data);

            $_SESSION['sucesso'] = "Categoria cadastrada com sucesso!";
            header('Location: ../views/Admin/adminDashboard.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }
    }

    public static function atualizar(int $id): void
    {
        self::startSession();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }

        try {
            $nome = $_POST['nome'] ?? '';

            if (empty($nome)) {
                throw new Exception("Nome da categoria é obrigatório");
            }

            $data = ['nome' => $nome];
            Categoria::update($id, $data);

            $_SESSION['sucesso'] = "Categoria atualizada com sucesso!";
            header('Location: ../views/Admin/adminDashboard.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }
    }

    public static function deletar(int $id): void
    {
        self::startSession();

        try {
            Categoria::delete($id);

            $_SESSION['sucesso'] = "Categoria excluída com sucesso!";
            header('Location: ../views/Admin/adminDashboard.php');
            exit();

        } catch (Exception $e) {
            $_SESSION['erro'] = $e->getMessage();
            header('Location: ../views/Admin/adminDashboard.php');
            exit();
        }
    }

    public static function buscarPorNome(string $nome): ?array
    {
        try {
            return Categoria::findByNome($nome);
        } catch (Exception $e) {
            self::startSession();
            $_SESSION['erro'] = $e->getMessage();
            return null;
        }
    }
}