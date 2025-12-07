<?php
// ================================
// File: templates/surat/create.php (Final Partial Structure)
// ================================
?>
<style>
  .preview-scroll {
    max-height: calc(100vh - 180px);
    overflow: auto;
  }
  .preview-frame {
    background: #fff;
    max-width: 900px;
    margin: 0 auto;
    padding: 24px 28px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  }
</style>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
  <!-- Kolom Formulir Input -->
  <div class="bg-white p-6 rounded-lg shadow-md">
    <h3 class="text-xl font-semibold mb-6 border-b pb-4">Formulir Isian Surat</h3>

    <form id="suratForm" action="<?= BASE_URL; ?>/surat/generate" method="post" target="_blank">
      <div class="mb-4">
        <label for="id_surat_masuk" class="block text-sm font-medium text-gray-700 mb-1">Gunakan data dari Surat Masuk</label>
        <select id="id_surat_masuk" name="id_surat_masuk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          <option value="">-- Tidak ada referensi --</option>
          <?php foreach (($surat_masuk_ref ?? []) as $sm): ?>
            <option value="<?= (int)$sm['id']; ?>" data-kode="<?= htmlspecialchars($sm['kode_klasifikasi']); ?>" data-unit="<?= htmlspecialchars($sm['unit_pengolah'] ?? ''); ?>">
              <?= htmlspecialchars($sm['nomor_agenda']); ?> — <?= htmlspecialchars($sm['perihal']); ?> (<?= htmlspecialchars($sm['kode_klasifikasi']); ?>)
            </option>
          <?php endforeach; ?>
        </select>
        <p class="text-xs text-gray-500 mt-1">Jika dipilih, kode klasifikasi dan unit akan mengikuti data surat masuk.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label for="kodeKlasifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kode Klasifikasi</label>
          <input type="text" id="kodeKlasifikasi" name="kodeKlasifikasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: 800.1">
          <p class="text-xs text-gray-500 mt-1">Mengikuti kode klasifikasi pemerintah.</p>
        </div>
        <div>
          <label for="kodeRegistrasi" class="block text-sm font-medium text-gray-700 mb-1">Kode Registrasi Surat Keluar</label>
          <input type="text" id="kodeRegistrasi" name="kodeRegistrasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Urut register, mis. 01">
          <p class="text-xs text-gray-500 mt-1">Angka/nomor urut yang akan digabungkan dengan kode klasifikasi.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 items-end">
        <div>
          <label for="unitPengolahSelect" class="block text-sm font-medium text-gray-700 mb-1">Unit Pengolah</label>
          <select id="unitPengolahSelect" name="unitPengolahSelect" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <?php foreach (($unit_pengolah_options ?? []) as $u): ?>
              <option value="<?= htmlspecialchars($u); ?>"><?= htmlspecialchars($u); ?></option>
            <?php endforeach; ?>
          </select>
          <p class="text-xs text-gray-500 mt-1">Sama dengan disposisi Sekcam/Umpeg.</p>
        </div>
        <div>
          <label for="noSurat" class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat (otomatis)</label>
          <input type="text" id="noSurat" name="noSurat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" placeholder="800.1/01 -Umpeg" readonly>
          <p class="text-xs text-gray-500 mt-1">Format: kode klasifikasi/kode registrasi -Unit Pengolah.</p>
        </div>
      </div>

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
  <div class="bg-gray-200 p-4 rounded-lg preview-scroll">
    <div class="preview-container" id="preview-wrapper">
      <div id="preview-container" class="preview-frame">
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
  const kodeKlasifikasiInput = document.getElementById('kodeKlasifikasi');
  const kodeRegistrasiInput = document.getElementById('kodeRegistrasi');
  const unitPengolahSelect = document.getElementById('unitPengolahSelect');
  const nomorSuratInput = document.getElementById('noSurat');
  const suratMasukSelect = document.getElementById('id_surat_masuk');
  const btnExportDoc = document.getElementById('btnExportDOC');

  function composeNomorSurat() {
    const kode = (kodeKlasifikasiInput?.value || '').trim();
    const reg = (kodeRegistrasiInput?.value || '').trim();
    const unit = (unitPengolahSelect?.value || '').trim();

    let nomor = '';
    if (kode || reg || unit) {
      nomor = (kode ? `${kode}/` : '') + (reg || '');
      if (unit) nomor += ` -${unit}`;
    }
    if (nomorSuratInput) {
      nomorSuratInput.value = nomor;
    }
    setText('preview-noSurat', nomor);
  }

  [kodeKlasifikasiInput, kodeRegistrasiInput, unitPengolahSelect].forEach(el => {
    if (!el) return;
    el.addEventListener('input', composeNomorSurat);
  });

  if (suratMasukSelect) {
    suratMasukSelect.addEventListener('change', () => {
      const opt = suratMasukSelect.selectedOptions[0];
      const kode = opt ? (opt.getAttribute('data-kode') || '') : '';
      const unit = opt ? (opt.getAttribute('data-unit') || '') : '';
      if (kode && kodeKlasifikasiInput) kodeKlasifikasiInput.value = kode;
      if (unit && unitPengolahSelect) {
        const match = Array.from(unitPengolahSelect.options).find(o => o.value === unit);
        if (match) unitPengolahSelect.value = unit;
      }
      composeNomorSurat();
    });
  }

  document.getElementById('btnExportPDF').addEventListener('click', () => {
    let s = document.createElement('input');
    s.type = 'hidden';
    s.name = 'export_scope';
    s.value = 'pdf';
    form.appendChild(s);
    form.submit();
  });
  if (btnExportDoc) {
    btnExportDoc.addEventListener('click', () => {
      let s = document.createElement('input');
      s.type = 'hidden';
      s.name = 'export_scope';
      s.value = 'doc';
      form.appendChild(s);
      form.submit();
    });
  }

