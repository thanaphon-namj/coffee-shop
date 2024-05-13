<?php

namespace App\Http\Controllers;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $file = $request->file('file');

        $s3Client = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $fileName = 'uploads/' . $file->getClientOriginalName();

        try {
            $result = $s3Client->putObject([
                'Bucket' => env('AWS_BUCKET'),
                'Key' => $fileName,
                'Body' => fopen($file, 'r'),
                'ACL' => 'public-read',
            ]);

            return response()->json([
                'success' => true,
                'url' => $result['ObjectURL'],
            ]);
        } catch (S3Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
