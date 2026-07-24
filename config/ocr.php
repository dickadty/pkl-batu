<?php

return [
    'ktp' => [
        'enabled' => env('KTP_OCR_ENABLED', true),

        // Gunakan path absolut di server produksi bila binary tidak terbaca dari PATH.
        'binary' => env('TESSERACT_PATH', 'tesseract'),

        // Bahasa Indonesia menggunakan kode "ind" pada Tesseract.
        'language' => env('TESSERACT_LANG', 'ind+eng'),
        'fallback_language' => env('TESSERACT_FALLBACK_LANG', 'eng'),

        // PSM 6 cocok untuk satu blok teks seperti kartu identitas.
        'page_segmentation_mode' => (int) env('TESSERACT_PSM', 6),
        'ocr_engine_mode' => (int) env('TESSERACT_OEM', 1),
        'timeout' => (int) env('TESSERACT_TIMEOUT', 30),
    ],
];
