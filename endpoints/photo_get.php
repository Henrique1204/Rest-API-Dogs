<?php

function photo_data($post) {
    // Busca os campos personalizados do post.
    $post_meta = get_post_meta($post->ID);
    // Busca a imagem do post.
    $src = wp_get_attachment_image_src($post_meta["img"][0], "large")[0];
    // Busca os dados do usuário que fez o post.
    $user = get_userdata($post->post_author);
    // Busca os comentários do post.
    $total_comments = get_comments_number($post->ID);

    // Retorna os dados estruturados.
    return [
        "id" => $post->ID,
        "author" => $user->user_login,
        "title" => $post->post_title,
        "date" => $post->post_date,
        "src" => $src,
        "peso" => $post_meta["peso"][0],
        "idade" => $post_meta["idade"][0],
        "acessos" => $post_meta["acessos"][0],
        "total_comments" => $total_comments
    ];
}

// Define a função que lida com os dados da requisição.
function api_photo_get($request) {
    $post_id = $request["id"];
    // Busca o post.
    $post = get_post($post_id);

    // Testa se o post solicitado existe.
    if (!isset($post)) {
        $response = new WP_Error("error", "Post não encontrado.", [
            "status" => 404
        ]);
    
        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Estrutura os dados para o retorno da requisição.
    $photo = photo_data($post);
    // Altera o valor de acesso do post a cada requisição.
    $photo["acessos"] = (int) $photo["acessos"] + 1;
    // Atualiza o valor de acessos no banco.
    update_post_meta($post->ID, "acessos", $photo["acessos"]);

    // Busca os comentários da foto.
    $comments = get_commenets([
        "post_id" => $post_id,
        "order" => "ASC"
    ]);

    $response = [
        "photo" => $photo,
        "comments" => $comments
    ];

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($response);
}

// Função para registrar o endpoint da API.
function register_api_photo_get() {
    // Função que registra o endpoint.
    register_rest_route('api', '/photo/(?P<id>[0-9]+)', [
        // Define o metódo como READABLE - Correspondente ao GET
        'methods' => WP_REST_Server::READABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_photo_get'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_photo_get');

// Define a função que lida com os dados da requisição.
function api_photos_get($request) {
    $_total = sanitize_text_field($request["_total"]) ?: 6;
    $_page = sanitize_text_field($request["_page"]) ?: 1;
    $_user = sanitize_text_field($request["_user"]) ?: 0;

    if (!is_numeric($_user)) {
        $user = get_user_by("login", $_user);
    
        if (!$user) {
            $response = new WP_Error("error", "Usuário não encontrado.", [
                "status" => 404
            ]);
        
            // Retorna os dados no formato de "response" de REST API.
            return rest_ensure_response($response);
        }

        $_user = $user->ID;
    }

    // Argumentos para serem usados na busca dos posts.
    $args = [
        "post_type" => "post",
        "author" => 0,
        "posts_per_page" => $_total,
        "paged" => $_page
    ];

    // Busca dos posts.
    $query = new WP_Query($args);
    // Posts.
    $posts = $query->posts;
    $photos = [];

    // Verifica se existe posts.
    if (isset($posts)) {
        foreach ($posts as $post) {
            $photos[] = photo_data($post);
        }
    }

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($photos);
}

// Função para registrar o endpoint da API.
function register_api_photos_get() {
    // Função que registra o endpoint.
    register_rest_route('api', '/photo', [
        // Define o metódo como READABLE - Correspondente ao GET
        'methods' => WP_REST_Server::READABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_photos_get'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_photos_get');
