(function () {
  function getCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
  }

  function debounce(fn, ms = 500) {
    let timer;
    return function (...args) {
      clearTimeout(timer);
      timer = setTimeout(() => fn.apply(this, args), ms);
    };
  }

  function normalize(raw) {
    return (raw || '')
      .replace(/[\r\n\t]+/g, ';')
      .split(';')
      .map(s => s.trim().toUpperCase())
      .filter(Boolean);
  }

  async function postCheck(url, csrf, payload) {
    const fd = new FormData();
    console.log(fd);
    console.log(payload);

    Object.entries(payload).forEach(([k, v]) => {
      // skip undefined/null biar gak ngirim aneh-aneh
      if (v === undefined || v === null) return;
      fd.append(k, v);
    });

    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
      },
      body: fd
    });

    if (!res.ok) {
      const txt = await res.text().catch(() => '');
      throw new Error(`HTTP ${res.status} ${txt}`);
    }

    return await res.json();
  }

  function render(resultEl, submitBtn, data) {
    // kosong
    if (!data || (data.checked_count || 0) === 0) {
      resultEl.innerHTML = '';
      submitBtn.disabled = false;
      return;
    }

    // ada bentrok / duplikat
    if (data.exists && data.exists.length) {

    const lines = data.exists.map(no => {
        const makers = (data.makers && data.makers[no])
        ? data.makers[no].map(x => x.user_name).join(', ')
        : '-';

        return `${no} (Dibuat oleh: ${makers})`;
    });

    resultEl.innerHTML = `
        <div class="alert alert-danger p-2 mb-2">
        Document Number sudah terpakai / duplikat:<br>
        * <b>${lines.join('<br>* ')}</b>
        </div>
    `;
    submitBtn.disabled = true;
    return;
    }

    // aman
    resultEl.innerHTML = `
      <div class="alert alert-success p-2 mb-2">
        Semua Document Number aman (${data.checked_count} item).
      </div>
    `;
    submitBtn.disabled = false;
  }

  function readFilterValue(filterField) {
    if (!filterField) return '';

    // cari elemen berdasarkan id (paling umum)
    let el = document.getElementById(filterField);

    // fallback: kalau ternyata pakai name bukan id
    if (!el) el = document.querySelector(`[name="${CSS.escape(filterField)}"]`);

    if (!el) return '';

    // handle checkbox/radio
    const type = (el.type || '').toLowerCase();
    if (type === 'checkbox') return el.checked ? (el.value || '1') : '';
    if (type === 'radio') {
      const checked = document.querySelector(`[name="${CSS.escape(el.name)}"]:checked`);
      return checked ? (checked.value || '') : '';
    }

    return (el.value ?? '').toString().trim();
  }

  document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.DOCNO_CHECK || {};

    const url = cfg.url || "/check-doc-no";
    const docType = cfg.type || "all";
    const ignoreId = cfg.ignore_id || "";
    const filterField = cfg.filter_field || "";
    const filterLabel = cfg.filter_label || filterField;

    const docEl = document.getElementById('doc_no');
    const resultEl = document.getElementById('docNoResult');
    const submitBtn = document.getElementById('submit');

    if (!docEl || !resultEl || !submitBtn) return;

    const csrf = getCsrfToken();

    const doCheck = async () => {
      const raw = docEl.value || '';
      const tokens = normalize(raw);

      if (tokens.length === 0) {
        resultEl.innerHTML = '';
        submitBtn.disabled = false;
        return;
      }

      // ✅ optional filter dinamis
      const fv = readFilterValue(filterField);
      if (filterField && fv === '') {
        resultEl.innerHTML = `
            <div class="alert alert-warning p-2 mb-2">
            Silakan pilih ${filterLabel} terlebih dahulu sebelum mengisi Document Number.
            </div>
        `;
        submitBtn.disabled = true;
        return;
      }

      const payload = {
        doc_no: raw,
        document_type: docType,
        filter_field: filterField,
        filter_value: fv,
      };

      if (ignoreId) payload.ignore_id = ignoreId;

      // Tampilkan indikator loading saat pengecekan berlangsung
      resultEl.innerHTML = `
        <div class="text-secondary small p-1 mb-1">
          <i class="fa fa-spinner fa-spin me-1"></i> Memeriksa duplikasi ${tokens.length} Document Number...
        </div>
      `;

      try {
        const data = await postCheck(url, getCsrfToken(), payload);
        render(resultEl, submitBtn, data);
      } catch (e) {
        console.error(e);
        const errStr = (e && e.message) ? e.message : String(e);
        let msg = 'Gagal cek Document Number. Silakan periksa koneksi jaringan Anda.';
        let showReloadBtn = false;
        let showRetryBtn = true;

        if (errStr.includes('419')) {
          msg = 'Sesi Anda telah kedaluwarsa (Error 419). Silakan muat ulang halaman.';
          showReloadBtn = true;
          showRetryBtn = false;
        } else if (errStr.includes('429')) {
          msg = 'Terlalu banyak permintaan dalam waktu singkat (Error 429). Mohon tunggu sejenak sebelum mencoba lagi.';
        } else if (errStr.includes('502') || errStr.includes('503') || errStr.includes('504')) {
          msg = 'Server sedang sibuk / timeout (Error 502/503/504). Silakan coba beberapa saat lagi.';
        } else if (errStr.includes('500')) {
          msg = 'Terjadi kesalahan pada database/server (Error 500) saat validasi. Silakan hubungi tim IT.';
        } else if (errStr.includes('403') || errStr.includes('401')) {
          msg = 'Sesi login telah berakhir atau akses ditolak (Error 401/403). Silakan login ulang.';
          showReloadBtn = true;
          showRetryBtn = false;
        } else if (errStr.includes('404')) {
          msg = 'Alamat validasi tidak ditemukan (Error 404). Silakan hubungi tim IT.';
          showRetryBtn = false;
        } else if (errStr.includes('Failed to fetch') || errStr.includes('NetworkError')) {
          msg = 'Gagal terhubung ke server. Periksa koneksi jaringan Anda.';
        } else {
          msg = `Gagal cek Document Number: ${errStr}`;
        }

        let actionHtml = '';
        if (showReloadBtn) {
          actionHtml = `<button type="button" class="btn btn-warning btn-xs ms-2 py-0 px-2" onclick="window.location.reload()"><i class="fa fa-rotate-right"></i> Refresh Halaman</button>`;
        } else if (showRetryBtn) {
          actionHtml = `<button type="button" class="btn btn-secondary btn-xs ms-2 py-0 px-2" id="btnRetryDocNo"><i class="fa fa-rotate-right"></i> Coba Lagi</button>`;
        }

        resultEl.innerHTML = `
          <div class="alert alert-warning p-2 mb-2 d-flex align-items-center justify-content-between flex-wrap">
            <div>
              <i class="fa fa-exclamation-triangle me-1"></i> ${msg}
            </div>
            <div>
              ${actionHtml}
            </div>
          </div>
        `;

        const retryBtn = document.getElementById('btnRetryDocNo');
        if (retryBtn) {
          retryBtn.addEventListener('click', () => doCheck());
        }

        submitBtn.disabled = true;
      }
    };

    // auto uppercase input
    docEl.style.textTransform = 'uppercase';
    docEl.addEventListener('input', function () {
      this.value = this.value.toUpperCase();
    });

    // auto format saat copy-paste dari Excel/catatan (ubah newline/tab menjadi '; ')
    docEl.addEventListener('paste', function (e) {
      const pasteText = (e.clipboardData || window.clipboardData)?.getData('text') || '';
      if (/[\r\n\t]/.test(pasteText)) {
        e.preventDefault();
        const formatted = pasteText
          .split(/[\r\n\t]+/)
          .map(s => s.trim().toUpperCase())
          .filter(Boolean)
          .join('; ');

        const start = this.selectionStart || 0;
        const end = this.selectionEnd || 0;
        const current = this.value;
        const prefix = (start > 0 && !current.slice(0, start).trimEnd().endsWith(';')) ? '; ' : '';
        const combined = current.slice(0, start) + prefix + formatted + current.slice(end);

        this.value = combined;
        this.dispatchEvent(new Event('input', { bubbles: true }));
      }
    });

    // check saat input doc_no
    docEl.addEventListener('input', debounce(doCheck, 500));
    docEl.addEventListener('blur', doCheck);

    // ✅ recheck kalau filter berubah (mis. select dept diganti)
    if (filterField) {
      const filterEl =
        document.getElementById(filterField) ||
        document.querySelector(`[name="${CSS.escape(filterField)}"]`);

      if (filterEl) {
        filterEl.addEventListener('change', doCheck);
        // kalau input text, bisa juga trigger saat ngetik
        filterEl.addEventListener('input', debounce(doCheck, 300));
      }
    }
  });
})();
