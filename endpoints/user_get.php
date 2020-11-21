<?php

// Define a função que lida com os dados da requisição.
function api_user_get($request) {
    // Busca os dados do usuáiro que está logado.
    $user = wp_get_current_user();
    $user_id = $user->ID;

    // Testa se o usuário passado foi inválido.
    if ($user_id === 0) {
        // Se tiver sido retorna esse erro.
        $response = new WP_Error("error", "Usuário não possui permissão.", [
            "status" => 401
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    $response = [
        "id" => $user_id,
        "username" => $user->user_login,
        "nome" => $user->display_name,
        "email" => $user->user_email
    ];

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($response);
}

// Função para registrar o endpoint da API.
function register_api_user_get() {
    // Função que registra o endpoint.
    register_rest_route('api', '/user', [
        // Define o metódo como READABLE - Correspondente ao GET
        'methods' => WP_REST_Server::READABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_user_get'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_user_get');
