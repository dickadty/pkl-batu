<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Jadwal Pemeriksaan Pengingat
    |--------------------------------------------------------------------------
    */

    'reminder_time' => env(
        'PERMOHONAN_REMINDER_TIME',
        '07:00'
    ),

    /*
    |--------------------------------------------------------------------------
    | Batas Pengingat dalam Hari Kalender
    |--------------------------------------------------------------------------
    |
    | baru:
    | Permohonan belum diteruskan oleh Admin Utama.
    |
    | diteruskan:
    | Permohonan belum dijawab oleh PPID Pembantu.
    |
    | menunggu_validasi:
    | Jawaban belum divalidasi oleh Admin Utama.
    |
    | revisi:
    | Revisi belum ditindaklanjuti PPID Pembantu.
    |
    */

    'reminder' => [
        'baru' => [
            1,
            3,
        ],

        'diteruskan' => [
            3,
            5,
        ],

        'menunggu_validasi' => [
            1,
            3,
        ],

        'revisi' => [
            1,
            3,
        ],
    ],
];
