<?php

// Pastikan sudah menginstall mPDF melalui Composer
// require_once '../vendor/autoload.php';

class Surat extends Controller
{
    public function __construct()
    {
        // Pastikan hanya user yang sudah login yang bisa akses
        if (!isset($_SESSION['login'])) {
            header('Location: ' . BASE_URL . '/auth');
            exit;
        }

        if (in_array(currentRole(), ['camat', 'sekcam', 'unit'], true)) {
            header('Location: ' . BASE_URL . '/suratmasuk');
            exit;
        }
    }
    // Menampilkan halaman pembuatan surat dengan live preview
    public function index()
    {
        // === ini Atur pesan flash untuk maintenance ===
        // Flasher::setFlash('Dalam Pengembangan', 'Fitur pembuatan surat sedang dalam tahap pengerjaan dan belum berfungsi sepenuhnya.', 'info');
        $data['judul'] = 'Buat Surat Keluar';

        // Mengambil data pejabat default (Camat) dari model
        $data['pejabat'] = $this->model('Surat_model')->getPejabatDefault();

        // Mengambil daftar template dari model
        $data['templates'] = $this->model('Surat_model')->getAllTemplates();

        $data['surat_list'] = $this->model('Surat_model')->getSuratKeluar(50);
        $data['surat_masuk_ref'] = $this->model('Suratmasuk_model')->getReferensiKeluar();
        $data['unit_pengolah_options'] = ['Umpeg', 'Pemerintahan', 'Pembangunan', 'Trantib', 'Ekonomi Pembangunan'];



        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('surat/create', $data);
        $this->view('layouts/footer');
    }

    // Proses untuk generate file PDF
    public function generate()
    {
        // --- 1) Ambil data POST
        $post   = $_POST;
        $raw    = $post['template_id'] ?? '';      // bisa 'tugas'/'keterangan' atau angka ID
        $export = $post['export_scope'] ?? 'pdf';  // 'pdf' | 'doc'
        $idSuratMasuk = !empty($post['id_surat_masuk']) ? (int)$post['id_surat_masuk'] : null;
        $kodeKlasifikasi = trim($post['kodeKlasifikasi'] ?? '');

        if ($raw === '') {
            http_response_code(400);
            exit('Template tidak dipilih.');
        }

        // --- 2) Tentukan $kode & $idTemplate
        $kode = null;
        $templateInfo = null;
        $idTemplate = 0;

        // 1) coba lewat model (direkomendasikan)
        if (method_exists($this->model('Surat_model'), 'getTemplateByKode')) {
            $tpl = $this->model('Surat_model')->getTemplateByKode($kode);
            if (!empty($tpl['id'])) {
                $idTemplate = (int)$tpl['id'];
            }
        }

        // 2) fallback aman kalau tabel belum punya kolom 'kode_template'
        if ($idTemplate === 0 && method_exists($this->model('Surat_model'), 'getTemplateByNamaLike')) {
            $byName = $this->model('Surat_model')->getTemplateByNamaLike($kode === 'tugas' ? 'tugas' : 'keterangan');
            if (!empty($byName['id'])) {
                $idTemplate = (int)$byName['id'];
            }
        }


        if (ctype_digit((string)$raw)) {
            // value adalah ID numeric → ambil dari DB
            $templateInfo = $this->model('Surat_model')->getTemplateById($raw);
            if (!$templateInfo) {
                http_response_code(404);
                exit('Template tidak ditemukan.');
            }
            $kode       = $templateInfo['kode_template'] ?? null;
            $idTemplate = (int)$templateInfo['id'];
        } else {
            // value adalah kode langsung
            if (!in_array($raw, ['tugas', 'keterangan'], true)) {
                http_response_code(400);
                exit('Template tidak valid.');
            }
            $kode = $raw;
            // coba mapping id dari kode (kalau method tersedia)
            if (method_exists($this->model('Surat_model'), 'getTemplateByKode')) {
                $m = $this->model('Surat_model')->getTemplateByKode($kode);
                if (!empty($m['id'])) $idTemplate = (int)$m['id'];
            }
        }
        if (!$kode) {
            http_response_code(400);
            exit('Kode template tidak valid.');
        }

        // --- 3) Ambil pejabat default
        $pejabat = $this->model('Surat_model')->getPejabatDefault();

        // --- 4) Helper format
        $e = fn($s) => htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
        $nl2p = fn($s) => nl2br($e($s ?? ''));
        $indo = function ($ymd) {
            if (!$ymd) return '';
            $bln = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            [$y, $m, $d] = explode('-', $ymd);
            return (int)$d . ' ' . ($bln[(int)$m] ?? '') . ' ' . $y;
        };

        // --- 5) Map form → variabel template

        // NORMALISASI TANGGAL (FIX)
        $rawTgl = trim($post['tglSurat'] ?? '');
        if ($rawTgl === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawTgl)) {
            // fallback aman agar tidak error NOT NULL di kolom DATE
            $rawTgl = date('Y-m-d');
        }
        $tanggalSuratDb    = $rawTgl;         // untuk INSERT ke DB
        $tglSuratFormatted = $indo($rawTgl);  // untuk ditampilkan di surat

