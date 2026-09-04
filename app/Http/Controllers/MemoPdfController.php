<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\cpar_memo; // palitan base sa tamang model class name mo
use Barryvdh\DomPDF\Facade\Pdf as PdfFacade;

class MemoPdfController extends Controller
{
    public function print($id)
    {
        $memo = cpar_memo::findOrFail($id);

        $pdf = PdfFacade::loadView('pdf.memo', [
            'content' => $memo->memo_content,
        ]);

        return $pdf->stream('memo.pdf');
    }
}
