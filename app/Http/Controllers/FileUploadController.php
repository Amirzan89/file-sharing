<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class FileUploadController extends Controller {
    //path : upload-request
    public function initiateUploadRequest(Request $request) {
        if (!$request->has('fileName')) {
            return response()->json(['message' => 'Missing "fileName"'], 400);
        }
        $fileId = Str::random(24);
        // $fileId = generateUniqueAlphaNumericId();
        $this->createEmptyFile($this->getFilePath($request->input('fileName'), $fileId));
        return response()->json(['fileId' => $fileId]);
    }
    //path : upload-status
    public function checkUploadStatus(Request $request) {
        $fileName = $request->query('fileName');
        $fileId = $request->query('fileId');
        if (!$fileName || !$fileId) {
            return response()->json(['message' => 'Missing "fileName" or "fileId"'], 400);
        }

        try {
            $fileSize = $this->getFileSize($this->getFilePath($fileName, $fileId));
            return response()->json(['totalChunkUploaded' => $fileSize]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'No file with such credentials'], 400);
        }
    }
    private function getFilePath($fileName, $fileId) {
        return 'uploads/' . $fileId . '_' . $fileName; // Define your file path structure
    }

    private function createEmptyFile($filePath) {
        Storage::put($filePath, ''); // Create an empty file using Laravel's Storage facade
    }
    //path : upload
    public function uploadFileChunk(Request $request) {
        $contentRange = $request->header('content-range');
        $fileId = $request->header('x-file-id');

        if (!$contentRange) {
            return response()->json(['message' => 'Missing "Content-Range" header'], 400);
        }

        if (!$fileId) {
            return response()->json(['message' => 'Missing "X-File-Id" header'], 400);
        }

        $match = preg_match('/bytes=(\d+)-(\d+)\/(\d+)/', $contentRange, $matches);

        if (!$match) {
            return response()->json(['message' => 'Invalid "Content-Range" Format'], 400);
        }

        $rangeStart = (int)$matches[1];
        $rangeEnd = (int)$matches[2];
        $fileSize = (int)$matches[3];

        if ($rangeStart >= $fileSize || $rangeStart >= $rangeEnd || $rangeEnd > $fileSize) {
            return response()->json(['message' => 'Invalid "Content-Range" provided'], 400);
        }

        $file = $request->file('fileName'); // Assuming your file input field name is "file"
        $filename = $file->getClientOriginalName(); // Get the original filename
        $filePath = $this->getFilePath($filename, $fileId);

        try {
            $stats = $this->getFileDetails($filePath);
            if ($stats->getSize() !== $rangeStart) {
                return response()->json(['message' => 'Bad "chunk" provided'], 400);
            }

            // Append the file chunk
            Storage::append($filePath, $file->getContent());
        } catch (\Exception $e) {
            return response()->json(['message' => 'No file with such credentials'], 400);
        }

        return response()->json([], 200);
    }
    // public function uploadFileChunk(Request $request) {
    //     $contentRange = $request->header('content-range');
    //     $fileId = $request->header('x-file-id');

    //     if (!$contentRange) {
    //         return response()->json(['message' => 'Missing "Content-Range" header'], 400);
    //     }

    //     if (!$fileId) {
    //         return response()->json(['message' => 'Missing "X-File-Id" header'], 400);
    //     }

    //     $match = preg_match('/bytes=(\d+)-(\d+)\/(\d+)/', $contentRange, $matches);

    //     if (!$match) {
    //         return response()->json(['message' => 'Invalid "Content-Range" Format'], 400);
    //     }

    //     $rangeStart = (int)$matches[1];
    //     $rangeEnd = (int)$matches[2];
    //     $fileSize = (int)$matches[3];

    //     if ($rangeStart >= $fileSize || $rangeStart >= $rangeEnd || $rangeEnd > $fileSize) {
    //         return response()->json(['message' => 'Invalid "Content-Range" provided'], 400);
    //     }

    //     $busboy = new Busboy($request->header(), /* ... */);

    //     $busboy->on('file', function ($fieldname, $file, $filename, $encoding, $mimetype) use ($fileId, $rangeStart) {
    //         $filePath = getFilePath($filename, $fileId);

    //         if (empty($fileId)) {
    //             // Pause request
    //             // $request->pause();
    //         }

    //         try {
    //             $stats = getFileDetails($filePath);
    //             if ($stats->getSize() !== $rangeStart) {
    //                 return response()->json(['message' => 'Bad "chunk" provided'], 400);
    //             }

    //             // Write the file chunk
    //             $fileStream = fopen($filePath, 'ab');
    //             stream_copy_to_stream($file, $fileStream);
    //             fclose($fileStream);

    //         } catch (\Exception $e) {
    //             return response()->json(['message' => 'No file with such credentials'], 400);
    //         }
    //     });

    //     $busboy->on('finish', function () {
    //         return response()->json([], 200);
    //     });

    //     $request->headers->set('content-type', 'multipart/form-data');
    //     $request->headers->set('content-transfer-encoding', 'binary');

    //     $busboy->end($request->getContent());
    // }
    private function getFileDetails($filePath) {
        $disk = Storage::disk('local'); // Adjust the disk as needed
    
        // Check if the file exists
        if ($disk->exists($filePath)) {
            $size = $disk->size($filePath);
            $contents = $disk->get($filePath);
    
            return ['size' => $size, 'contents' => $contents];
        } else {
            throw new \Exception('File not found');
        }
    }
    
    // private function getFileDetails($filePath) {
    //     return Storage::disk('local')->getMetadata($filePath); // Adjust the disk as needed
    // }

}
