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
      .split(',')
      .map(s => s.trim())
      .filter(Boolean);
  }

  async function postCheck(url, csrf, payload) {
    const fd = new FormData();
    Object.entries(payload).forEach(([k, v]) => fd.append(k, v));

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
      resultEl.innerHTML = `
        <div class="alert alert-danger p-2 mb-2">
          Document Number sudah terpakai / duplikat:
          <b>${data.exists.join(', ')}</b>
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

  document.addEventListener('DOMContentLoaded', () => {
    const cfg = window.DOCNO_CHECK || {};

    const url = cfg.url || "/check-doc-no";
    const type = cfg.type || "all";
    const ignoreId = cfg.ignore_id || "";

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

      const payload = {
        doc_no: raw,
        document_type: type
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

    docEl.addEventListener('input', debounce(doCheck, 500));
    docEl.addEventListener('blur', doCheck);
  });
})();
