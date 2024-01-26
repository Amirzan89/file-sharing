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
                return response()->json(['status'=>'error','message'=>$validator->errors()->toArray()],400);
            }
            if(!in_array($request->input('format'),array_merge($this->extText, $this->extPDF, $this->extImage))){
                return response()->json(['status'=>'error','message'=>'invalid format file'],400);
            }
            if($request->input('input') > $this->maxSize){
                return response()->json(['status'=>'error','message'=>'File too large'],400);
            }
            $id = Str::random(10).now();
            Cache::put("file_upload_{$id}",$id,5*60);
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
    protected function assembleChunks($directory, $identifier) {
        $completeFilePath = storage_path("app/uploads/{$identifier}_complete");
        $completeFile = fopen($completeFilePath, 'w');
        for ($i = 1; $i <= count(glob("{$directory}/*.part")); $i++) {
            $chunkPath = "{$directory}/{$i}.part";
            fwrite($completeFile, file_get_contents($chunkPath));
            unlink($chunkPath);
        }
        fclose($completeFile);
    }
    public function uploadChunk(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:100',
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
        $directory = storage_path("app/temp/{$identifier}");
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        // Save the chunk to the temporary location
        $chunkPath = "{$directory}/{$currentChunk}.part";
        file_put_contents($chunkPath, $file->get());
        if ($currentChunk == $totalChunks) {
            $this->assembleChunks($directory, $identifier);
            $this->cleanupTemporaryFiles($directory);
            return response()->json(['status'=>'success','message' => 'Upload complete']);
        }
        return response()->json(['status'=>'success','message' => 'Chunk uploaded successfully']);
    }
}