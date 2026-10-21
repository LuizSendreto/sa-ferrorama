
<?php
include "../../infra/conexao.php";

$sql_trens = "SELECT id, nome_linha FROM trens ORDER BY nome_linha ASC";
$trens = mysqli_query($conn, $sql_trens);

?>
 <?php

$sql = "SELECT id, nome_rota, descricao, tempo_estimado, data_criacao, trem_id FROM rotas";
$rota = mysqli_query($conn, $sql);

?>  
      
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Rotas</title>

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
            <h2>Cadastro de Rotas</h2>

            <form action="cadastrar_rotas.php" method="POST">

                <div>
                    <label for="nome_rota">Nome da rota:</label>
                    <input
                        type="text"
                        id="nome_rota"
                        name="nome_rota"
                        placeholder="Digite o nome da rota"
                        required
                    >
                </div>

                <div>
                    <label for="descricao">Descrição:</label>
                    <textarea
                        id="descricao"
                        name="descricao"
                        placeholder="Digite a descrição da rota"
                    ></textarea>
                </div>

                <div>
                    <label for="tempo_estimado">Tempo estimado (minutos):</label>
                    <input
                        type="number"
                        id="tempo_estimado"
                        name="tempo_estimado"
                        placeholder="Digite o tempo estimado"
                        min="0"
                        required
                    >
                </div>

                <div>
                    <label for="trens_id">Trem:</label>
                    <select name="trens_id" id="trens_id" required>
                        <option value="">Selecione o trem</option>
                        <?php
                        if ($trens && mysqli_num_rows($trens) > 0) {
                            while ($trem = mysqli_fetch_assoc($trens)) {
                        ?>
                            <option value="<?php echo (int) $trem['id']; ?>">
                                <?php echo htmlspecialchars($trem['nome_linha'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                
                </div>

                <button
                    id="botao_cadastro_trem"
                    type="submit"
                    class="btn btn-outline-danger"
                >Cadastrar Rota</button>

            </form>
    </main>
</body>
    </html>
    