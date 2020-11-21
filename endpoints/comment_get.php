<?php

// Define a função que lida com os dados da requisição.
function api_comment_get($request) {
    $post_id = $request["id"];

    // Puxa todos os comentários do post.
    $comments = get_comments([
        "post_id" => $post_id
    ]);

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($comments);
}

// Função para registrar o endpoint da API.
function register_api_comment_get() {
    // Função que registra o endpoint.
    register_rest_route('api', '/comment/(?P<id>[0-9]+)', [
        // Define o metódo como READABLE - Correspondente ao GET
        'methods' => WP_REST_Server::READABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_comment_get'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_comment_get');
