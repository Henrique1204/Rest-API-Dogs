<?php

// Define a função que lida com os dados da requisição.
function api_comment_post($request) {
    // Busca os dados do usuáiro que está logado.
    $user = wp_get_current_user();
    $user_id = $user->ID;

    // Testa se o tem algum usuário logado.
    if ($user_id === 0) {
        $reponse = new WP_Erro("error", "Sem permissão.", [
            "status" => 401
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    $comment = sanitize_text_field($request["content"]);
    $post_id = $request["id"];

    // Testa se o comentário tá vazio
    if (empty($comment)) {
        $reponse = new WP_Erro("error", "Dados incompletos.", [
            "status" => 422
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Dados para inserir um comentário.
    $reponse = [
        // Define o autor.
        "comment_author" => $user->user_login,
        // Define o conteúdo.
        "comment_content" => $comment,
        // Define o id do comentário.
        "comment_post_ID" => $post_id,
        // Defiine o id do usuário.
        "user_id" => $user_id
    ];

    // Função que insere o comentário no post.
    // O retorno é o id do comentário.
    $comment_id = wp_insert_comment($response);
    // Busca o comentário.
    $comment = get_comment($comment_id);

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($comment);
}

// Função para registrar o endpoint da API.
function register_api_comment_post() {
    // Função que registra o endpoint.
    register_rest_route('api', '/comment/(?P<id>[0-9]+)', [
        // Define o metódo como CREATABLE - Correspondente ao POST
        'methods' => WP_REST_Server::CREATABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_comment_post'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_comment_post');
