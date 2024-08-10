<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\CustomersImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerImportController extends Controller
{
    /**
     * Handle the import request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new CustomersImport, $request->file('file'));

            return response()->json(['message' => 'Import was successful.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'There was an error with the import: ' . $e->getMessage()], 500);
        }
    }
}
