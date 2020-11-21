<?php

// Define a função que lida com os dados da requisição.
function api_stats_get($request) {
    // Puxa o usuário logado no momento.
    $user = wp_get_current_user();
    $user_id = $user->ID;

    // Testa se o usuário tem permissão.
    if ($user_id === 0) {
        // Se tiver sido retorna esse erro.
        $response = new WP_Error("error", "Usuário não possui permissão.", [
            "status" => 401
        ]);

        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Argumentos para serem usados na busca dos posts.
    $args = [
        // Tipo de post.
        "post_type" => "post",
        // Filtra os posts só desse autor.
        "author" => $user_id,
        // Trás todos os posts.
        "posts_per_page" => -1
    ];

    // Busca dos posts.
    $query = new WP_Query($args);
    $posts = $query->posts;

    $stats = [];

    if ($posts) {
        foreach ($posts as $post) {
            $stats[] = [
                "id" => $post->ID,
                "title" => $post->post_title,
                "acessos" => get_post_meta($post->ID, "acessos", true)
            ];
        }
    }

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($stats);
}

// Função para registrar o endpoint da API.
function register_api_stats_get() {
    // Função que registra o endpoint.
    register_rest_route('api', '/stats', [
        // Define o metódo como READABLE - Correspondente ao GET
        'methods' => WP_REST_Server::READABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_stats_get'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_stats_get');
