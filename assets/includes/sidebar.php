<aside class="main-sidebar sidebar-dark-purple elevation-4" style="background-color:rgb(32, 31, 32);">

    <div class="brand-link d-flex justify-content-between align-items-center">
        <a href="" class="brand-text font-weight-light" style="color: white !important; margin-left: 15px;">
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

                <li class="nav-item menu-close">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-pdf text-warning"></i>
                        <p>
                            Emitir Relatórios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="gerar_relatorio.php?tipo=funcionarios" class="nav-link" target="_blank">
                                <i class="fas fa-user text-warning nav-icon"></i>
                                <p>Funcionários</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="gerar_relatorio.php?tipo=produtos" class="nav-link" target="_blank">
                                <i class="fas fa-box text-warning nav-icon"></i>
                                <p>Produtos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="gerar_relatorio.php?tipo=vendas" class="nav-link" target="_blank">
                                <i class="fas fa-users text-warning nav-icon"></i>
                                <p>Vendas</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <?php elseif ($_SESSION['funcionario_cargo'] === 'preparador'): ?>
                    <li class="nav-item">
                        <a href="pedidos_recebidos.php" class="nav-link">
                            <i class="nav-icon fas fa-blender text-white"></i>
                            <p>Pedidos Recebidos</p>
                        </a>
                    </li>

                    <li class="nav-item menu-close">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-pdf text-warning"></i>
                        <p>
                            Emitir Relatório
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">                  
                        <li class="nav-item">
                            <a href="gerar_relatorio.php?tipo=clientes" class="nav-link" target="_blank">
                                <i class="fas fa-users text-warning nav-icon"></i>
                                <p>Pedidos</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php elseif ($_SESSION['funcionario_cargo'] === 'caixa'): ?>
                    <li class="nav-item">
                        <a href="registrar_pedido.php" class="nav-link">
                            <i class="nav-icon fas fa-cash-register text-white"></i>
                            <p>Registrar Pedido</p>
                        </a>
                    </li>

                    <li class="nav-item menu-close">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-pdf text-warning"></i>
                        <p>
                            Emitir Relatório
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">                  
                        <li class="nav-item">
                            <a href="gerar_relatorio.php?tipo=clientes" class="nav-link" target="_blank">
                                <i class="fas fa-users text-warning nav-icon"></i>
                                <p>Minhas Vendas</p>
                            </a>
                        </li>
                    </ul>
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