<?php

namespace App\Services\Publik;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class KtpOcrService
{
    /**
     * Membaca foto KTP dan mengembalikan data yang berhasil dikenali.
     *
     * @return array{
     *     data: array<string, string|null>,
     *     warnings: array<int, string>,
     *     language: string,
     *     completeness: int
     * }
     */
    public function scan(UploadedFile $image): array
    {
        if (! (bool) config('ocr.ktp.enabled', true)) {
            throw new RuntimeException('Fitur pembacaan KTP sedang dinonaktifkan.');
        }

        $imagePath = $image->getRealPath();

        if (! is_string($imagePath) || $imagePath === '' || ! is_file($imagePath)) {
            throw new RuntimeException('File gambar KTP tidak dapat dibaca.');
        }

        $warnings = [];
        $language = $this->resolveLanguage($warnings);
        $text = $this->runTesseract($imagePath, $language);
        $data = $this->parseKtpText($text, $warnings);

        $filled = collect($data)
            ->filter(static fn (mixed $value): bool => is_string($value) && trim($value) !== '')
            ->count();

        $completeness = (int) round(($filled / max(count($data), 1)) * 100);

        if (($data['nik'] ?? null) === null) {
            $warnings[] = 'NIK belum terbaca. Periksa foto dan isi NIK secara manual.';
        }

        if (($data['nama'] ?? null) === null) {
            $warnings[] = 'Nama belum terbaca. Isi nama secara manual.';
        }

        return [
            'data' => $data,
            'warnings' => array_values(array_unique($warnings)),
            'language' => $language,
            'completeness' => $completeness,
        ];
    }

    private function runTesseract(
        string $imagePath,
        string $language
    ): string {
        $binary = trim((string) config('ocr.ktp.binary', 'tesseract'));

        if ($binary === '') {
            throw new RuntimeException('Path binary Tesseract belum dikonfigurasi.');
        }

        $process = new Process([
            $binary,
            $imagePath,
            'stdout',
            '-l',
            $language,
            '--oem',
            (string) config('ocr.ktp.ocr_engine_mode', 1),
            '--psm',
            (string) config('ocr.ktp.page_segmentation_mode', 6),
            '-c',
            'preserve_interword_spaces=1',
        ]);

        $process->setTimeout(
            max((int) config('ocr.ktp.timeout', 30), 5)
        );

        try {
            $process->run();
        } catch (\Throwable $exception) {
            throw new RuntimeException(
                'Tesseract tidak dapat dijalankan. Pastikan aplikasi OCR sudah terpasang dan path binary benar.',
                previous: $exception
            );
        }

        if (! $process->isSuccessful()) {
            $error = trim($process->getErrorOutput());

            throw new RuntimeException(
                $error !== ''
                    ? 'Proses OCR gagal: ' . Str::limit($error, 300)
                    : 'Proses OCR gagal tanpa pesan kesalahan.'
            );
        }

        $output = trim($process->getOutput());

        if ($output === '') {
            throw new RuntimeException(
                'Tidak ada teks yang berhasil dibaca dari foto KTP.'
            );
        }

        return $output;
    }

