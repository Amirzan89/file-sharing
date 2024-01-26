<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class FileUploadController extends Controller {
    //path : upload-request
    public function initiateUploadRequest(Request $request) {
        if (!$request->has('fileName')) {
            return response()->json(['message' => 'Missing "fileName"'], 400);
        }
        $fileId = Str::random(24);
        $this->createEmptyFile($this->getFilePath($request->input('fileName'), $fileId));
        return response()->json(['fileId' => $fileId]);
    }
    //path : upload-status
    private function createEmptyFile($filePath) {
        Storage::put($filePath, ''); // Create an empty file using Laravel's Storage facade
    }
    // private function getFilePath($fileName, $fileId) {
    //     return 'uploads/' . $fileId . '_' . $fileName; // Define your file path structure
    // }

    // //path : upload
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
    //     $file = $request->file('fileName'); // Assuming your file input field name is "file"
    //     $filename = $file->getClientOriginalName(); // Get the original filename
    //     $filePath = $this->getFilePath($filename, $fileId);
    //     try {
    //         $stats = $this->getFileDetails($filePath);
    //         if ($stats->getSize() !== $rangeStart) {
    //             return response()->json(['message' => 'Bad "chunk" provided'], 400);
    //         }
    //         // Append the file chunk
    //         Storage::append($filePath, $file->getContent());
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'No file with such credentials'], 400);
    //     }
    //     return response()->json([], 200);
    // }
    // private function getFileDetails($filePath) {
    //     $disk = Storage::disk('local'); // Adjust the disk as needed
    //     // Check if the file exists
    //     if ($disk->exists($filePath)) {
    //         $size = $disk->size($filePath);
    //         $contents = $disk->get($filePath);
    //         return ['size' => $size, 'contents' => $contents];
    //     } else {
    //         throw new \Exception('File not found');
    //     }
    // }
    public function uploadChunk(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:100048',
            'currentChunk' => 'required|numeric',
            'totalChunks' => 'required|numeric',
            'identifier' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->toArray()], 400);
        }
        $currentChunk = $request->input('currentChunk');
        $totalChunks = $request->input('totalChunks');
        $file = $request->file('file');
        $identifier = $request->input('identifier');
        // Ensure a directory exists for this identifier
        $directory = storage_path("app/uploads/{$identifier}");
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        // Save the chunk to the temporary location
        $chunkPath = "{$directory}/{$currentChunk}.part";
        file_put_contents($chunkPath, $file->get());
        // If this is the last chunk, handle the complete file
        if ($currentChunk == $totalChunks) {
            $this->assembleChunks($directory, $identifier);
            // Optionally: Clean up temporary chunk files
            $this->cleanupTemporaryFiles($directory);
            return response()->json(['message' => 'Upload complete']);
        }
        return response()->json(['message' => 'Chunk uploaded successfully']);
    }
    protected function cleanupTemporaryFiles($directory) {
        $files = glob("{$directory}/*.part");
        foreach ($files as $file) {
            unlink($file);
        }
        rmdir($directory); // Remove the directory
    }
    protected function assembleChunks($directory, $identifier) {
        $completeFilePath = storage_path("app/uploads/{$identifier}_complete");
        $completeFile = fopen($completeFilePath, 'w');
        for ($i = 1; $i <= count(glob("{$directory}/*.part")); $i++) {
            $chunkPath = "{$directory}/{$i}.part";
            fwrite($completeFile, file_get_contents($chunkPath));
            unlink($chunkPath); // Remove the processed chunk
        }
        fclose($completeFile);
    }
}
