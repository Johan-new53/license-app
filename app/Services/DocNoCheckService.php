<?php

namespace App\Services;

use App\Models\Finance;

class DocNoCheckService
{
public function check(string $input, ?string $documentType = 'all', ?int $ignoreId = null): array
{
    $tokens = collect(explode(',', $input))
        ->map(fn($v) => trim($v))
        ->filter()
        ->values();

    if ($tokens->isEmpty()) {
        return ['exists' => [], 'clean' => []];
    }

    // cek duplicate input
    $duplicates = [];
    foreach ($tokens as $value) {
        if ($tokens->filter(fn($v) => $v === $value)->count() > 1) {
            $duplicates[] = $value;
        }
    }
    $duplicates = array_unique($duplicates);

    $clean = $tokens->unique()->values()->all();

    $exists = [];

    foreach ($duplicates as $dup) {
        $exists[$dup] = true;
    }

    // cek DB
    $q = Finance::query();

    if ($documentType && $documentType !== 'all') {
        $q->where('type', $documentType);
    }

    if (!is_null($ignoreId)) {
        $q->where('id', '!=', $ignoreId);
    }

    $q->where(function ($sub) use ($clean) {
        foreach ($clean as $n) {
            $safe = preg_quote($n, '/');
            $sub->orWhereRaw("doc_no REGEXP ?", ["(^|,){$safe}(,|$)"]);
        }
    });

    $rows = $q->pluck('doc_no')->all();

    foreach ($rows as $docStr) {
        foreach (explode(',', $docStr) as $t) {
            $t = trim($t);
            if (in_array($t, $clean, true)) {
                $exists[$t] = true;
            }
        }
    }

    return [
        'exists' => array_keys($exists),
        'clean' => $clean
    ];
}

}
