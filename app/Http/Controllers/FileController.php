<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FileController extends Controller
{
    public function showPilmapresPreview()
    {
        $path = public_path('document/Panduan-Pilmapres-FST.pdf');

        if (!file_exists($path)) {
            abort(404);
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Panduan-Pilmapres-FST.pdf"'
        ];

        return Response::file($path, $headers);
    }
}
