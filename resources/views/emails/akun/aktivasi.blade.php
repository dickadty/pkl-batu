<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun PPID Kota Batu</title>
</head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f5f9;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:#1d4ed8;padding:28px 32px;color:#ffffff;">
                            <div style="font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;opacity:.9;">
                                PPID Kota Batu
                            </div>
                            <h1 style="margin:10px 0 0;font-size:26px;line-height:1.3;">
                                Aktivasi Akun Layanan Warga
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.7;">
                                Yth. <strong>{{ $user->nama }}</strong>,
                            </p>

                            <p style="margin:0 0 20px;font-size:15px;line-height:1.7;color:#334155;">
                                Akun layanan PPID Anda telah dibuat. Gunakan alamat email berikut sebagai username, lalu buat password melalui tombol aktivasi.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#64748b;">
                                            Username
                                        </div>
                                        <div style="margin-top:6px;font-size:16px;font-weight:700;color:#1e3a8a;word-break:break-all;">
                                            {{ $user->email }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 auto 24px;">
                                <tr>
                                    <td align="center" style="border-radius:10px;background:#1d4ed8;">
                                        <a href="{{ $activationUrl }}" style="display:inline-block;padding:14px 24px;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;">
                                            Aktifkan Akun dan Buat Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#475569;">
                                Tautan ini hanya dapat digunakan satu kali dan berlaku selama 24 jam.
                            </p>

                            <p style="margin:0;font-size:13px;line-height:1.7;color:#64748b;word-break:break-all;">
                                Apabila tombol tidak dapat dibuka, salin tautan berikut ke browser:<br>
                                <a href="{{ $activationUrl }}" style="color:#1d4ed8;">{{ $activationUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:12px;line-height:1.6;color:#64748b;">
                            Email ini dikirim otomatis oleh sistem PPID Kota Batu. Jangan membalas email ini.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
