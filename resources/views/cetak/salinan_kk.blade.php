<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <style>#feature a.next,#feature a.prev,#footer .footer-links,#nav,a.print-preview{display:none;color:#000;font-family:Georgia,serif}body{background:#fff;color:#000;font-family:Georgia,serif;line-height:1.2}blockquote,code,dl,form,ol,p,pre,table,ul{margin:0 0 1em}#header strong{color:#000;display:block;font-weight:400;font-size:3em;margin:0 0 1em;padding:0}#content:before{display:none}a:link:after,a:visited:after{content:" (" attr(href) ") ";font-size:80%;text-decoration:none}#feature>div{border:none;overflow:visible;height:auto}#feature div.items{left:auto!important;position:relative;overflow:visible;width:auto}#feature div.items div{float:none;margin:20px 0;position:relative}div.gallery{margin:1em 0;overflow:hidden}div.gallery div.items div{float:left;margin-right:10px}#footer{font-size:.83em;margin:2em 0 0;padding:1em 0 0}</style>
    <style>*{margin:0;padding:0}body{margin:2px;padding:2px;background:#fff;color:#000;font-size:12px;font-family:cambria,"times new roman",arial}h1{font-size:18px}h3{font-size:18px}h4{font-size:14px}h5{font-size:13px}label{display:inline-block}.nowrap{white-space:nowrap}#body{padding:0}#ktp{width:500px;margin:0;border:2px solid #000;padding:5px}.header{border-bottom:2px solid #000;padding-bottom:5px;margin:-5px auto}table{border-collapse:collapse;width:100%}table.border{border:1px solid #000}table.border.thick{border:2px solid #000}table.data{font-size:12px}table.border tr{border-bottom:1px solid #aaa}thead tr.thick,tr.thick{border-bottom:2px solid #000!important}table.border td{padding:2px 5px;border:1px solid #aaa!important}th{text-transform:uppercase;padding:2px;border:1px solid #000!important;background:#eee}tr.footer{text-transform:uppercase;font-weight:700;padding:2px;border-top:1px solid #000!important;background:#eee}td.thick,th.thick{border:solid #000;border-width:0 2px}td.top{vertical-align:top}table.noborder *{border:none!important}td.bilangan,td.no_urut,th.bilangan{text-align:center}img.logo{width:100px;height:auto;display:block;margin-left:auto;margin-right:auto}.judul{text-transform:uppercase;text-align:center}td.padat,th.padat{width:1px;white-space:nowrap;text-align:center}.text-center{text-align:center}td.textx{mso-number-format:"\@"}hr.garis{border-bottom:2px solid #000;height:0;margin-top:5px;margin-bottom:10px}.textx{mso-number-format:"\@"}td,th{font-size:9pt}table#ttd td{text-align:center;white-space:nowrap}.underline{text-decoration:underline}</style>
</head>

<body>
    <div id="container">
        <div id="body">
            <div align="center">
                <h3>KARTU KELUARGA</h3>
                <h4>SALINAN</h4>
                <h5>No. {{ $kepalaKeluarga->keluarga->no_kk ?? '' }} </h5>
            </div>
            <br>
            <table width="100%" cellpadding="3" cellspacing="4">
                <tbody>
                    <tr>
                        <td>Nama KK</td>
                        <td>: {{ strtoupper($kepalaKeluarga->nama ?? '') }}</td>
                        <td>{{ ucfirst(config('aplikasi.sebutan_kecamatan')) }}</td>
                        <td>: {{ strtoupper(config('desa.nama_kecamatan')) }} </td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ strtoupper(config('aplikasi.sebutan_dusun')) }} {{ strtoupper($kepalaKeluarga->clusterDesa->dusun ?? '') }} </td>
                        <td>Kabupaten/Kota</td>
                        <td>: {{ ucfirst(config('desa.nama_kabupaten')) }} </td>
                    </tr>
                    <tr>
                        <td>RT / RW</td>
                        <td>: {{ $kepalaKeluarga->clusterDesa->rt ?? ''}} / {{ $kepalaKeluarga->clusterDesa->rw ?? ''}}</td>
                        <td>Kode Pos</td>
                        <td>: {{ config('desa.kode_pos') }}</td>
                    </tr>
                    <tr>
                        <td>Kelurahan/{{ ucwords(config('aplikasi.sebutan_desa')) }}</td>
                        <td>: {{ ucwords(config('desa.nama_desa')) }} </td>
                        <td>Provinsi</td>
                        <td>: {{ ucwords(config('desa.nama_propinsi')) }} </td>
                    </tr>
                </tbody>
            </table>

            <br>

            <table class="border thick ">
                <thead>
                    <tr class="border thick">
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">NIK</th>
                        <th class="text-center">Jenis Kelamin</th>
                        <th class="text-center">Tempat Lahir</th>
                        <th class="text-center">Tanggal Lahir</th>
                        <th class="text-center">Agama</th>
                        <th class="text-center">Pendidikan</th>
                        <th class="text-center">Pekerjaan</th>
                        <th class="text-center">Golongan darah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggota as $keluarga)
                        <tr class="data">
                            <td class="text-center"> {{ $loop->iteration }}</td>
                            <td>{{ strtoupper($keluarga->nama) }}</td>
                            <td>{{ strtoupper($keluarga->nik) }}</td>
                            <td>{{ strtoupper($keluarga->jenisKelamin->nama) }}</td>
                            <td>{{ strtoupper($keluarga->tempatlahir) }}</td>
                            <td>{{ strtoupper($keluarga->tanggallahir ? $keluarga->tanggallahir->format('d-m-Y') : null) }}</td>
                            <td>{{ strtoupper($keluarga->agama->nama) }}</td>
                            <td>{{ strtoupper($keluarga->pendidikanKK->nama) }}</td>
                            <td>{{ strtoupper($keluarga->pekerjaan->nama) }}</td>
                            <td align="center">{{ strtoupper($keluarga->golonganDarah->nama) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br>

            <table class="border thick ">
                <thead>
                    <tr class="border thick">
                        <th class="text-center">No</th>
                        <th class="text-center">Status Perkawinan</th>
                        <th class="text-center">Tanggal Perkawinan</th>
                        <th class="text-center">Tanggal Perceraian</th>
                        <th class="text-center">Status Hubungan dalam Keluarga</th>
                        <th class="text-center">Kewarganegaraan</th>
                        <th class="text-center">No. Paspor</th>
                        <th class="text-center">No. KITAS / KITAP</th>
                        <th class="text-center">Nama Ayah</th>
                        <th class="text-center">Nama Ibu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggota as $penduduk)
                        <tr class="data">
                            <td class="text-center" width="2">{{ $loop->iteration }}</td>
                            <td>{{ strtoupper($penduduk->statusPerkawinan) }}</td>
                            <td>{{ strtoupper($penduduk->tanggalperkawinan) }}</td>
                            <td>{{ strtoupper($penduduk->tanggalperceraian) }}</td>
                            <td>{{ strtoupper($penduduk->pendudukHubungan->nama) }}</td>
                            <td>{{ strtoupper($penduduk->wargaNegara->nama) }}</td>
                            <td>{{ strtoupper($penduduk->dokumen_pasport) }}</td>
                            <td>{{ strtoupper($penduduk->dokumen_kitas) }}</td>
                            <td>{{ strtoupper($penduduk->nama_ayah) }}</td>
                            <td align="center">{{ strtoupper($penduduk->nama_ibu) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="page-break-after: avoid;"></p>
            <table width="100%" cellpadding="3" cellspacing="4">
                <tbody>
                    <tr>
                        <td width="25%"></td>
                        <td width="50%"></td>
                        <td width="25%" align="center">{{ strtoupper(config('desa.nama_desa')) }} , {{ \Illuminate\Support\Carbon::now()->formatLocalized("%d %B %Y") }}</td>
                    </tr>
                    <tr>
                        <td width="25%" align="center">KEPALA KELUARGA</td>
                        <td width="50%"></td>
                        <td align="center" width="150">KEPALA {{ strtoupper(config('aplikasi.sebutan_desa')) }} {{ strtoupper(config('desa.nama_desa')) }} </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="25%" align="center">{{ strtoupper($kepalaKeluarga->nama ?? '') }}</td>
                        <td width="50%"></td>
                        <td width="25%" align="center" width="150">{{ strtoupper(config('desa.nama_kepala_desa')) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