    /**
     * Memilih bahasa yang benar-benar tersedia pada instalasi Tesseract.
     *
     * @param array<int, string> $warnings
     */
    private function resolveLanguage(array &$warnings): string
    {
        $binary = trim((string) config('ocr.ktp.binary', 'tesseract'));
        $configured = trim((string) config('ocr.ktp.language', 'ind+eng'));
        $fallback = trim((string) config('ocr.ktp.fallback_language', 'eng'));

        $process = new Process([
            $binary,
            '--list-langs',
        ]);

        $process->setTimeout(10);

        try {
            $process->run();
        } catch (\Throwable $exception) {
            throw new RuntimeException(
                'Tesseract tidak ditemukan. Instal Tesseract dan atur TESSERACT_PATH pada file .env.',
                previous: $exception
            );
        }

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                'Tesseract tidak dapat menampilkan daftar bahasa. Periksa instalasi Tesseract.'
            );
        }

        $available = collect(preg_split('/\R/u', $process->getOutput()) ?: [])
            ->map(static fn (string $line): string => trim($line))
            ->filter(static function (string $line): bool {
                return $line !== ''
                    && ! Str::startsWith($line, 'List of available languages');
            })
            ->values()
            ->all();

        $requested = array_values(array_filter(
            array_map('trim', explode('+', $configured))
        ));

        $usable = array_values(array_intersect($requested, $available));

        if ($usable !== []) {
            if (count($usable) < count($requested)) {
                $warnings[] = sprintf(
                    'Sebagian model bahasa OCR tidak tersedia. Sistem menggunakan: %s.',
                    implode('+', $usable)
                );
            }

            return implode('+', $usable);
        }

        if ($fallback !== '' && in_array($fallback, $available, true)) {
            $warnings[] = sprintf(
                'Model bahasa "%s" tidak tersedia. Sistem menggunakan model "%s".',
                $configured,
                $fallback
            );

            return $fallback;
        }

        throw new RuntimeException(
            'Model bahasa OCR tidak tersedia. Instal data bahasa Indonesia dan Inggris untuk Tesseract.'
        );
    }

    /**
     * Mengubah teks OCR KTP menjadi struktur data formulir.
     *
     * @param array<int, string> $warnings
     * @return array<string, string|null>
     */
    private function parseKtpText(
        string $text,
        array &$warnings
    ): array {
        $lines = $this->normalizeLines($text);

        $nik = $this->extractNik($lines);
        $nama = $this->extractLabelValue($lines, [
            '/^\s*NAMA\b\s*[:\-]?\s*/iu',
        ]);

        $birthLine = $this->extractLabelValue($lines, [
            '/^\s*TEMPAT\s*\/?\s*TGL\.?\s*LAHIR\b\s*[:\-]?\s*/iu',
            '/^\s*TEMPAT\s+DAN\s+TANGGAL\s+LAHIR\b\s*[:\-]?\s*/iu',
        ]);

        [$birthPlace, $birthDate] = $this->parseBirthLine($birthLine);

        $genderLine = $this->extractMatchingLine($lines, [
            '/JENIS\s*KELAMIN/iu',
        ]);

        $gender = null;

        if ($genderLine !== null) {
            if (preg_match('/PEREMPUAN/iu', $genderLine) === 1) {
                $gender = 'Perempuan';
            } elseif (preg_match('/LAKI\s*[- ]?\s*LAKI|LAKI/iu', $genderLine) === 1) {
                $gender = 'Laki-laki';
            }
        }

        $address = $this->extractLabelValue($lines, [
            '/^\s*ALAMAT\b\s*[:\-]?\s*/iu',
        ]);

        $rtRw = $this->extractLabelValue($lines, [
            '/^\s*RT\s*\/\s*RW\b\s*[:\-]?\s*/iu',
            '/^\s*RT\s*[- ]?\s*RW\b\s*[:\-]?\s*/iu',
        ]);

        $village = $this->extractLabelValue($lines, [
            '/^\s*KEL\.?\s*\/\s*DESA\b\s*[:\-]?\s*/iu',
            '/^\s*KELURAHAN\s*\/\s*DESA\b\s*[:\-]?\s*/iu',
            '/^\s*KELURAHAN\b\s*[:\-]?\s*/iu',
            '/^\s*DESA\b\s*[:\-]?\s*/iu',
        ]);

        $district = $this->extractLabelValue($lines, [
            '/^\s*KECAMATAN\b\s*[:\-]?\s*/iu',
        ]);

        $occupation = $this->extractLabelValue($lines, [
            '/^\s*PEKERJAAN\b\s*[:\-]?\s*/iu',
        ]);

        $province = $this->extractLabelValue($lines, [
            '/^\s*PROVINSI\b\s*[:\-]?\s*/iu',
        ]);

        $city = $this->extractLabelValue($lines, [
            '/^\s*(?:KABUPATEN|KOTA)\b\s*[:\-]?\s*/iu',
        ]);

        $completeAddress = collect([
            $address,
            $rtRw ? 'RT/RW ' . $rtRw : null,
            $village ? 'Kel/Desa ' . $village : null,
            $district ? 'Kecamatan ' . $district : null,
            $city,
            $province,
        ])
            ->filter(static fn (?string $value): bool => $value !== null && trim($value) !== '')
            ->unique(static fn (string $value): string => mb_strtolower($value))
            ->implode(', ');

        if ($nik !== null && strlen($nik) !== 16) {
            $warnings[] = 'NIK hasil OCR tidak terdiri dari 16 angka dan tidak digunakan.';
            $nik = null;
        }

        return [
            'nik' => $nik,
            'nama' => $this->titleCase($nama),
            'tempat_lahir' => $this->titleCase($birthPlace),
            'tanggal_lahir' => $birthDate,
            'jenis_kelamin' => $gender,
            'alamat' => $completeAddress !== ''
                ? $this->titleCase($completeAddress)
                : null,
            'rt_rw' => $rtRw,
            'desa_kel' => $this->titleCase($village),
            'kecamatan' => $this->titleCase($district),
            'kota_kab' => $this->titleCase($city),
            'provinsi' => $this->titleCase($province),
            'pekerjaan' => $this->titleCase($occupation),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function normalizeLines(string $text): array
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[^\P{C}\n\t]/u', '', $text) ?: $text;

        return collect(explode("\n", $text))
            ->map(static function (string $line): string {
                return trim(preg_replace('/\s+/u', ' ', $line) ?: $line);
            })
            ->filter(static fn (string $line): bool => $line !== '')
            ->values()
            ->all();
    }

    /**
     * @param array<int, string> $lines
     */
    private function extractNik(array $lines): ?string
    {
        foreach ($lines as $line) {
            if (preg_match('/N[1I]K/iu', $line) !== 1) {
                continue;
            }

            $candidate = preg_replace('/^.*?N[1I]K\s*[:\-]?\s*/iu', '', $line) ?: $line;
            $digits = $this->ocrCharactersToDigits($candidate);

            if (strlen($digits) >= 16) {
                return substr($digits, 0, 16);
            }
        }

        foreach ($lines as $line) {
            if (preg_match('/[0-9OQILZSBG|]{16,25}/iu', str_replace(' ', '', $line), $match) !== 1) {
                continue;
            }

            $digits = $this->ocrCharactersToDigits($match[0]);

            if (strlen($digits) >= 16) {
                return substr($digits, 0, 16);
            }
        }

        return null;
    }

    private function ocrCharactersToDigits(string $value): string
    {
        $value = mb_strtoupper($value);

        $value = strtr($value, [
            'O' => '0',
            'Q' => '0',
            'I' => '1',
            'L' => '1',
            '|' => '1',
            'Z' => '2',
            'S' => '5',
            'G' => '6',
            'B' => '8',
        ]);

        return preg_replace('/\D+/', '', $value) ?: '';
    }

    /**
     * @param array<int, string> $lines
     * @param array<int, string> $patterns
     */
    private function extractLabelValue(
        array $lines,
        array $patterns
    ): ?string {
        foreach ($lines as $index => $line) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $line) !== 1) {
                    continue;
                }

                $value = preg_replace($pattern, '', $line, 1);
                $value = trim((string) $value, " \t\n\r\0\x0B:.-");

                if ($value !== '') {
                    return $this->removeFollowingLabelText($value);
                }

                $next = $lines[$index + 1] ?? null;

                if (is_string($next) && ! $this->looksLikeLabel($next)) {
                    return trim($next);
                }
            }
        }

        return null;
    }

    /**
     * @param array<int, string> $lines
     * @param array<int, string> $patterns
     */
    private function extractMatchingLine(
        array $lines,
        array $patterns
    ): ?string {
        foreach ($lines as $line) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $line) === 1) {
                    return $line;
                }
            }
        }

        return null;
    }

    private function removeFollowingLabelText(string $value): string
    {
        $labels = [
            'GOL. DARAH',
            'GOL DARAH',
            'AGAMA',
            'STATUS PERKAWINAN',
            'PEKERJAAN',
            'KEWARGANEGARAAN',
            'BERLAKU HINGGA',
        ];

        foreach ($labels as $label) {
            $position = mb_stripos($value, $label);

            if ($position !== false && $position > 0) {
                $value = mb_substr($value, 0, $position);
            }
        }

        return trim($value, " \t\n\r\0\x0B:.-");
    }

    private function looksLikeLabel(string $line): bool
    {
        return preg_match(
            '/^(NIK|NAMA|TEMPAT|JENIS\s*KELAMIN|ALAMAT|RT\s*\/\s*RW|KEL|DESA|KECAMATAN|AGAMA|STATUS|PEKERJAAN|KEWARGANEGARAAN|BERLAKU)/iu',
            trim($line)
        ) === 1;
    }

    /**
     * @return array{0:?string,1:?string}
     */
    private function parseBirthLine(?string $birthLine): array
    {
        if ($birthLine === null || trim($birthLine) === '') {
            return [null, null];
        }

        if (
            preg_match(
                '/(?<day>\d{2})[\/\.\-](?<month>\d{2})[\/\.\-](?<year>\d{4})/',
                $birthLine,
                $match
            ) !== 1
        ) {
            return [trim($birthLine), null];
        }

        $day = (int) $match['day'];
        $month = (int) $match['month'];
        $year = (int) $match['year'];

        $date = checkdate($month, $day, $year)
            ? sprintf('%04d-%02d-%02d', $year, $month, $day)
            : null;

        $place = trim(str_replace($match[0], '', $birthLine), " \t\n\r\0\x0B,;:-");

        return [
            $place !== '' ? $place : null,
            $date,
        ];
    }

    private function titleCase(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        return mb_convert_case(
            mb_strtolower($value),
            MB_CASE_TITLE,
            'UTF-8'
        );
    }
}
