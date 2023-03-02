<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserQuestion;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select(['id', 'name', 'email'])->get();

        return response()->json(['success' => true, 'msg' => 'Listando todos os usuários', 'data' => $users]);
    }

    public function saveFeedback(Request $request)
    {
        /** @var User */
        $user = Auth::user();

        $user->feedback = $request->get('feedback');
        $user->save();

        return response()->json([
            'success' => true,
            'msg' => $user->name . ', muito obrigado pelo seu feedback!',
            'nota' => DB::select(DB::raw("SELECT SUM(valor) AS nota FROM user_questions WHERE user_id = $user->id"))[0]->nota,
            'media' => Question::selectRaw("SUM(valor) AS total")->first()->total * 0.6,
        ], 200);
    }

    public function getNota()
    {
        $user = Auth::user();

        $nota = DB::select(DB::raw("SELECT SUM(valor) AS nota FROM user_questions WHERE user_id = $user->id"))[0]->nota;

        if ($nota == null) {
            return response()->json([
                'success' => false,
                'msg' => 'Nenhuma nota encontrada',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'msg' => 'OK!',
            'nota' => $nota,
            'media' => Question::selectRaw("SUM(valor) AS total")->first()->total * 0.6,
        ], 200);
    }
}
