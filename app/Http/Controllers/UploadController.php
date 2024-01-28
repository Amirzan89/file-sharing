<?php
namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\FileUpload;
use Closure;
use Carbon\Carbon;
class uploadController extends Controller
{
    //max file size in byte
    private $maxSize;
    private $extText = ['txt','md','docx','pptx'];
    private $extProgram = ['java'];
    private $extImage = ['png','jpg','jpeg'];
    private $extPDF = ['pdf'];
    private $pathImage  = 'public/image';
    private $pathPDF = 'public/pdf';
    private $pathText = 'public/text';
    private $pathProgram = 'public/program';
    private $pathRandom = 'public/random';
    public function __construct(){
        $this->maxSize = env('MAX_FILE_SIZE',10*1024*1024);
    }
    public function validation(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'format'=>'required',
                'size'=>'required',
            ], [
                'name.required' => 'Name File cannot be empty.',    
                'format.required' => 'Format File cannot be empty.',    
                'size.required' => 'Size File cannot be empty.',    
            ]);
            if ($validator->fails()) {
                $errors = [];
                foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                    $errors[$field] = $errorMessages[0];
                    break;
                }
                return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
            }
            // if(!in_array($request->input('format'),array_merge($this->extText, $this->extPDF, $this->extImage))){
            //     return response()->json(['status'=>'error','message'=>'invalid format file'],400);
            // }
            // if($request->input('input') > $this->maxSize){
            //     return response()->json(['status'=>'error','message'=>'File too large'],400);
            // }
            $id = Str::random(10);
            FileUpload::create(['temp_id' => $id,'file_name' => $request->input('name')]);
            return response()->json(['status'=>'success','message'=>'File valid','data'=>['id'=>$id]]);
        }catch(Exception $err){
            return response()->json(['status'=>'error','message'=>$err->getMessage()],400);
        }
    }
    protected function cleanupTemporaryFiles($directory) {
        $files = glob("{$directory}/*.part");
        foreach ($files as $file) {
            unlink($file);
        }
        rmdir($directory);
    }
    protected function assembleChunks($directory,$fileName) {
        $completeFilePath = storage_path("app/uploads/");
        if (!file_exists($completeFilePath)) {
            mkdir($completeFilePath, 0777, true);
        }
        $completeFilePath .= $fileName;
        $completeFile = fopen($completeFilePath, 'w');
        for ($i = 1; $i <= count(glob("{$directory}/*.part")); $i++) {
            $chunkPath = "{$directory}/{$i}.part";
            fwrite($completeFile, file_get_contents($chunkPath));
            unlink($chunkPath);
        }
        fclose($completeFile);
    }
    public function uploadChunk(Request $request) {
        $validator = Validator::make($request->only('file','currentChunk','totalChunks','identifier'), [
            'file' => 'required|file|max:2000',
            'currentChunk' => 'required|numeric',
            'totalChunks' => 'required|numeric',
            'identifier' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $currentChunk = $request->input('currentChunk');
        $totalChunks = $request->input('totalChunks');
        $file = $request->file('file');
        $identifier = $request->input('identifier');
        $fileUpload = FileUpload::select('file_name')->where('temp_id', $identifier)->first();
        if (!$fileUpload) {
            return response()->json(['status' => 'error', 'message' => 'Invalid file upload'], 400);
        }
        $directory = storage_path("app/temp/{$identifier}");
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        // Save the chunk to the temporary location
        $chunkPath = "{$directory}/{$currentChunk}.part";
        file_put_contents($chunkPath, $file->get());
        if ($currentChunk == $totalChunks) {
            $this->assembleChunks($directory, $fileUpload->file_name);
            $this->cleanupTemporaryFiles($directory);
            return response()->json(['status'=>'success','message' => 'Upload complete']);
        }
        return response()->json(['status'=>'success','message' => 'Chunk uploaded successfully']);
    }
    public function cancelUpload(Request $request){
        $validator = Validator::make($request->only('name','identifier'), [
            'name'=>'required',
            'identifier' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        FileUpload::where('temp_id', $request->input('identifier'))->delete();
        return response()->json(['status'=>'success','message' => 'Cancel Upload Success']);
    }
    public function deleteUpload(Request $request){
        $validator = Validator::make($request->only('name','identifier'), [
            'name'=>'required',
            'identifier' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $identifier = $request->input('identifier');
        $fileUpload = FileUpload::select('file_name')->where('temp_id', $identifier)->first();
        if (!$fileUpload) {
            return response()->json(['status' => 'error', 'message' => 'ID File not valid'], 400);
        }
        $path = storage_path("app/uploads/{$fileUpload->file_name}");
        if (file_exists($path)) {
            unlink($path);
        }
        FileUpload::where('temp_id', $identifier)->delete();
        return response()->json(['status'=>'success','message' => 'Delete Upload Success']);
    }
}