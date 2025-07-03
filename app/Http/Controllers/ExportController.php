<?php

namespace App\Http\Controllers;

use App\Exports\EmbarkExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportEmbark()
    {
        return Excel::download(new EmbarkExport, 'embark.csv');
    }
}
