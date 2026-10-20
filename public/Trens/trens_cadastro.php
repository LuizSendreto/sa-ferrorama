
<?php
include "../../infra/conexao.php";

$sql_funcionarios = "SELECT id, nome FROM funcionarios ORDER BY nome ASC";
$funcionarios = mysqli_query($conn, $sql_funcionarios);

?>
 <?php

$sql = "SELECT id, nome_linha, numero_serie, velocidade, estado FROM trem";
$trem = mysqli_query($conn, $sql);

?>  
      

            <h2>Cadastro de Trens</h2>

            <form action="cadastrar_trem.php" method="POST">

                <div>
                    <label for="nome_linha">Nome da linha:</label>
                    <input
                        type="text"
                        id="nome_linha"
                        name="nome_linha"
                        placeholder="Digite o nome da linha do trem"
                        required
                    >
                </div>

                <div>
                    <label for="numero_serie">Número de série:</label>
                    <input
                        type="text"
                        id="numero_serie"
                        name="numero_serie"
                        placeholder="Digite o número de série do trem"
                        required
                    >
                </div>

                <div>
                    <label for="velocidade">Velocidade mínima:</label>
                    <input
                        type="number"
                        id="velocidade"
                        name="velocidade"
                        placeholder="Digite a velocidade mínima do trem"
                        min="0"
                        step="any"
                        required
                    >
                </div>

                <div>
                    <label for="estado">Status:</label>

                    <select name="estado" id="estado" required>
                        <option value="">Selecione o status:</option>
                        <option value="Ativo">Ativo</option>
                        <option value="Inativo">Inativo</option>
                        <option value="Manutencao">Manutenção</option>
                    </select>
                </div>

                <div>
                    <label for="funcionario_id">Funcionário responsável:</label>

                    <select name="funcionario_id" id="funcionario_id" required>
                        <option value="">Selecione o funcionário:</option>

                        <?php
                        if ($funcionarios && mysqli_num_rows($funcionarios) > 0) {
                            while ($funcionario = mysqli_fetch_assoc($funcionarios)) {
                        ?>

                            <option value="<?php echo (int) $funcionario['id']; ?>">
                                <?php
                                echo htmlspecialchars(
                                    $funcionario['nome'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
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
                >Cadastrar Trem</button>

            </form>
    