        $noSurat           = $e($post['noSurat'] ?? '');

        if ($kodeKlasifikasi === '' && str_contains($noSurat, '/')) {
            $kodeKlasifikasi = trim(explode('/', $noSurat)[0]);
        }

        // Surat Tugas
        $dasarSuratPlain   = $e($post['dasarSurat'] ?? '');
        $tugasSuratPlain   = $e($post['tugasSurat'] ?? '');

        $pegawaiList = [];
        if (!empty($post['pegawai']) && is_array($post['pegawai'])) {
            $arr = $post['pegawai'];
            $max = 0;
            foreach (['nama', 'pangkat', 'nip', 'jabatan'] as $k) {
                $max = max($max, isset($arr[$k]) && is_array($arr[$k]) ? count($arr[$k]) : 0);
            }
            for ($i = 0; $i < $max; $i++) {
                $pegawaiList[] = [
                    'nama'    => $e($arr['nama'][$i] ?? ''),
                    'pangkat' => $e($arr['pangkat'][$i] ?? ''),
                    'nip'     => $e($arr['nip'][$i] ?? ''),
                    'jabatan' => $e($arr['jabatan'][$i] ?? ''),
                    'visible_nama' => !isset($arr['visible_nama'][$i]) || $arr['visible_nama'][$i] != '0',
                    'visible_pangkat' => !isset($arr['visible_pangkat'][$i]) || $arr['visible_pangkat'][$i] != '0',
                    'visible_nip' => !isset($arr['visible_nip'][$i]) || $arr['visible_nip'][$i] != '0',
                    'visible_jabatan' => !isset($arr['visible_jabatan'][$i]) || $arr['visible_jabatan'][$i] != '0',
                ];
            }
        } else {
            $pegawaiList[] = [
                'nama'    => $e($post['pegawaiNama'] ?? ''),
                'pangkat' => $e($post['pegawaiPangkat'] ?? ''),
                'nip'     => $e($post['pegawaiNip'] ?? ''),
                'jabatan' => $e($post['pegawaiJabatan'] ?? ''),
                'visible_nama' => true,
                'visible_pangkat' => true,
                'visible_nip' => true,
                'visible_jabatan' => true,
            ];
        }

        // fallback entry kosong agar tidak undefined
        if (empty($pegawaiList)) {
            $pegawaiList[] = ['nama' => '', 'pangkat' => '', 'nip' => '', 'jabatan' => ''];
        }

        // Surat Keterangan
        $namaPenduduk      = $e($post['namaPenduduk'] ?? '');
        $nikPenduduk       = $e($post['nikPenduduk'] ?? '');
        $alamatPenduduk    = $e($post['alamatPenduduk'] ?? '');
        $keteranganIsiHtml = $nl2p($post['keteranganIsi'] ?? '');


        // Asset
        $logoUrl = BASE_URL . '/assets/img/logo.png';

        // --- 6) Tentukan file export (server-side HTML)
        $exportTemplate = "../templates/surat/export/surat_{$kode}.php";
        if (!file_exists($exportTemplate)) {
            http_response_code(500);
            exit('File template export tidak ditemukan.');
        }

        // --- 7) Render HTML
        ob_start();
        include $exportTemplate; // variabel + $pejabat + $logoUrl dipakai di sini
        $html = ob_get_clean();

        // --- 8) Siapkan folder simpan
        $dir = $this->storageDir(); // ../public/uploads/surat_dihasilkan/
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $stamp    = date('Ymd-His');
        $basename = "Surat-{$kode}-{$stamp}";
        $userId   = $this->currentUserId();

        // Perihal untuk list
        $perihal = ($kode === 'tugas')
            ? 'Surat Tugas - ' . (implode(', ', array_filter(array_column($pegawaiList, 'nama'))) ?: '-')
            : 'Surat Keterangan - ' . ($namaPenduduk ?: '-');

