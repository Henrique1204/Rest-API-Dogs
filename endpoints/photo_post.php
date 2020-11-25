<?php

// Define a função que lida com os dados da requisição.
function api_photo_post($request) {
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

    $nome = sanitize_text_field($request["nome"]);
    $peso = sanitize_text_field($request["peso"]);
    $idade = sanitize_text_field($request["idade"]);
    // Puxa a imagem que foi passada na requisição.
    $files = $request->get_file_params();

    // Testa se algum dos campos foi vazio.
    if (empty($nome) || empty($peso) || empty($idade) || empty($files)) {
        // Cria um novo Objeto de Erro e retorna ele como resposta da requisição.
        $response = new WP_Error("error", "Dados incompletos", ["status" => 422]);
        // Retorna os dados no formato de "response" de REST API.
        return rest_ensure_response($response);
    }

    // Cria os dados para serem passados na função que cria o post.
    $response = [
        // Define o autor do post.
        "post_author" => $user_id,
        // Define o tipo de post.
        "post_type" => "post",
        // Define se o post foi publicado.
        "post_status" => "publish",
        // Define o titulo do post.
        "post_title" => $nome,
        // Define o conteúdo do post.
        "post_content" => $nome,
        // Insere a imagem ao post.
        "files" => $files,
        // Define campos personalizados, no caso campos que não estão nativamente em um post do WP.
        "meta_input" => [
            // Chave do campo => Valor.
            "peso" => $peso,
            // Chave do campo => Valor.
            "idade" => $idade,
            // Chave do campo => Valor.
            "acessos" => 0
        ]
    ];

    // Cria e insere o post no banco de dados apartir do que foi defino ali em cima.
    // O retorno da função é um id.
    $post_id = wp_insert_post($response);

    // Pela função de media_handle_upload ser pesada, você precisa fazer a requisição dela.
    // Pois ela não vem nativamente com o WP.
    require_once ABSPATH."wp-admin/includes/image.php";
    require_once ABSPATH."wp-admin/includes/file.php";
    require_once ABSPATH."wp-admin/includes/media.php";

    // Função que sobe a imagem pro WP.
    // O retorno da função é o id da imagem.
    $photo_id = media_handle_upload("img", $post_id);
    // Atualiza o post para imagem ser carregada.
    update_post_meta($post_id, "img", $photo_id);

    // Retorna os dados no formato de "response" de REST API.
    return rest_ensure_response($response);
}

// Função para registrar o endpoint da API.
function register_api_photo_post() {
    // Função que registra o endpoint.
    register_rest_route('api', '/photo', [
        // Define o metódo como Creatable - Correspondente ao POST
        'methods' => WP_REST_Server::CREATABLE,
        // Define qual função será executada na hora de 
        'callback' => 'api_photo_post'
    ]);
}

// Faz com que a função de registro ocorra na hora em que a api inicia.
add_action('rest_api_init', 'register_api_photo_post');
