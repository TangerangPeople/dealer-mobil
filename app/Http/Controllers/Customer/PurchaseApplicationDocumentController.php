<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PurchaseApplication;
use App\Models\PurchaseApplicationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PurchaseApplicationDocumentController extends Controller
{
    public function download(Request $request, PurchaseApplication $application, PurchaseApplicationDocument $document): StreamedResponse
    {
        $ownedApplication = $request->user('customer')
            ->purchaseApplications()
            ->findOrFail($application->getKey());
        $ownedDocument = $ownedApplication->documents()->findOrFail($document->getKey());

        abort_unless(Storage::disk('local')->exists($ownedDocument->file_path), 404);

        return Storage::disk('local')->download($ownedDocument->file_path, $ownedDocument->original_name, [
            'Content-Type' => $ownedDocument->mime_type,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
