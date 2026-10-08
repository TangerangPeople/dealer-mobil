<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseApplication;
use App\Models\PurchaseApplicationDocument;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PurchaseApplicationDocumentController extends Controller
{
    public function download(PurchaseApplication $application, PurchaseApplicationDocument $document): StreamedResponse
    {
        $authorizedDocument = $application->documents()->findOrFail($document->getKey());

        abort_unless(Storage::disk('local')->exists($authorizedDocument->file_path), 404);

        return Storage::disk('local')->download($authorizedDocument->file_path, $authorizedDocument->original_name, [
            'Content-Type' => $authorizedDocument->mime_type,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