</script>

<script>
  const templateSelect = document.getElementById('template_id');
  const formContainer = document.getElementById('form-container');
  const previewContainer = document.getElementById('preview-container');

  /* --- util --- */
  function setText(spanId, value, {
    html = false,
    preserve = false
  } = {}) {
    const el = document.getElementById(spanId);
    if (!el) return;
    if (preserve) {
      el.textContent = value ?? '';
      el.style.whiteSpace = 'pre-wrap';
    } else if (html) {
      el.innerHTML = value ?? '';
    } else {
      el.textContent = value ?? '';
    }
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
      preserve: true
    }],
    ['tugasSurat', 'preview-tugas', {
      preserve: true
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
        } else {
          setText(spanId, input.value, {
            html: !!opt.html,
            preserve: !!opt.preserve
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
      } else {
        setText(spanId, input.value, {
          html: !!opt.html,
          preserve: !!opt.preserve
        });
      }
    });

    if (template === 'tugas') {
      renderPegawaiPreview();
    }
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
        initPegawaiRepeater();
        // lalu sinkronkan nilai awal ke preview
        updatePreviewFromForm(template);
      });

    /* load PREVIEW */
    fetch(`<?= BASE_URL ?>/surat/get_partial?part=preview&template=${template}`)
      .then(res => res.text())
      .then(html => {
        previewContainer.innerHTML = `<div class="preview-frame">${html}</div>`;
        // setelah preview ganti, refresh isian dari form ke preview
        updatePreviewFromForm(template);
      });
  });

  /* inisialisasi default: template 'tugas' */
  function collectPegawai() {
    const rows = document.querySelectorAll('.pegawai-item');
    const data = [];
    rows.forEach(row => {
      const nama = row.querySelector('[data-field="nama"]')?.value?.trim() ?? '';
      const pangkat = row.querySelector('[data-field="pangkat"]')?.value?.trim() ?? '';
      const nip = row.querySelector('[data-field="nip"]')?.value?.trim() ?? '';
      const jabatan = row.querySelector('[data-field="jabatan"]')?.value?.trim() ?? '';
      if (nama || pangkat || nip || jabatan) {
        data.push({ nama, pangkat, nip, jabatan });
      }
    });
    return data.length ? data : [{ nama: '', pangkat: '', nip: '', jabatan: '' }];
  }

  function renderPegawaiPreview() {
    const data = collectPegawai();
    const singleWrap = document.getElementById('preview-pegawai-single');
    const multiWrap = document.getElementById('preview-pegawai-multi');
    const listEl = document.getElementById('preview-pegawai-list');
    if (!singleWrap || !multiWrap || !listEl) return;

    const rows = document.querySelectorAll('.pegawai-item');
    rows.forEach(row => {
      const btn = row.querySelector('.btn-remove-pegawai');
      if (!btn) return;
      if (rows.length > 1) btn.classList.remove('hidden');
      else btn.classList.add('hidden');
    });

    if (data.length === 1) {
      singleWrap.classList.remove('hidden');
      multiWrap.classList.add('hidden');
      setText('preview-pegawaiNama', data[0].nama);
      setText('preview-pegawaiPangkat', data[0].pangkat);
      setText('preview-pegawaiNip', data[0].nip);
      setText('preview-pegawaiJabatan', data[0].jabatan);
    } else {
      singleWrap.classList.add('hidden');
      multiWrap.classList.remove('hidden');
      listEl.innerHTML = '';
      data.forEach((row, idx) => {
        const li = document.createElement('li');
        li.className = 'mb-3';
        li.innerHTML = `
          <div><strong>${idx + 1}.</strong> ${row.nama || '-'}</div>
          <div style="padding-left:18px;">
            <div>Pangkat/Gol : ${row.pangkat || '-'}</div>
            <div>NIP : ${row.nip || '-'}</div>
            <div>Jabatan : ${row.jabatan || '-'}</div>
          </div>`;
        listEl.appendChild(li);
      });
    }
  }

  function initPegawaiRepeater() {
    const container = document.getElementById('pegawaiRepeater');
    if (!container) return;

    const addBtn = document.getElementById('btnAddPegawai');
    const template = document.getElementById('pegawai-template');

    const bindInput = (row) => {
      row.querySelectorAll('input').forEach(inp => {
        ['input', 'change'].forEach(evt => inp.addEventListener(evt, () => {
          renderPegawaiPreview();
        }));
      });
      const rm = row.querySelector('.btn-remove-pegawai');
      if (rm) {
        rm.addEventListener('click', () => {
          row.remove();
          renderPegawaiPreview();
        });
      }
    };

    container.querySelectorAll('.pegawai-item').forEach(bindInput);

    if (addBtn && template) {
      addBtn.addEventListener('click', () => {
        const clone = template.content.firstElementChild.cloneNode(true);
        container.appendChild(clone);
        bindInput(clone);
        renderPegawaiPreview();
      });
    }
    renderPegawaiPreview();
  }

  attachLivePreview('tugas');
  updatePreviewFromForm('tugas');
  initPegawaiRepeater();
  composeNomorSurat();
</script>
