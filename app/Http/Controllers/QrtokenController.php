<?php

namespace App\Http\Controllers;

use App\Models\Qrtoken;
use Illuminate\Http\Request;

class QrtokenController extends Controller
{
    public function index(Request $request)
    {
        $created_by = $request->created_by;

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $new_generate_code = substr(str_shuffle(str_repeat($pool, 5)), 0, 5);

        // save or update data @ database
        $alredyToken = Qrtoken::where('created_by', $created_by)->first();
        if ($alredyToken) {
            $qrcode = Qrtoken::find($alredyToken->id);
            $qrcode->token      = $new_generate_code;
            $qrcode->save();
        } else {
            $qrcode             = new Qrtoken();
            $qrcode->token      = $new_generate_code;
            $qrcode->created_by = $created_by;
            $qrcode->save();
        }

        return response()->json([
            'token' => $new_generate_code
        ]);
    }
}
