<?php
// ================================
// File: templates/surat/create.php (Final Partial Structure)
// ================================
?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <!-- Kolom Formulir Input -->
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-6 border-b pb-4">Formulir Isian Surat</h3>

    <form id="suratForm" action="<?= BASE_URL; ?>/surat/generate" method="post" target="_blank">
      <div class="mb-4">
        <label for="template_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Template Surat</label>
        <select id="template_id" name="template_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <option value="tugas">Surat Tugas</option>
          <option value="keterangan">Surat Keterangan</option>
        </select>
      </div>

      <div id="form-container">
        <?php include __DIR__ . '/partials/form_surat_tugas.php'; ?>
      </div>
      <div class="flex justify-end gap-3 mt-4">
        <button type="button" id="btnExportPDF" class="bg-green-600 text-white px-4 py-2 rounded">Download PDF</button>
      </div>
    </form>
  </div>

  <!-- Kolom Live Preview -->
  <div class="bg-gray-200 p-4 rounded-lg overflow-auto">
    <div class="preview-container" id="preview-wrapper">
      <div id="preview-container">
        <?php include __DIR__ . '/partials/preview_surat_tugas.php'; ?>
      </div>
    </div>
  </div>
</div>

<!-- Riwayat Surat Keluar -->
<div class="bg-white p-6 rounded-lg shadow-md mt-8">
  <div class="flex items-center justify-between mb-4">
    <h3 class="text-lg font-semibold">Riwayat Surat Keluar</h3>
    <span class="text-sm text-gray-500">
      <?= isset($surat_list) ? count($surat_list) : 0; ?> data
    </span>
  </div>

  <?php if (!empty($surat_list)): ?>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="border-b">
            <th class="text-left py-2 pr-4">#</th>
            <th class="text-left py-2 pr-4">Tanggal</th>
            <th class="text-left py-2 pr-4">Nomor Surat</th>
            <th class="text-left py-2 pr-4">Template</th>
            <th class="text-left py-2 pr-4">Perihal</th>
            <th class="text-left py-2 pr-4">File</th>
            <th class="text-left py-2 pr-4">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($surat_list as $i => $r): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="py-2 pr-4"><?= $i + 1 ?></td>

              <td class="py-2 pr-4">
                <?php
                $tgl = $r['tanggal_surat'] ?? '';
                echo $tgl ? date('d/m/Y', strtotime($tgl)) : '-';
                ?>
              </td>

              <td class="py-2 pr-4"><?= htmlspecialchars($r['nomor_surat'] ?? '-') ?></td>
              <td class="py-2 pr-4"><?= htmlspecialchars($r['nama_template'] ?? '-') ?></td>
              <td class="py-2 pr-4"><?= htmlspecialchars($r['perihal'] ?? '-') ?></td>

              <td class="py-2 pr-4">
                <?php if (!empty($r['path_file'])): ?>
                  <a class="text-blue-600 hover:underline" target="_blank"
                    href="<?= BASE_URL . '/' . ltrim($r['path_file'], '/') ?>">
                    <?= htmlspecialchars($r['nama_file_pdf'] ?? 'Unduh') ?>
                  </a>
                <?php else: ?>
                  <span class="text-gray-400">-</span>
                <?php endif; ?>
              </td>

              <td class="py-2 px-4 whitespace-nowrap text-sm font-medium">
                <!-- Lihat/Unduh -->
                <a href="<?= BASE_URL . '/' . ltrim($r['path_file'], '/') ?>"
                  target="_blank"
                  class="text-blue-600 hover:text-blue-900"
                  title="Lihat / Unduh">
                  <i class="fas fa-file"></i>
                </a>

                <!-- Hapus dengan modal konfirmasi -->
                <button type="button"
                  class="text-red-600 hover:text-red-900 ml-4"
                  title="Hapus"
                  onclick="showConfirmation(
            'Konfirmasi Hapus',
            'Apakah Anda yakin ingin menghapus surat ini?',
            '<?= BASE_URL ?>/surat/delete/<?= (int)$r['id'] ?>'
          )">
                  <i class="fas fa-trash"></i>
                </button>
              </td>

            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-gray-500 text-sm">Belum ada surat yang di-generate.</p>
  <?php endif; ?>
</div>

<!-- Flasher Notifikasi -->
<?php Flasher::flash(); ?>
<script>
  const form = document.getElementById('suratForm');
  document.getElementById('btnExportPDF').addEventListener('click', () => {
    let s = document.createElement('input');
    s.type = 'hidden';
    s.name = 'export_scope';
    s.value = 'pdf';
    form.appendChild(s);
    form.submit();
  });
  document.getElementById('btnExportDOC').addEventListener('click', () => {
    let s = document.createElement('input');
    s.type = 'hidden';
    s.name = 'export_scope';
    s.value = 'doc';
    form.appendChild(s);
    form.submit();
  });
