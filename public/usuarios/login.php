
<?php

session_start();

require_once '../../infra/conexao.php';

$erro = "";
$email = "";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Token de segurança para o login
if (empty($_SESSION['csrf_login'])) {
    $_SESSION['csrf_login'] = bin2hex(random_bytes(32));
}

// Processar login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (
        !isset($_POST['csrf_login']) ||
        !is_string($_POST['csrf_login']) ||
        !hash_equals(
            $_SESSION['csrf_login'],
            $_POST['csrf_login']
        )
    ) {
        $erro = "Solicitação inválida. Atualize a página e tente novamente.";

    } elseif (
        $email === "" ||
        $senha === "" ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $erro = "Informe um e-mail e uma senha válidos.";

    } else {

        try {

            $sql = "SELECT id, nome, email, senha, tipo, nivel_acesso
                    FROM usuarios
                    WHERE email = ?
                    LIMIT 1";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $usuario = $resultado->fetch_assoc();

            $stmt->close();

            if (
                $usuario &&
                password_verify($senha, $usuario['senha']) &&
                in_array($usuario['tipo'], ['cliente', 'funcionario'], true)
            ) {

                session_regenerate_id(true);

                $_SESSION['usuario_id'] = (int) $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['tipo_usuario'] = $usuario['tipo'];
                $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];

                unset($_SESSION['csrf_login']);

                if ($usuario['tipo'] === "funcionario") {

                    header("Location: ../dashboard.php");
                    exit;

                } else {

                    header("Location: ../../index.php");
                    exit;

                }

            } else {

                // Não revelar se o e-mail está cadastrado.
                $erro = "E-mail ou senha inválidos.";

            }

        } catch (Throwable $e) {

            error_log($e->getMessage());
            $erro = "Não foi possível realizar o login. Tente novamente.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Fazer Login ● Strain</title>

    <link
        rel="stylesheet"
        href="../../assets/style/style.css"
    >

    <style>

        /* =========================================
           STRAIN - NOVA TELA DE LOGIN
           ========================================= */

        body#pagina_login_strain {
            margin: 0;
            min-height: 100vh;
            display: block;
            overflow-x: hidden;

            font-family: Arial, Helvetica, sans-serif;

            background-image:
                linear-gradient(
                    rgba(255, 255, 255, 0.55),
                    rgba(255, 255, 255, 0.55)
                ),
                url('../../assets/img/Tela de login.png');

            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* Remove a sobreposição global antiga */
        body#pagina_login_strain::before {
            display: none;
        }

        /* Container principal */
        #pagina_login_strain .login-layout {
            display: grid;
            grid-template-columns: 52% 48%;
            width: 100%;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* =========================================
           LADO ESQUERDO - APRESENTAÇÃO
           ========================================= */

        #pagina_login_strain .login-apresentacao {
            display: flex;
            justify-content: center;
            align-items: center;

            min-width: 0;
            padding: 50px 35px;
            box-sizing: border-box;

            position: relative;
            z-index: 2;
        }

        #pagina_login_strain .login-apresentacao img {
            display: block;
            width: 100%;
            max-width: 480px;
            height: auto;
            max-height: 540px;

            object-fit: contain;

            border-radius: 18px;
        }

        /* =========================================
           LADO DIREITO - FUNDO VERMELHO
           ========================================= */

        #pagina_login_strain .login-area {
            position: relative;
            display: flex;

            justify-content: center;
            align-items: center;

            min-width: 0;
            padding: 45px 35px 45px 70px;
            box-sizing: border-box;

            isolation: isolate;
        }

        /* Curva vermelha característica do Strain */
        #pagina_login_strain .login-area::before {
            content: "";

            position: absolute;
            top: 50%;
            left: 8%;

            width: 150%;
            height: 165%;

            transform: translateY(-50%);

            background-color: #850000;
            border-radius: 50% 0 0 50%;

            z-index: -1;
        }

        /* =========================================
           PAINEL DO LOGIN
           ========================================= */

        #pagina_login_strain .login-painel {
            width: 100%;
            max-width: 380px;

            min-height: 470px;

            padding: 45px 38px;
            box-sizing: border-box;

            background-color: #ebdcc7;
            border-radius: 18px;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: stretch;

            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);

            position: relative;
            z-index: 1;
        }

        /* Título */
        #pagina_login_strain .login-titulo {
            margin: 0 0 35px;

            font-size: 36px;
            font-weight: 700;
            text-align: center;

            color: #850000;
        }

        /* Mensagem de erro */
        #pagina_login_strain .login-erro {
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 20px;

            border-radius: 8px;
            border: 1px solid #d69a9a;

            background-color: #fff0f0;
            color: #850000;

            font-size: 13px;
            line-height: 1.5;
            text-align: center;

            box-sizing: border-box;
        }

        /* Formulário */
        #pagina_login_strain .login-formulario {
            display: flex;
            flex-direction: column;

            width: 100%;
            gap: 20px;

            align-items: stretch;
            justify-content: center;

            margin: 0;
            padding: 0;

            background: transparent;
            border: none;
        }

        #pagina_login_strain .login-campo {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        #pagina_login_strain .login-campo label {
            color: #850000;
            font-size: 14px;
            font-weight: 700;
        }

        #pagina_login_strain .login-campo input {
            display: block;

            width: 100%;
            min-width: 0;
            height: 46px;

            margin: 0;
            padding: 10px 14px;

            box-sizing: border-box;

            background-color: #ffffff;
            color: #333333;

            border: 2px solid #850000;
            border-radius: 12px;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;

            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        #pagina_login_strain .login-campo input::placeholder {
            color: #777777;
        }

        #pagina_login_strain .login-campo input:focus {
            border-color: #b52b2b;
            box-shadow: 0 0 0 3px rgba(133, 0, 0, 0.12);
        }

        /* Botão entrar */
        #pagina_login_strain .login-botao {
            display: block;
            align-self: center;

            width: 75%;
            min-height: 45px;

            margin-top: 10px;
            padding: 11px 20px;

            border: none;
            border-radius: 25px;

            background-color: #850000;
            color: #ffffff;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            font-weight: 700;

            text-align: center;
            cursor: pointer;

            transition: background-color .2s, transform .2s;
        }

        #pagina_login_strain .login-botao:hover {
            background-color: #a51b1b;
            transform: translateY(-2px);
        }

        #pagina_login_strain .login-botao:focus-visible {
            outline: 3px solid #bd5e5e;
            outline-offset: 3px;
        }

        /* =========================================
           RESPONSIVIDADE
           ========================================= */

        @media (max-width: 1000px) {

            #pagina_login_strain .login-layout {
                grid-template-columns: 45% 55%;
            }

            #pagina_login_strain .login-apresentacao {
                padding: 30px 20px;
            }

            #pagina_login_strain .login-area {
                padding: 35px 30px 35px 55px;
            }

            #pagina_login_strain .login-titulo {
                font-size: 30px;
            }
        }

        @media (max-width: 700px) {

            #pagina_login_strain .login-layout {
                grid-template-columns: 1fr;
                min-height: 100vh;
            }

            #pagina_login_strain .login-apresentacao {
                padding: 30px 20px 10px;
            }

            #pagina_login_strain .login-apresentacao img {
                max-width: 230px;
                max-height: 240px;
            }

            #pagina_login_strain .login-area {
                padding: 30px 20px 50px;
                background-color: #850000;
            }

            #pagina_login_strain .login-area::before {
                display: none;
            }

            #pagina_login_strain .login-painel {
                max-width: 420px;
                min-height: 0;
                padding: 35px 25px;
            }

            #pagina_login_strain .login-titulo {
                font-size: 29px;
                margin-bottom: 25px;
            }
        }

    </style>

