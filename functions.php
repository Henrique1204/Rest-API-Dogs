<?php

// Pega o caminho base do diretório.
$dirbase = get_template_directory();
// Adiciona o arquivo routes pra dentro deste arquivo.
require_once $dirbase."/routes.php";

// Altera a largura da opção de tamanho large.
update_option("large_size_w", 1000);
// Altera a altura da opção de tamanho large.
update_option("large_size_h", 1000);
// Altera a opção de crop para cropar a imagem adicionada no tamanho large.
update_option("large_crop", 1);

// Função para alterar a url da API.
function change_api() {
    return 'json';
}

// Modifica o "prefixo" da url da API para o que definimos na função acima.
add_filter('rest_url_prefix', 'change_api');

function expire_token() {
    return time() + (60 * 60 * 24);
}

add_action('jwt_auth_expire', 'expire_token');