        // --- 9) Generate & simpan + insert DB + download
        if ($export === 'doc') {
            // DOCX
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

            $filename = $basename . '.docx';
            $absPath  = $dir . $filename;

            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save($absPath); // simpan ke server

            // insert DB
            $this->model('Surat_model')->insertSuratKeluar([
                'id_template'     => $idTemplate,
                'nomor_surat'     => $post['noSurat'] ?? '',
                'tanggal_surat' => $tanggalSuratDb,
                'perihal'         => $perihal,
                'data_surat'      => json_encode($post, JSON_UNESCAPED_UNICODE),
                'id_user_pembuat' => $userId,
                'nama_file_pdf'   => $filename, // kolom kamu bernama nama_file_pdf
                'path_file'       => 'uploads/surat_dihasilkan/' . $filename,
                'kode_klasifikasi' => $kodeKlasifikasi,
                'id_surat_masuk' => $idSuratMasuk,
            ]);

            /* === SET FLASH === */
            Flasher::setFlash(
                'Berhasil',
                'Surat berhasil dibuat & disimpan. Silakan cek tabel riwayat di bawah.',
                'success'
            );
            // pastikan session disimpan sebelum kirim file
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            // download
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($absPath));
            readfile($absPath);
            exit;
        } else {
            // PDF
            $mpdf = new \Mpdf\Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
            ]);
            $mpdf->WriteHTML($html);

            $filename = $basename . '.pdf';
            $absPath  = $dir . $filename;

            // simpan file ke server
            $mpdf->Output($absPath, \Mpdf\Output\Destination::FILE);

            // insert DB
            $this->model('Surat_model')->insertSuratKeluar([
                'id_template'     => $idTemplate,
                'nomor_surat'     => $post['noSurat'] ?? '',
                'tanggal_surat'   => $tanggalSuratDb,
                'perihal'         => $perihal,
                'data_surat'      => json_encode($post, JSON_UNESCAPED_UNICODE),
                'id_user_pembuat' => $userId,
                'nama_file_pdf'   => $filename, // pdf atau docx sama-sama disimpan di kolom ini
                'path_file'       => 'uploads/surat_dihasilkan/' . $filename,
                'kode_klasifikasi' => $kodeKlasifikasi,
                'id_surat_masuk' => $idSuratMasuk,
            ]);

            /* === SET FLASH === */
            Flasher::setFlash(
                'Berhasil',
                'Surat berhasil dibuat & disimpan. Silakan cek tabel riwayat di bawah.',
                'success'
            );
            // pastikan session disimpan sebelum kirim file
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            // download file yang sudah disimpan
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($absPath));
            readfile($absPath);
            exit;
        }
    }

    public function delete($id)
    {
        $row = $this->model('Surat_model')->getSuratKeluarById((int)$id);
        if ($row) {
            $abs = __DIR__ . '/../../public/' . ltrim($row['path_file'], '/');
            if (is_file($abs)) {
                @unlink($abs);
            }
            $this->model('Surat_model')->deleteSuratKeluar((int)$id);
            Flasher::setFlash('Berhasil', 'Surat berhasil dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Surat tidak ditemukan.', 'danger');
        }
        header('Location: ' . BASE_URL . '/surat');
        exit;
    }

    public function get_partial()
    {
        $part     = $_GET['part'] ?? '';
        $template = $_GET['template'] ?? '';

        // whitelist sesuai kode_template
        $allowed = ['tugas', 'keterangan'];
        if (!in_array($template, $allowed)) {
            http_response_code(400);
            exit('Template tidak valid.');
        }

        // >>> INI PENTING: ambil pejabat default agar tersedia di partial preview
        $pejabat = $this->model('Surat_model')->getPejabatDefault();

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

        $path = "../templates/surat/partials/";
        if ($part === 'form') {
            include $path . "form_surat_{$template}.php";
        } elseif ($part === 'preview') {
            include $path . "preview_surat_{$template}.php"; // $pejabat available di sini
        } else {
            http_response_code(400);
            echo 'Part tidak dikenali.';
        }
    }
    private function storageDir()
    {
        return __DIR__ . '/../../public/uploads/surat_dihasilkan/';
    }
    private function currentUserId()
    {
        return $_SESSION['user_id']['id'] ?? $_SESSION['user_id'] ?? 0;
    }
}