</head>

<body id="pagina_login_strain">
    
<!-- NAVBAR PADRÃO STRAIN -->
<header id="header_login">
    <nav class="navbar">

        <div class="marca-strain">
            <i data-lucide="train-front"></i>
            <span>STRAIN</span>
        </div>

        <button
            type="button"
            class="btn"
            onclick="window.location.href='../../index.php'"
        >
            Voltar
        </button>

    </nav>
</header>

    <main class="login-layout">

        <!-- APRESENTAÇÃO DO STRAIN -->
        <section class="login-apresentacao">

            <img
                src="../../assets/img/Tela de login atualizada.png"
                alt="Apresentação do projeto Strain"
            >

        </section>

        <!-- ÁREA DE LOGIN -->
        <section class="login-area">

            <div class="login-painel">

                <h1 class="login-titulo">
                    Fazer Login
                </h1>

                <!-- ERRO -->
                <?php if ($erro !== "") { ?>

                    <div class="login-erro" role="alert">

                        <?= htmlspecialchars(
                            $erro,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </div>

                <?php } ?>

                <!-- FORMULÁRIO -->
                <form
                    class="login-formulario"
                    method="POST"
                    action=""
                >

                    <input
                        type="hidden"
                        name="csrf_login"
                        value="<?= htmlspecialchars(
                            $_SESSION['csrf_login'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >

                    <!-- E-MAIL -->
                    <div class="login-campo">

                        <label for="login_email">
                            E-mail
                        </label>

                        <input
                            type="email"
                            id="login_email"
                            name="email"
                            placeholder="Digite seu e-mail"
                            maxlength="100"
                            autocomplete="username"
                            value="<?= htmlspecialchars(
                                $email,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required
                        >

                    </div>

                    <!-- SENHA -->
                    <div class="login-campo">

                        <label for="login_senha">
                            Senha
                        </label>

                        <input
                            type="password"
                            id="login_senha"
                            name="senha"
                            placeholder="Digite sua senha"
                            autocomplete="current-password"
                            required
                        >

                    </div>

                    <!-- BOTÃO -->
                    <button
                        type="submit"
                        class="login-botao"
                    >
                        Entrar
                    </button>

                </form>

            </div>

        </section>

    </main>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();
</script>
</body>

</html>
