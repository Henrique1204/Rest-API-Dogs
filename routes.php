<?php

// Remove as credenciais do usuário da API.
// Pode gerar erros na interface do WP, por isso só use caso não for utlizar ela.
// remove_action('rest_api_init', 'create_initial_rest_routes', 99);

// Remove os endpoints da API que dão acesso as crednciaisi do usuário.
add_filter("rest_endpoints", function ($endpoints) {
    unset($endpoints["/wp/v2/users"]);
    unset($endpoints["/wp/v2/users/(?P<id>[\d]+)"]);

    return $endpoints;
});

// Pega o caminho base do diretório.
$dirbase = get_template_directory();
// Adiciona o arquivo user_post pra dentro deste arquivo.
require_once $dirbase."/endpoints/user_post.php";
// Adiciona o arquivo user_get pra dentro deste arquivo.
require_once $dirbase."/endpoints/user_get.php";

// Adiciona o arquivo photo_get pra dentro deste arquivo.
require_once $dirbase."/endpoints/photo_get.php";
// Adiciona o arquivo photo_post pra dentro deste arquivo.
require_once $dirbase."/endpoints/photo_post.php";
// Adiciona o arquivo photo_delete pra dentro deste arquivo.
require_once $dirbase."/endpoints/photo_delete.php";

// Adiciona o arquivo comment_post pra dentro deste arquivo.
require_once $dirbase."/endpoints/comment_post.php";
// Adiciona o arquivo comment_get pra dentro deste arquivo.
require_once $dirbase."/endpoints/comment_get.php";

// Adiciona o arquivo password pra dentro deste arquivo.
require_once $dirbase."/endpoints/password.php";

// Adiciona o arquivo stats_get pra dentro deste arquivo.
require_once $dirbase."/endpoints/stats_get.php";
