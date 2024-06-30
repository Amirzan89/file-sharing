<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Pricing;
// use App\Models\Cart;
// use App\Models\DetailCart;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\User;
use Midtrans\Snap;
use Midtrans\Notification;
use Carbon\Carbon;
class TransaksiController extends Controller
{
    private function timezoneConverter($input){
        return '';
    }
    // public function cekTransaksi(Request $request){
    //     $transaksiDB = Transaksi::select('updated_at')->where('uuid', $request->input('id_transaksi'))->first();
    //     //check if expired or more than 1 day
    //     if($transaksiDB->updated_at >= ){
    //         //if expired then delete transaksi
    //         Transaksi::where()->delete();
    //         return response()->json(['status'=>'error', 'message'=>'Transaksi Expired'], 400);
    //     }
    //     return response()->json(['status'=>'success', 'message', 'message'=>'Transaksi']);
    // }
    public function createTransaksi(Request $request){
        $validator = Validator::make($request->only('id_pricing', 'jumlah'), [
            'id_pricing' => 'required',
            'jumlah' => 'required|integer|min=1',
        ], [
            'id_pricing.required' => 'ID Pricing are required',
            'jumlah.required' => 'Jumlah are required',
            'jumlah.integer' => 'Jumlah must be an integer',
            'jumlah.min' => 'Jumlah must be at least 1.',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $idUser = User::select("id_user")->whereRaw("BINARY email = ?",[$request->input('user_auth')['email']])->first()['id_user'];
        $pricingDb = Pricing::select("id_pricing", 'price')->where("uuid ",$request->input('id_pricing'))->first();
        if (is_null($pricingDb)) {
            return response()->json(['status' =>'error','message'=>'Data Pricing tidak ditemukan'], 400);
        }
        $uId = uniqid();
        $idTransaksi = Transaksi::insert([
            'uuid' => Str::uuid(),
            'order_id' => $uId,
            'total' => $pricingDb['price'],
            'status' => 'pending',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'id_pricing' => $pricingDb['id_pricing'],
            'id_user' => $idUser,
        ]);
        if(!$idTransaksi){
            return response()->json(['status'=>'error','message'=>'Gagal menambahkan data Transaksi'], 500);
        }
        $params = [
            'transaction_details' => [
                'order_id' => $uId,
                'gross_amount' => $pricingDb['price'],
            ],
            'customer_details' => [
                'first_name' => $request->input('user_auth')['nama_lengkap'],
                // 'last_name' => $request->last_name,
                'email' => $request->input('user_auth')['email'],
                'phone' => $request->input('user_auth')['no_telpon'],
            ],
        ];
        return response()->json(['status'=>'success','message'=>'Data Transaksi berhasil ditambahkan', 'data'=>['token'=>Snap::getSnapToken($params)]]);
    }
    public function updateTransaksi(Request $request){
        $validator = Validator::make($request->only('id_transaksi', 'id_pricing', 'jumlah'), [
            'id_transaksi' => 'required',
            'id_pricing' => 'required',
            'jumlah' => 'required|integer|min=1',
        ], [
            'id_transaksi.required' => 'ID Transaction are required',
            'id_pricing.required' => 'ID Pricing are required',
            'jumlah.required' => 'Jumlah are required',
            'jumlah.integer' => 'Jumlah must be an integer',
            'jumlah.min' => 'Jumlah must be at least 1.',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) { 
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $transaksii = Transaksi::where('uuid',$request->input('id_transaksi'))->first();
        if (is_null($transaksii)) {
            return response()->json(['status' =>'error','message'=>'Data Transaksi tidak ditemukan'], 400);
        }
        $edit = $transaksii->update([
            'updated_at' => Carbon::now(),
        ]);
        if(!$edit){
            return response()->json(['status' =>'error','message'=>'Gagal memperbarui data Transaksi'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data Transaksi berhasil di perbarui']);
    }
    public function prosesTransaksi(Request $request){
        //
    }
    public function midtransNotify(Request $request){
        if(hash('sha512', $request->order_id.$request->status_code.$request->gross_amout.env('MIDTRANS_SERVER_KEY')) == $request->signature_key && $request->transaction_status == 'capture'){
            Transaksi::where('order_id', $request->order_id)->update(['status'=>'success', 'updated_at'=>Carbon::now()]);
        }
    }
    public function cancelUser(Request $request){
        $validator = Validator::make($request->only('id_transaksi'), [
            'id_transaksi' => 'required',
        ], [
            'id_transaksi.required' => 'ID Transaksi wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $transaksii = Transaksi::where('uuid',$request->input('id_transaksi'))->first();
        $edit = $transaksii->update([
            'updated_at' => Carbon::now(),
        ]);
        if(!$edit){
            return response()->json(['status' =>'error','message'=>'Gagal memperbarui data Transaksi'], 500);
        }
        return response()->json(['status' =>'success','message'=>'Data Transaksi berhasil di perbarui']);
    }
    public function deleteTransaksi(Request $request){
        $validator = Validator::make($request->only('id_transaksi'), [
            'id_transaksi' => 'required',
        ], [
            'id_transaksi.required' => 'ID Transaksi wajib di isi',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        //check data transaksi
        // $transaksii = Transaksi::select('nama')->where('users.email', $request->input('email'))->where('shop.id_transaksi', $request->input('id_transaksi'))->join('users', 'shop.id_user', '=', 'users.id_user')->first();
        $transaksii = Transaksi::where('uuid',$request->input('uuid'))->first();
        if (is_null($transaksii)) {
            return response()->json(['status' =>'error','message'=>'Data Transaksi tidak ditemukan'], 400);
        }
        if (!$transaksii->delete()) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data Transaksi'], 500);
        }
        return response()->json(['status' => 'success', 'message' => 'Data Transaksi berhasil dihapus']);
    }
}