</script>

<script>
  const templateSelect = document.getElementById('template_id');
  const formContainer = document.getElementById('form-container');
  const previewContainer = document.getElementById('preview-container');

  /* --- util --- */
  function nl2br(str) {
    if (!str) return '';
    return String(str).replace(/\n/g, '<br>');
  }

  function setText(spanId, value, {
    html = false
  } = {}) {
    const el = document.getElementById(spanId);
    if (!el) return;
    if (html) el.innerHTML = value ?? '';
    else el.textContent = value ?? '';
  }

  /* daftar field utk kedua template */
  const BIND_MAP_COMMON = [
    ['noSurat', 'preview-noSurat', {
      html: false
    }],
    ['tglSurat', 'preview-tglSurat', {
      type: 'date'
    }], // diformat ID
  ];
  const BIND_MAP_TUGAS = [
    ['dasarSurat', 'preview-dasar', {
      html: true,
      nl2br: true
    }],
    ['pegawaiNama', 'preview-pegawaiNama', {}],
    ['pegawaiPangkat', 'preview-pegawaiPangkat', {}],
    ['pegawaiNip', 'preview-pegawaiNip', {}],
    ['pegawaiJabatan', 'preview-pegawaiJabatan', {}],
    ['tugasSurat', 'preview-tugas', {
      html: true,
      nl2br: true
    }],
  ];
  const BIND_MAP_KET = [
    ['namaPenduduk', 'preview-namaPenduduk', {}],
    ['nikPenduduk', 'preview-nikPenduduk', {}],
    ['alamatPenduduk', 'preview-alamatPenduduk', {}],
    ['keteranganIsi', 'preview-keteranganIsi', {
      html: true,
      nl2br: true
    }],
  ];

  /* pilih bind map berdasarkan template aktif */
  function getBindMap(template) {
    return [...BIND_MAP_COMMON, ...(template === 'keterangan' ? BIND_MAP_KET : BIND_MAP_TUGAS)];
  }

  /* pasang listener input -> preview */
  function attachLivePreview(template) {
    const binds = getBindMap(template);

    binds.forEach(([inputId, spanId, opt = {}]) => {
      const input = document.getElementById(inputId);
      if (!input) return;

      const handler = () => {
        if (opt.type === 'date') {
          const v = input.value ? new Date(input.value) : null;
          const formatted = v ? v.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
          }) : '';
          setText(spanId, formatted);
        } else if (opt.html && opt.nl2br) {
          setText(spanId, nl2br(input.value), {
            html: true
          });
        } else {
          setText(spanId, input.value, {
            html: !!opt.html
          });
        }
      };

      /* pasang untuk input & change */
      ['input', 'change'].forEach(evt => input.addEventListener(evt, handler));
    });
  }

  /* push semua nilai form sekarang ke preview (saat selesai load partial) */
  function updatePreviewFromForm(template) {
    const binds = getBindMap(template);

    binds.forEach(([inputId, spanId, opt = {}]) => {
      const input = document.getElementById(inputId);
      if (!input) return;

      if (opt.type === 'date') {
        const v = input.value ? new Date(input.value) : null;
        const formatted = v ? v.toLocaleDateString('id-ID', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        }) : '';
        setText(spanId, formatted);
      } else if (opt.html && opt.nl2br) {
        setText(spanId, nl2br(input.value), {
          html: true
        });
      } else {
        setText(spanId, input.value, {
          html: !!opt.html
        });
      }
    });
  }

  /* switch partial form + preview via AJAX */
  templateSelect.addEventListener('change', function() {
    const template = this.value; // 'tugas' | 'keterangan'

    /* load FORM */
    fetch(`<?= BASE_URL ?>/surat/get_partial?part=form&template=${template}`)
      .then(res => res.text())
      .then(html => {
        formContainer.innerHTML = html;
        // setelah form masuk DOM, pasang listener baru utk template ini
        attachLivePreview(template);
        // lalu sinkronkan nilai awal ke preview
        updatePreviewFromForm(template);
      });

    /* load PREVIEW */
    fetch(`<?= BASE_URL ?>/surat/get_partial?part=preview&template=${template}`)
      .then(res => res.text())
      .then(html => {
        previewContainer.innerHTML = html;
        // setelah preview ganti, refresh isian dari form ke preview
        updatePreviewFromForm(template);
      });
  });

  /* inisialisasi default: template 'tugas' */
  attachLivePreview('tugas');
  updatePreviewFromForm('tugas');
</script>
