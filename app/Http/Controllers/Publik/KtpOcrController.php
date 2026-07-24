<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Services\Publik\KtpOcrService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class KtpOcrController extends Controller
{
    public function __construct(
        protected KtpOcrService $ktpOcrService
    ) {}

    /**
     * Membaca foto KTP tanpa menyimpan file sementara ke database.
     */
    public function scan(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'file_identitas' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png',
                    'max:5120',
                ],
            ],
            [
                'file_identitas.required' => 'Pilih foto KTP terlebih dahulu.',
                'file_identitas.image' => 'File OCR harus berupa gambar.',
                'file_identitas.max' => 'Ukuran foto KTP maksimal 5 MB.',
            ]
        );

        try {
            $result = $this->ktpOcrService->scan(
                $validated['file_identitas']
            );

            return response()->json([
                'success' => true,
                'message' => 'Foto KTP berhasil dibaca. Periksa kembali seluruh hasil sebelum mengirim formulir.',
                'data' => $result['data'],
                'warnings' => $result['warnings'],
                'language' => $result['language'],
                'completeness' => $result['completeness'],
            ]);
        } catch (RuntimeException $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getMessage(),
                ],
                422
            );
        } catch (Throwable $exception) {
            Log::error(
                'Terjadi kesalahan saat menjalankan OCR KTP.',
                [
                    'exception' => $exception::class,
                    'message' => $exception->getMessage(),
                ]
            );

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membaca foto KTP. Silakan isi formulir secara manual.',
                ],
                500
            );
        }
    }
}
