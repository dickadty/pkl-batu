<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Permintaan Informasi Tidak Dapat Diproses</title>
</head>

<body
    style="
        margin: 0;
        padding: 0;
        background-color: #f1f5f9;
        font-family: Arial, Helvetica, sans-serif;
        color: #334155;
    ">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="
            width: 100%;
            background-color: #f1f5f9;
            padding: 30px 15px;
        ">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"
                    style="
                        width: 100%;
                        max-width: 600px;
                        background-color: #ffffff;
                        border-radius: 12px;
                        overflow: hidden;
                    ">
                    <tr>
                        <td
                            style="
                                background-color: #b91c1c;
                                padding: 28px 30px;
                                text-align: center;
                            ">
                            <h1
                                style="
                                    margin: 0;
                                    color: #ffffff;
                                    font-size: 22px;
                                    line-height: 1.4;
                                ">
                                Permintaan Informasi Tidak Dapat Diproses
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px;">
                            <p
                                style="
                                    margin: 0 0 18px;
                                    font-size: 15px;
                                    line-height: 1.7;
                                ">
                                Yth. <strong>{{ $namaWarga }}</strong>,
                            </p>

                            <p
                                style="
                                    margin: 0 0 20px;
                                    font-size: 15px;
                                    line-height: 1.7;
                                ">
                                Permintaan informasi publik yang Anda ajukan
                                telah diperiksa oleh Admin Utama PPID Kota Batu.
                            </p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="
                                    width: 100%;
                                    margin-bottom: 20px;
                                    border-collapse: collapse;
                                ">
                                <tr>
                                    <td
                                        style="
                                            width: 42%;
                                            padding: 14px 16px;
                                            background-color: #f8fafc;
                                            border: 1px solid #e2e8f0;
                                            font-size: 14px;
                                        ">
                                        <strong>Nomor Registrasi</strong>
                                    </td>

                                    <td
                                        style="
                                            padding: 14px 16px;
                                            background-color: #ffffff;
                                            border: 1px solid #e2e8f0;
                                            font-size: 14px;
                                        ">
                                        {{ $nomorRegistrasi }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="
                                            padding: 14px 16px;
                                            background-color: #f8fafc;
                                            border: 1px solid #e2e8f0;
                                            font-size: 14px;
                                        ">
                                        <strong>Status</strong>
                                    </td>

                                    <td
                                        style="
                                            padding: 14px 16px;
                                            background-color: #ffffff;
                                            border: 1px solid #e2e8f0;
                                            color: #b91c1c;
                                            font-size: 14px;
                                        ">
                                        <strong>DITOLAK</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="
                                            padding: 14px 16px;
                                            background-color: #f8fafc;
                                            border: 1px solid #e2e8f0;
                                            font-size: 14px;
                                        ">
                                        <strong>Tanggal Penolakan</strong>
                                    </td>

                                    <td
                                        style="
                                            padding: 14px 16px;
                                            background-color: #ffffff;
                                            border: 1px solid #e2e8f0;
                                            font-size: 14px;
                                        ">
                                        {{ $tanggalPenolakan }}
                                    </td>
                                </tr>
                            </table>

                            <div
                                style="
                                    margin-bottom: 22px;
                                    padding: 18px;
                                    background-color: #fef2f2;
                                    border-left: 4px solid #dc2626;
                                    border-radius: 6px;
                                ">
                                <p
                                    style="
                                        margin: 0 0 8px;
                                        color: #991b1b;
                                        font-size: 14px;
                                    ">
                                    <strong>Alasan Penolakan</strong>
                                </p>

                                <p
                                    style="
                                        margin: 0;
                                        color: #7f1d1d;
                                        font-size: 14px;
                                        line-height: 1.7;
                                    ">
                                    {!! nl2br(e($alasanPenolakan)) !!}
                                </p>
                            </div>

                            <p
                                style="
                                    margin: 0 0 22px;
                                    font-size: 15px;
                                    line-height: 1.7;
                                ">
                                Permohonan tidak dapat diproses karena data atau
                                dokumen belum memenuhi kelengkapan yang
                                dipersyaratkan. Anda dapat mengajukan kembali
                                permohonan dengan data yang telah diperbaiki.
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                style="margin: 0 auto 24px;">
                                <tr>
                                    <td align="center" bgcolor="#b91c1c" style="border-radius: 7px;">
                                        <a href="{{ $trackingUrl }}" target="_blank" rel="noopener noreferrer"
                                            style="
                                                display: inline-block;
                                                padding: 13px 22px;
                                                color: #ffffff;
                                                text-decoration: none;
                                                font-size: 14px;
                                                font-weight: bold;
                                            ">
                                            Lihat Detail Permohonan
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p
                                style="
                                    margin: 0;
                                    font-size: 15px;
                                    line-height: 1.7;
                                ">
                                Salam hormat,<br>
                                <strong>PPID Kota Batu</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="
                                padding: 20px 30px;
                                background-color: #f8fafc;
                                text-align: center;
                                border-top: 1px solid #e2e8f0;
                            ">
                            <p
                                style="
                                    margin: 0;
                                    color: #94a3b8;
                                    font-size: 12px;
                                    line-height: 1.6;
                                ">
                                Email ini dikirim otomatis oleh sistem PPID Kota
                                Batu. Harap tidak membalas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>