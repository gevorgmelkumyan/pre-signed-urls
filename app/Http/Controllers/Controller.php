<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreSignedUrlRequest;
use App\Http\Requests\UpdateFileStatusRequest;
use App\Models\File;
use Aws\S3\S3Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public function respond($data = null, int $status = 200): JsonResponse {
        return response()->json($data ?? [], $status);
    }

    public function getFiles(): JsonResponse {
        return $this->respond(File::query()->latest()->get());
    }

    public function generatePreSignedUrl(PreSignedUrlRequest $request): JsonResponse {
        $fileName = str_replace(' ', '_', $request->file_name);

        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'endpoint' => env('AWS_ENDPOINT'),
        ]);

        $cmd = $s3Client->getCommand('PutObject', [
            'Bucket' => env('AWS_BUCKET'),
            'Key' => 'uploads/' . $fileName,
            'ContentType' => $request->file_type
        ]);

        $preSignedRequest = $s3Client->createPresignedRequest($cmd, now()->addMinutes(5));
        $uri = $preSignedRequest->getUri();
        $path = str_replace('/' . env('AWS_BUCKET'), '', $uri->getPath());

        /** @var File $file */
        $file = File::query()->create([
            'name' => $fileName,
            'path' => $path,
        ]);

        return $this->respond([
            'file_id' => $file->id,
            'url' => (string) $uri,
        ]);
    }

    public function updateFileStatus(File $file, UpdateFileStatusRequest $request): JsonResponse {
        $file->update(['status' => $request->status]);

        return $this->respond($file);
    }
}
