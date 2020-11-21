<?php

// Define a função que lida com os dados da requisição.
function api_user_post($request) {
    $email = sanitize_email($request['email']);
    $username = sanitize_text_field($request['username']);
    $password = $request['password'];

    // Testa se algum dos campos foi vazio.
    if (empty($email) || empty($username) || empty($password)) {
        // Cria um novo Objeto de Erro e retorna ele como resposta da requisição.
        $response = new WP_Error("error", "Dados incompletos", ["status" => 422]);
        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Testa se o usuário e email já existem.
    if (username_exists($username) || email_exists($email)) {
        // Cria um novo Objeto de Erro e retorna ele como resposta da requisição.
        $response = new WP_Error("error", "Usuario já cadastrado.", ["status" => 403]);
        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Função que insere um novo usuário no WP.
    $response = wp_insert_user([
        // Define o login do usuário.
        'user_login' => $username,
        // Define o email do usuário.
        'user_email' => $email,
        // Define a senha do usuário.
        'user_pass' => $password,
        // Define os privilégios do usuário.
        'role' => 'subscriber'
    ]);

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($response);
}

// Função para registrar o endpoint da API.
function register_api_user_post() {
    // Função que registra o endpoint.
    register_rest_route('api', '/user', [
        // Define o metódo como Creatable - Correspondente ao POST
        'methods' => WP_REST_Server::CREATABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_user_post'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_user_post');
