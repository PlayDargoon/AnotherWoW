<?php
// src/controllers/NewsController.php
require_once __DIR__ . '/../models/News.php';
require_once __DIR__ . '/../services/DatabaseConnection.php';

class NewsController {
    private $newsModel;
    private $userModel;
    public function __construct() {
        $pdo = DatabaseConnection::getSiteConnection(); // acore_site
        $this->newsModel = new News($pdo);
        require_once __DIR__ . '/../models/User.php';
        $this->userModel = new User(DatabaseConnection::getAuthConnection());
    }
    private function checkAccess() {
        $userId = $_SESSION['user_id'] ?? null;
        $accessLevel = $userId ? $this->userModel->getUserAccessLevel($userId) : 0;
        if ($accessLevel < 4) {
            renderTemplate('layout.html.php', [
                'contentFile' => 'pages/admin_panel_error.html.php',
            ]);
            exit;
        }
    }
    public function manage() {
        $this->checkAccess();
        $newsList = $this->newsModel->getAll();
        $data = [
            'contentFile' => 'pages/news_manage.html.php',
            'newsList' => $newsList,
        ];
        renderTemplate('layout.html.php', $data);
    }
    public function create() {
        $this->checkAccess();
        $data = [
            'contentFile' => 'pages/news_create.html.php',
        ];
        renderTemplate('layout.html.php', $data);
    }
    public function store() {
        $this->checkAccess();
        require_once __DIR__ . '/../helpers/csrf.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf_token = $_POST['csrf_token'] ?? '';
            if (!check_csrf_token($csrf_token)) {
                $error = 'Ошибка безопасности: неверный CSRF-токен.';
                $data = [
                    'contentFile' => 'pages/news_create.html.php',
                    'error' => $error,
                ];
                renderTemplate('layout.html.php', $data);
                return;
            }
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $author = $_SESSION['username'] ?? 'Администрация';
            if ($title && $content && $author) {
                $this->newsModel->create($title, $content, $author);
                header('Location: /news/manage');
                exit;
            } else {
                $error = 'Заполните все поля.';
                $data = [
                    'contentFile' => 'pages/news_create.html.php',
                    'error' => $error,
                ];
                renderTemplate('layout.html.php', $data);
            }
        }
    }
    public function delete() {
        $this->checkAccess();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            $this->newsModel->delete($id);
        }
        header('Location: /news/manage');
        exit;
    }
}
