<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class uploadController extends Controller
{
    //max file size in byte
    private $maxSize;
    private $extText = ['txt','md','docx','pptx'];
    private $extProgram = ['java','js','php'];
    private $extImage = ['png','jpg','jpeg'];
    private $extPDF = 'pdf';
    private $pathImage  = 'public/image';
    private $tempFolder = 'img/tmp/';
    private $pathPDF = 'public/pdf';
    private $pathText = 'public/text';
    private $pathProgram = 'public/program';
    private $pathRandom = 'public/random';
    public function __construct(){
        $this->maxSize = env('MAX_FILE_SIZE',10*1024*1024);
    }
    public function validateUpload(Request $req,Response $res){
        if($req->hasFile('myFiles')){
            $files = $req->file('myFiles');
            $errorMsg = array();
            $replaceMsg = array();
            $idMsg = array();
            //validate each file
            foreach($files as $file){
                $ext = $file->getClientOriginalExtension();
                $fileName = $file->getClientOriginalName().$ext;
                //validate size
                if($file->getSize() > $this->maxSize){
                    array_push($errorMsg,[
                    'size-file'=>'File ' . $fileName . ' is too large',
                    'size'=>$file->getSize()
                    ]);
                }
                //validate existing file
                if(Storage::exists($fileName)){
                    array_push($replaceMsg,[
                        'exist-file'=>'File ' . $fileName . ' is exist',
                    ]);
                }else{
                    array_push($idMsg,[
                    'id-file'=>uniqid().'-'.time()]);
                }
            }
            // Check the results after the foreach loop completes
            if(!empty($errorMsg)){
                return response()->json(['status'=>'error','data' => $errorMsg], 413);
            }else if(!empty($replaceMsg)){
                return response()->json(['status'=>'success','data'=>$replaceMsg],200);
            }else{
                return response()->json(['status'=>'success','data'=>$idMsg],200);
            }
        }
    }
    public function upload(Request $req, Response $res){
        if($req->hasFile('myFiles')){
            $files = $req->file('myFiles');
            $this->validateSize($files);
            $fileName = $files->getClientOriginalName();
            $ext = $files->getClientOriginalExtension();
            if($this->extPDF === $ext){
                $path = $files->storeAs($this->pathPDF, $fileName);
            }
            foreach($this->extImage as $Ext){
                if($Ext === $ext){
                    $path = $files->storeAs($this->pathImage, $fileName);
                }
            }
            foreach($this->extProgram as $Ext){
                if($Ext === $ext){
                    $path = $files->storeAs($this->pathProgram, $fileName);
                }
            }
            foreach($this->extText as $Ext){
                if($Ext === $ext){
                    $path = $files->storeAs($this->pathText, $fileName);
                }
            }
            $path = $files->storeAs($this->pathRandom, $fileName);
            return response()->json(['message'=>$ext,'path'=>$path],200);
        } else {
            return response()->json(['message' => 'No files were uploaded'], 400);
        }
    }
    public function uploadPond(Request $request, Response $response){
        $files = $request->file('files');
        $fileName = $files->getClientOriginalName();
        $folder = uniqid().'-'.now()->timestamp;
        $files->storeAs('/img'.$folder,$fileName);
        return $folder;
    }
    // $folder = uniqid(). '-'. now()->timestamp;
}