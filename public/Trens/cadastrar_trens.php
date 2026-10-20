
<?php
include "../../infra/conexao.php";

$sql_funcionarios = "SELECT id, nome FROM funcionarios ORDER BY nome ASC";
$funcionarios = mysqli_query($conn, $sql_funcionarios);

?>
 <?php

$sql = "SELECT id, nome_linha, numero_serie, velocidade, estado FROM trens";
$trens = mysqli_query($conn, $sql);

?>  


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trens</title>

    <link rel="stylesheet" href="../../assets/style/style.css">
</head>

<body class="body_dashboard">

    <header id="header_trens">
        <nav class="navbar">

            <div class="logo-navbar">
                <img src="../../assets/img/logo_navbar2.png" alt="Logo Strain">
            </div>

            <div class="logo-navbar">
                <img src="../../assets/img/icone_da_navbar.png" alt="logo usuario logado">
            </div>

        </nav>
    </header>

    <main id="main_trens">
        <aside id="sidebar_dashboard">

            <h2 id="titulo_sidebar">Dashboard</h2>

            <ul class="menu_sidebar">
                <li><a href="../../index.php">Início</a></li>
                <li><a href="../dashboard.php" >Dashboard</a></li>
                <li><a href="../trens/cadastrar_trens.php" class="active_sidebar">Trens</a></li>
                <li><a href="../rotas.php">Rotas</a></li>
                <li><a href="../monitoramento.php">Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php">Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php" >Visualização de Sensores</a></li>
                <li><a href="../usuarios/login.php">Logout</a></li>
            </ul>

        </aside>

        <div class="fotos">
            <div class="trens">
                <div class="titulo1">   
                <h2 >Trem 1010 - Linha Azul(Sul)</h2>
                </div>
                <img class="trem-img" src="../../assets/img/Purple and Pink Modern Illustration Happy Halloween Circle Sticker (1).png" alt="Trem azul">
                    <h4 class="trem-info">Velocidade atual: <b>80km/h</b></h4>
                        <h4>Status: <button>Normal</button></h4>
                 </div>

                  
            <div class="trens">
                <div class="titulo2">   
                <h2 >Trem 1010 - Linha Azul(Sul)</h2>
                </div>
                <img class="trem-img" src="../../assets/img/Purple and Pink Modern Illustration Happy Halloween Circle Sticker (1).png" alt="Trem azul">
                    <h4 class="trem-info">Velocidade atual: <b>80km/h</b></h4>
                        <h4>Status: <button>Normal</button></h4>
                 </div>
                 
                 <div class="doistrens"> 

            <div class="trens">
                <div class="titulo3">   
                <h2 >Trem 1010 - Linha Azul(Sul)</h2>
                </div>
                <img class="trem-img" src="../../assets/img/Purple and Pink Modern Illustration Happy Halloween Circle Sticker (1).png" alt="Trem azul">
                    <h4 class="trem-info">Velocidade atual: <b>80km/h</b></h4>
                        <h4>Status: <button>Normal</button></h4>
                 </div>

                  
            <div class="trens">
                <div class="titulo4">   
                <h2 >Trem 1010 - Linha Azul(Sul)</h2>
                </div>
                <img class="trem-img" src="../../assets/img/Purple and Pink Modern Illustration Happy Halloween Circle Sticker (1).png" alt="Trem azul">
                    <h4 class="trem-info">Velocidade atual: <b>80km/h</b></h4>
                        <h4>Status: <button>Normal</button></h4>
                 </div>

        </div>
       


 <div class="table_trens">
            <h1 id="table_trens">Visualização de trens</h1>
    <section id="tabela_cadastro_trens">
        <div class="text-center">
            <h2 class="titulo_tabela">Trens Cadastrados</h2>
            <table class="table_t" style="width: 70%;">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Numero de série</th>
                    <th class="text-center">Velocidade</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Ações</th>
                </tr>
                <?php while ($trem = mysqli_fetch_assoc($trens)) { ?>
                        <tr>
                            <td><?php echo $trem["id"]; ?></td>
                            <td><?php echo $trem["numero_serie"]; ?></td>
                            <td><?php echo $trem["velocidade"]; ?></td>
                            <td><?php echo $trem["estado"]; ?></td>
                            <td>
                                <a href="editar_trens.php?id=<?php echo $trem["id"]; ?>">Editar</a>
                                <a href="excluir_trens.php?id=<?php echo $trem["id"]; ?>" onclick="return confirm('Tem certeza que deseja excluir este trem?');">
                                    Excluir
                                </a>
                            </td>
                        </tr>
                <?php } ?>
            </table>
        </div>
    </section>
     <div class="div_button">
            <button class="button-container" onclick="location.href='../funcionarios/cadastro_funcionarios.php'"> <b>Cadastrar trem</b></button>
        </div>
    </main>
</body>
</html>
    </main>

</body>

</html>