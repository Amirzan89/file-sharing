<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
class DownloadController extends Controller
{
    private $pathImage  = 'public/image';
    private $pathPDF = 'public/pdf';
    private $pathProgram = 'public/program';
    public function download(Request $req, Response $res){
        if($req->hasFile('myFiles')){
            $files = $req->file('myFiles');
            $fileName = pathinfo($files, PATHINFO_FILENAME);
            $ext = $files->getClientOriginalExtension();
            $fileNameStore = $fileName.'-'.time().'.'.$ext;
            $path = $files->move($this->pathPDF, $fileNameStore);
            return response()->json(['success' => true]);
        }
    }
}