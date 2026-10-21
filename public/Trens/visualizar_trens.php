<?php
include "../../infra/conexao.php";

// Cadastra o trem quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_linha = $_POST["nome_linha"];
    $numero_serie = $_POST["numero_serie"];
    $velocidade = $_POST["velocidade"];
    $estado = $_POST["estado"];
    $funcionario_id = $_POST["funcionario_id"];

    $sql_cadastro = "INSERT INTO trens
        (nome_linha, numero_serie, velocidade, estado, funcionario_id)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql_cadastro);

    if (!$stmt) {
        die("Erro ao preparar cadastro: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $nome_linha,
        $numero_serie,
        $velocidade,
        $estado,
        $funcionario_id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);

        header("Location: visualizar_trens.php");
        exit();
    } else {
        die("Erro ao cadastrar trem: " . mysqli_stmt_error($stmt));
    }
}

// Busca os trens cadastrados e seus funcionários responsáveis
$sql = "SELECT
            trens.id,
            trens.nome_linha,
            trens.numero_serie,
            trens.velocidade,
            trens.estado,
            trens.funcionario_id,
            funcionarios.nome AS funcionario_responsavel
        FROM trens
        LEFT JOIN funcionarios
            ON trens.funcionario_id = funcionarios.id";

$trens = mysqli_query($conn, $sql);

if (!$trens) {
    die("Erro ao consultar trens: " . mysqli_error($conn));
}

// Busca os funcionários para o formulário
$sql_funcionarios = "SELECT id, nome FROM funcionarios ORDER BY nome ASC";
$funcionarios = mysqli_query($conn, $sql_funcionarios);
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
                <li><a href="../trens/visualizar_trens.php" class="active_sidebar">Trens</a></li>
                <li><a href="../rotas.php">Rotas</a></li>
                <li><a href="../monitoramento.php">Monitoramento</a></li>
                <li><a href="../alertas.php">Alertas</a></li>
                <li><a href="../relatorios.php">Relatórios</a></li>
                <li><a href="../usuarios/visu_usuarios.php">Visualização de Usuarios</a></li>
                <li><a href="../sensores/visu_sensores.php" >Visualização de Sensores</a></li>
                <li><a href="../usuarios/login.php">Logout</a></li>
            </ul>

        </aside>
    <container class="container_trens">
    
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

    <section id="tabela_visualizar_trens">
        <h2 class="titulo_tabela">Trens Cadastrados</h2>

        <table class="table_t">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de série</th>
                    <th>Velocidade</th>
                    <th>Estado</th>
                    <th>Funcionário responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($trem = mysqli_fetch_assoc($trens)) { ?>
                    <tr>
                        <td><?php echo $trem["id"]; ?></td>
                        <td><?php echo $trem["numero_serie"]; ?></td>
                        <td><?php echo $trem["velocidade"]; ?> km/h</td>
                        <td><?php echo htmlspecialchars($trem["estado"]); ?></td>
                        <td><?php echo htmlspecialchars($trem["funcionario_responsavel"] ?? "Não atribuído"); ?></td>
                        <td>
                            <a href="editar_trens.php?id=<?php echo $trem["id"]; ?>">Editar</a>

                            <a href="excluir_trens.php?id=<?php echo $trem["id"]; ?>"
                               onclick="return confirm('Tem certeza que deseja excluir este trem?');">
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <div class="div_button">
        <button class="button-container"
                onclick="location.href='trens_cadastro.php'">
            <b>Cadastrar trem</b>
        </button>
    </div>
</div>

        
</main>
</body>
</html>
    </main>

</body>

</html>