<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\CustomersExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CustomerExportController extends Controller
{
    /**
     * Handle the export request.
     *
     * @return Response|BinaryFileResponse
     */
    public function export(Request $request)
    {
        $data = $request->input('contacts');
        return Excel::download(new CustomersExport($data), 'customers.xlsx');
    }
}
