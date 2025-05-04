<?php 
// Aqui, caso a sessão ainda não esteja iniciada ele inicia
if (session_status() === PHP_SESSION_NONE) session_start();
include(__DIR__ . '/../php/config/db.php');
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Botão sidebar Para responsividade. Importante !!! -->
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #FFD700;">
                <i class="fas fa-bars" style="color: #FFD700;"></i>
                <span class="d-none d-md-inline ml-1" style="color: #FFD700">Menu</span>
            </a>
        </li>
    </ul>

    <!-- icone que fica o usuário que está logado a direita -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" style="color: #FFD700;">
                <i class="fas fa-user-circle mr-1" style="color: #FFD700;"></i>
                <span class="d-none d-md-inline" style="color: #FFD700;">
                    <?= htmlspecialchars($_SESSION['funcionario']['nome'] ?? 'Funcionario') ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <strong><?= htmlspecialchars($_SESSION['funcionario']['nome'] ?? 'Funcionario') ?></strong>
                    <div class="text-muted small">
                        <?= ucfirst($_SESSION['funcionario']['perfil'] ?? 'Perfil') ?>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="../../../php/auth/logout.php" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt mr-2"></i>Sair
                </a>
            </div>
        </li>
    </ul>
</nav>
