<aside class="main-sidebar sidebar-dark-purple elevation-4" style="background-color:rgb(32, 31, 32);">

    <div class="brand-link d-flex justify-content-between align-items-center">
        <a href="" class="brand-text font-weight-light" style="color: #FFD700 !important; margin-left: 15px;">
            PéDeAçai
        </a>
        <a href="../home.php" class="btn btn-danger btn-sm mr-2 d-none d-md-inline" title="Sair">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <?php if ($_SESSION['funcionario_cargo'] === 'gerente'): ?>
                    <li class="nav-item">
                        <a href="../../../html/cadastro.html" class="nav-link">
                            <i class="nav-icon fas fa-user-plus text-white"></i>
                            <p>Cadastro de Funcionarios</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link">
                            <i class="nav-icon fas fa-user-plus text-white"></i>
                            <p>Dashboard do sistema</p>
                        </a>
                    </li>                  
                <?php endif; ?>
                <li class="nav-item d-md-none">
                    <a href="../home.php" class="nav-link text-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Sair</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>