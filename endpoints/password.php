<?php

// Define a função que lida com os dados da requisição.
function api_password_lost($request) {
    $login = $request["login"];
    $url = $request["url"];

    // Testa se o login está vazio.
    if (empty($login)) {
        $reponse = new WP_Erro("error", "Informe o email ou login.", [
            "status" => 406
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Puxando o usuário pelo email.
    $user = get_user_by("email", $login);

    // Testando se o usuário puxado pelo email existe.
    if (empty($user)) {
        // Se não existir tenta puxar o usuário pelo nome de login.
        $user = get_user_by("login", $login);
    }

    // Testando se o usuário puxado pelo login existe.
    if (empty($user)) {
        $reponse = new WP_Erro("error", "Usuário não existe.", [
            "status" => 401
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Puxando o nome de login do usuário.
    $user_login = $user->user_login;
    // Puxando o email do usuário.
    $user_email = $user->user_email;

    // Gerando a chave para recuperação de senha.
    $key = get_password_reset_key($user);

    // Escrevendo a mensagem que irá no email.
    $message = "Utilize o link abaixo para resertar a sua senha: \r\n";
    // Criando a url para redicionar o usuário para área de recuperação de senha.
    $url = esc_url_raw($url."/?key=$key&login=".rawurlencode($user_login)."\r\n");
    // Juntando todo o conteúdo do email.
    $body = $message . $url;

    // Função que envia o e-mail.
    wp_email($user_email, "Password Reset", $body);

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response("E-mail enviado.");
}

// Função para registrar o endpoint da API.
function register_api_password_lost() {
    // Função que registra o endpoint.
    register_rest_route('api', '/password/lost', [
        // Define o metódo como CREATABLE - Correspondente ao POST
        'methods' => WP_REST_Server::CREATABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_password_lost'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_password_lost');

// Define a função que lida com os dados da requisição.
function api_password_reset($request) {
    $login = $request["login"];
    $password = $request["password"];
    $key = $request["key"];

    // Puxa o usuário pelo login.
    $user = get_user_by("login", $login);

    // Testando se o usuário existe.
    if (empty($user)) {
        $reponse = new WP_Erro("error", "Usuário não existe.", [
            "status" => 401
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Confere se a chave de recuperação e o login são válidos.
    // Caso não sejam válidos, o retorno é um erro de WP.
    $check_key = check_password_reset_key($key, $login);

    // Testa se o valor de retorno foi um erro de WP.
    if (is_wp_error($check_key)) {
        $reponse = new WP_Erro("error", "Token expirado.", [
            "status" => 401
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Função que reseta a senha.
    reset_password($user, $password);

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response("Senha alterada.");
}

// Função para registrar o endpoint da API.
function register_api_password_reset() {
    // Função que registra o endpoint.
    register_rest_route('api', '/password/lost', [
        // Define o metódo como CREATABLE - Correspondente ao POST
        'methods' => WP_REST_Server::CREATABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_password_reset'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_password_reset');
