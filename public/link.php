<?php
$target = realpath(__DIR__ . '/../storage/app/public');
$link = __DIR__ . '/storage';

if (!$target || !is_dir($target)) {
    echo "Pasta alvo não encontrada: " . ($target ?: __DIR__ . '/../storage/app/public');
    exit(1);
}

if (is_link($link) || file_exists($link)) {
    echo "Já existe: $link";
    exit(0);
}

if (symlink($target, $link)) {
    echo "Link simbólico criado com sucesso: $link => $target";
    exit(0);
}

$err = error_get_last();
echo "Falha ao criar o link simbólico. Erro: " . ($err['message'] ?? 'desconhecido');
exit(1);
?>