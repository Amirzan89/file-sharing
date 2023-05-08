<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Closure;
class uploadController extends Controller
{
    //max file size in byte
    private $maxSize;
    private $extText = ['txt','md','docx','pptx'];
    private $extProgram = ['java','js','php'];
    private $extImage = ['png','jpg','jpeg'];
    private $extPDF = 'pdf';
    private $pathImage  = 'public/image';
    private $pathPDF = 'public/pdf';
    private $pathText = 'public/text';
    private $pathProgram = 'public/program';
    private $pathRandom = 'public/random';
    public function __construct(){
        $this->maxSize = env('MAX_FILE_SIZE',10*1024*1024);
    }
    public function validateUpload(Request $req,Response $res){
        try{
            $out = array();
            // $data = collect($req->json()->all());
            $data = $req->json()->all();
            // $data->each(function($item, $key) use(&$out){
            foreach($data as $item){
                $size = 0;
                $id = '';
                $fileName = '';
                $ext = '';
                if(isset($item['name'])){
                    $fileName = $item['name'];
                }
                if(isset($item['id'])){
                    $id = $item['id'];
                }
                if(isset($item['size'])){
                    $size = $item['size'];
                }
                if(isset($item['ext'])){
                    $ext = $item['ext'];
                }
                // $id = $item->get('id');
                // $fileName = $item->get('name');
                // $ext = $item->get('ext');
                // $size = $item->get('size');
                // $id = $item->value('id');
                // $fileName = $item->value('name');
                // $ext = $item->value('ext');
                // $size = $item->value('size');
                //if file exist
                // if(Storage::exists($fileName)){
                //     array_push($out,['id'=>$id,'status'=>'File is exist']);
                // }
                if($size > $this->maxSize){
                    array_push($out,['id'=>$id,'status'=>'File is to large']);
                }else{
                    array_push($out,['id'=>$id,'status'=>'File is ok']);
                }
            }
            // });
            return response()->json($out,200);
        }catch(\Exception $err){
            return response()->json(['error' => $err->getMessage()], 500);
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