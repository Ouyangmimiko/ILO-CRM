<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\RecordsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export(Request $request) {
        $validatedRecords = $request->validate([
            'records' => 'required|array',
            'records.*' => 'array',
        ]);

        $records = $request->input('records');
        $export = new RecordsExport($records);
        return Excel::download($export, 'engagement_status.xlsx');
    }
}
