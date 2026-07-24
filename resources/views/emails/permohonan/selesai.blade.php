<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Informasi Publik Selesai</title>
</head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f5f9;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                    style="max-width:680px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="padding:28px 32px;background:#166534;color:#ffffff;text-align:center;">
                            <div style="font-size:24px;font-weight:700;">PPID KOTA BATU</div>
                            <div style="margin-top:6px;font-size:14px;color:#dcfce7;">Permohonan Informasi Publik Selesai</div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.7;">
                                Yth. <strong>{{ $permohonan->namaWarga() }}</strong>,
                            </p>

                            <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#334155;">
                                Permohonan informasi publik Anda telah selesai diproses. Jawaban final dapat dilihat melalui tiket berikut.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="border-collapse:collapse;background:#f0fdf4;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="padding:12px 16px;border-bottom:1px solid #bbf7d0;font-size:14px;font-weight:700;width:190px;">Nomor Tiket</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #bbf7d0;font-size:14px;">{{ $permohonan->no_pemohon }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;border-bottom:1px solid #bbf7d0;font-size:14px;font-weight:700;">Status</td>
                                    <td style="padding:12px 16px;border-bottom:1px solid #bbf7d0;font-size:14px;font-weight:700;color:#166534;">Selesai</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;font-size:14px;font-weight:700;vertical-align:top;">Jawaban Final</td>
                                    <td style="padding:12px 16px;font-size:14px;line-height:1.7;white-space:pre-line;">{{ $permohonan->jawaban ?: 'Jawaban tersedia pada file yang dapat dibuka melalui halaman tiket.' }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:28px;text-align:center;">
                                <a href="{{ $detailUrl }}"
                                    style="display:inline-block;background:#166534;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:13px 24px;border-radius:10px;">
                                    Buka Jawaban Final
                                </a>
                            </div>

                            <p style="margin:28px 0 0;font-size:13px;line-height:1.6;color:#64748b;">
                                Tautan tersebut juga dapat digunakan untuk membuka file jawaban apabila tersedia.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:22px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:center;color:#64748b;font-size:12px;line-height:1.6;">
                            PPID Pemerintah Kota Batu<br>
                            Gedung Balai Kota Among Tani, Jalan Panglima Sudirman Nomor 507, Kota Batu
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
