<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'service' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'rating' => [
                    'required',
                    'integer',
                    'between:1,5',
                ],

                'message' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'service.required' =>
                'Silakan pilih layanan yang ingin dinilai.',

                'rating.required' =>
                'Silakan berikan penilaian.',

                'rating.between' =>
                'Penilaian harus antara 1 sampai 5.',

                'name.max' =>
                'Nama maksimal 100 karakter.',

                'message.max' =>
                'Kritik dan saran maksimal 1000 karakter.',
            ]
        );


        $validated['respondent_hash'] = hash_hmac(
            'sha256',
            ($request->ip() ?? 'unknown')
                . '|'
                . ($request->userAgent() ?? 'unknown'),
            config('app.key')
        );

        Survey::create($validated);

        return redirect()
            ->to(url()->previous() . '#survey')
            ->with(
                'survey_success',
                'Terima kasih. Penilaian Anda berhasil dikirim.'
            );
    }
}
