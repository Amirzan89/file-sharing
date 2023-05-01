<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// use function PHPUnit\Framework\isEmpty;

class RegisterController extends Controller
{
    public function Register(Request $request, User $user){
        $id = $request->input("id");
        echo "usernammeeee $id <br>";
        // echo isempty($id)."br>";
        // echo isset($id)."br>";
        $email = $request->input("email");
        $nama = $request->input("nama");
        // $email = "Admin@gmail.com";
        $pass = $request->input("pass");
        $pass1 = $request->input("pass_new");
        if(!isset($id)){
            echo "id tidak boleh kosong <br>";
            // return redirect('/register',302,["error"=>'Username tidak boleh kosong']);
        }else if(!isset($email)){
            echo "email tidak boleh kosong <br>";
            // return redirect('/register',302,["error"=>'Email tidak boleh kosong']);
        }else if(!isset($nama)){
            echo "nama tidak boleh kosong <br>";
            // return redirect('/register',302,["error"=>'nama tidak boleh kosong']);
        }else if(!isset($pass)){
            echo "password tidak boleh kosong <br>";
            // return redirect('/register',302,["error"=>'Password tidak boleh kosong']);
        }else if(!isset($pass1)){
            echo "password tidak boleh kosong <br>";
            // return redirect('/register',302,["error"=>'Password tidak boleh kosong']);
        }else if (User::select("id")->where('id','LIKE','%'.$id.'%')->limit(1)->exists()){
            // if(DB::table("users")->select("id")->where('email','=',$id)->limit(1)->exists()){
            echo "id sudah digunakan <br>";
            // throw
            // return redirect('/register',302,["error"=>'Username sudah digunakan']);
        }else{
            if($pass === $pass1){
                // DB::table("users")->select("id")->where('email','=',$email)->limit(1)->get();
                $user->id = $id;
                $user->email = $email;
                $user->nama_users = $nama;
                $user->password = Hash::make($pass);
                if($user->save()){
                    echo "akun berhasil dibuat <br>";
                    return redirect('/login',302,["success"=>'Akun berhasil dibuat']);
                }else{
                    echo "akun gagal dibuat <br>";
                    return redirect('/register',302,["error"=>'Akun gagal dibuat']);
                }
            }else{
                echo "Password harus sama <br>";
                // return redirect('/register',302,["error"=>'Password Harus Sama']);
            }
        }
        // return view('welcome');
    }
}
?>