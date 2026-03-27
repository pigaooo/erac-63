<?php

return [
    'timezone' => 'America/Sao_Paulo',

    'encerramento_online' => '2026-06-20',

    'lotes' => [
        [
            'id' => 'primeiro-lote',
            'label' => '1º lote',
            'valor' => 'R$ 125,00',
            'inicio' => '2026-03-30',
            'fim' => '2026-04-30',
            'badge' => 'primary',
            'descricao' => 'A confirmação é enviada após a validação do pagamento.',
        ],
        [
            'id' => 'segundo-lote',
            'label' => '2º lote',
            'valor' => 'R$ 135,00',
            'inicio' => '2026-05-01',
            'fim' => '2026-05-20',
            'badge' => 'secondary',
            'descricao' => 'Pagamento via PIX com validação posterior.',
        ],
        [
            'id' => 'terceiro-lote',
            'label' => '3º lote',
            'valor' => 'R$ 140,00',
            'inicio' => '2026-05-21',
            'fim' => '2026-06-20',
            'badge' => 'accent',
            'descricao' => 'Último período para inscrição on-line com validação posterior.',
        ],
    ],
];
