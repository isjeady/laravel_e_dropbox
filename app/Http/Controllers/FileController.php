<?php

namespace App\Http\Controllers;

use App\Model\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{


    public function list(Request $request){
            $files = FileUpload::where(['source' => 'source', 'user_id' => Auth::user()->id])->get();
            $fileOutput = [];
            foreach($files as $file){
                $obj = [
                    "id"   => $file->id,
                    "name" => $file->file_name,
                    "nameFile" => $file->file_name,
                    //"size" => Storage::disk('dropbox')->size($file),
                    //"lastModified" => date('m/d/Y', Storage::disk('dropbox')->lastModified($file)),
                ];
                array_push($fileOutput,$obj);
            }
            return response()->json($fileOutput, 200);
    }

    public function listCloud(Request $request){
            $files = Storage::disk('dropbox')->allFiles(env('DROPBOX_FOLDER'));
            $fileOutput = [];
            foreach($files as $file){
                $obj = [
                    "id"   => '24',
                    "name" => $file,
                    "nameFile" => $file,
                    //"size" => Storage::disk('dropbox')->size($file),
                    //"lastModified" => date('m/d/Y', Storage::disk('dropbox')->lastModified($file)),
                ];
                array_push($fileOutput,$obj);
            }
            return response()->json($fileOutput, 200);

    }

    public function get(Request $request,$idFile){
        $file = FileUpload::find($idFile);
        if(!$file)
            return response()->json('File Not Found', 400);
        $fileName = $file ->file_name;
        $path = env('DROPBOX_FOLDER') ."/". $fileName;
        if(Storage::disk('dropbox')->exists($path)){
            return Storage::disk('dropbox')->download($path);
        }else{
            return response()->json('File Not Found', 400);
        }
    }

    public function delete(Request $request,$idFile){
        $file = FileUpload::find($idFile);
        if(!$file)
            return response()->json('File Not Found', 400);
        $fileName = $file ->file_name;
        $path = env('DROPBOX_FOLDER') ."/". $fileName;
        if(Storage::disk('dropbox')->exists($path)){
            Storage::disk('dropbox')->delete($path);
            $file->delete();
            return response()->json('Success', 200);
        }else{
            return response()->json('File Not Found', 400);
        }
    }

    public function store(Request $request){

        if($request->hasFile('files')){
            $fileArray = $request->file('files');

            foreach($fileArray as $file){
                try{
                    if($file->isValid()){
                        $fileName = $file->getClientOriginalName();//.".". $file->getClientOriginalExtension();
                        //$fileName = time() . str_random(12).".". $file->getClientOriginalExtension();
                        $path= Storage::disk('dropbox')->putFileAs('source',$file,$fileName);
                        //$path= Storage::disk('dropbox')->put('source',$file);
                        $files = FileUpload::where('file_name',$fileName)->first();

                        if($path != null && $files == null){
                            $fileUpload = new FileUpload();
                            $fileUpload->file_name= $fileName;
                            $fileUpload->user_id= Auth::user()->id;
                            $fileUpload->save();
                        }

                    }
                }catch(\Excpetion $ex){

                }
            }

            return response()->json(['success' => 'You have successfully uploaded File'.$path], 200);
       }
       return response()->json([‘upload_file_not_found’], 400);
    }
}
