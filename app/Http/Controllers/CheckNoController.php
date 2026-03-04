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

        // optional dynamic filter
        $filterField = $request->input('filter_field');
        $filterValue = $request->input('filter_value');

        $clean = collect(explode(';', $raw))
            ->map(fn($v) => trim($v))
            ->filter()
            ->values()
            ->all();

        $exists = [];
        if (!empty($clean)) {
            $check = $service->check(
                implode(';', $clean),
                $documentType,
                $ignoreId,
                $filterField,
                $filterValue
            );
        }

        return response()->json([
            'checked_count' => count($clean),
            'document_type' => $documentType,
            'ignore_id' => $ignoreId,
            'filter_field' => $filterField,
            'filter_value' => $filterValue,
            'invalid' => [],
            'exists' => $check['exists'] ?? [],
            'makers' => $check['makers'] ?? [],
        ]);
    }
}
