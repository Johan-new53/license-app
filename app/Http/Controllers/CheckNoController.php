<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DocNoCheckService;

class CheckNoController extends Controller
{
    public function checkDocNo(Request $request, DocNoCheckService $service)
    {
        $raw = (string) $request->input('doc_no', '');
        $documentType = (string) $request->input('document_type', 'all');

        $ignoreId = $request->input('ignore_id');
        $ignoreId = $ignoreId !== null ? (int) $ignoreId : null;

        // normalize
        $clean = collect(explode(',', $raw))
            ->map(fn($v) => trim($v))
            ->filter()
            ->values()
            ->all();

        $exists = [];
        if (!empty($clean)) {
            $check = $service->check(implode(',', $clean), $documentType, $ignoreId);
            $exists = $check['exists'] ?? [];
        }

        return response()->json([
            'checked_count' => count($clean),
            'document_type' => $documentType,
            'ignore_id' => $ignoreId,
            'invalid' => [],
            'exists' => $exists,
        ]);
    }
}
