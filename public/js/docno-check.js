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
      .split(';')
      .map(s => s.trim())
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

      try {
        const data = await postCheck(url, csrf, payload);
        render(resultEl, submitBtn, data);
      } catch (e) {
        console.error(e);
        resultEl.innerHTML = `
          <div class="alert alert-warning p-2 mb-2">
            Gagal cek Document Number.
          </div>
        `;
        submitBtn.disabled = true;
      }
    };

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
