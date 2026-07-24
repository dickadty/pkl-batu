<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Permohonan Informasi</title>
</head>
<body style="margin:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f5f9;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:#1d4ed8;padding:28px 32px;color:#ffffff;">
                            <div style="font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;opacity:.9;">
                                PPID Kota Batu
                            </div>
                            <h1 style="margin:10px 0 0;font-size:26px;line-height:1.3;">
                                Permohonan Informasi Diterima
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.7;">
                                Yth. <strong>{{ $permohonan->namaWarga() }}</strong>,
                            </p>

                            <p style="margin:0 0 24px;font-size:15px;line-height:1.7;color:#334155;">
                                Permohonan informasi publik Anda telah diterima dan tercatat dalam sistem PPID Kota Batu.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;width:42%;font-size:13px;font-weight:700;color:#64748b;border-bottom:1px solid #e2e8f0;">
                                        Nomor Tiket
                                    </td>
                                    <td style="padding:12px 16px;font-size:14px;font-weight:700;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                        {{ $permohonan->no_pemohon }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#64748b;border-bottom:1px solid #e2e8f0;">
                                        Tanggal
                                    </td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                        {{ optional($permohonan->tanggal)->format('d-m-Y') ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#64748b;border-bottom:1px solid #e2e8f0;">
                                        Status
                                    </td>
                                    <td style="padding:12px 16px;font-size:14px;color:#0f172a;border-bottom:1px solid #e2e8f0;">
                                        {{ $permohonan->status }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 16px;background:#f8fafc;font-size:13px;font-weight:700;color:#64748b;vertical-align:top;">
                                        Rincian
                                    </td>
                                    <td style="padding:12px 16px;font-size:14px;line-height:1.6;color:#0f172a;">
                                        {{ $permohonan->rincian }}
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 auto 28px;">
                                <tr>
                                    <td align="center" style="border-radius:10px;background:#1d4ed8;">
                                        <a href="{{ $detailUrl }}" style="display:inline-block;padding:14px 24px;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;">
                                            Lihat Detail Permohonan
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            @if ($activationUrl)
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;">
                                    <tr>
                                        <td style="padding:20px;">
                                            <h2 style="margin:0 0 10px;font-size:17px;color:#1e3a8a;">
                                                Akun warga otomatis telah dibuat
                                            </h2>

                                            <p style="margin:0 0 12px;font-size:14px;line-height:1.7;color:#334155;">
                                                Username akun Anda adalah alamat email berikut:
                                            </p>

                                            <div style="margin:0 0 18px;font-size:15px;font-weight:700;color:#1e3a8a;word-break:break-all;">
                                                {{ $permohonan->emailWarga() }}
                                            </div>

                                            <p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#334155;">
                                                Buat password sendiri agar permohonan berikutnya dapat diajukan melalui akun dan seluruh riwayat dapat dilihat dalam satu halaman.
                                            </p>

                                            <table role="presentation" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="center" style="border-radius:10px;background:#0f766e;">
                                                        <a href="{{ $activationUrl }}" style="display:inline-block;padding:13px 20px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:700;">
                                                            Aktifkan Akun dan Buat Password
                                                        </a>
                                                    </td>
                                                </tr>
                                            </table>

                                            <p style="margin:16px 0 0;font-size:12px;line-height:1.6;color:#64748b;">
                                                Tautan aktivasi berlaku selama 24 jam dan hanya dapat digunakan satu kali.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin:0;font-size:13px;line-height:1.7;color:#64748b;word-break:break-all;">
                                Tautan pelacakan tiket:<br>
                                <a href="{{ $detailUrl }}" style="color:#1d4ed8;">{{ $detailUrl }}</a>
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
