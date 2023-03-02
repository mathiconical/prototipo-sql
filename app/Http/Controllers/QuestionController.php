<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use DB;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\User;
use App\Models\UserQuestion;
use Auth;

use function PHPUnit\Framework\objectHasAttribute;

class QuestionController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin) {
            $questions = Question::select(['id', 'pergunta', 'valor', 'resposta'])->get();
        } else {
            $questions = Question::select(['id', 'pergunta', 'valor'])->get();
        }

        return response()->json(['success' => true, 'msg' => 'Listando todas as questions', 'data' => $questions]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pergunta' => 'required',
            'valor' => 'required',
            'resposta' => 'required',
        ]);

        $question = Question::updateOrCreate($request->all());

        return response()->json(['success' => true, 'msg' => 'Informações salvas!', 'data' => $question]);
    }

    public function show($id)
    {
        $question = Question::findOrFail($id);

        return response()->json(['success' => true, 'data' => $question]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'pergunta' => 'required',
            'valor' => 'required',
            'resposta' => 'required',
        ]);

        $question = Question::findOrFail($id)->update($request->all());

        return response()->json(['success' => true, 'msg' => 'Informação atualizada!', 'data' => $question]);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);

        $question->delete();

        return response()->json(['success' => true, 'msg' => 'Informação removida com sucesso!', 'data' => $question]);
    }

    public function dashboard()
    {
        $questions = Question::select(['id', 'pergunta', 'valor'])->get();

        $questionsWithoutAnswer = [];

        /** @var User */
        $user = Auth::user();
        $user_id = $user->id;

        if ($user->isAdmin) {
            $commonUsers = User::select(['id', 'name', 'email', 'feedback'])->where('isAdmin', '=', 0)->get();
            $questions = Question::select(['id', 'pergunta', 'valor', 'resposta'])->get();

            foreach ($commonUsers as $commonUser) {
                $commonUser->totalQuestionsAnswered = count($commonUser->questions);
                $commonUser->nota = $commonUser->nota();
                $commonUser->questions = $commonUser->questions;
            }

            return view('adminDashboard', [
                'users' => $commonUsers,
                'userQuestions' => $questions,
            ]);
        }

        foreach ($questions as $question) {
            $user_questions_ids = DB::select(DB::raw("SELECT question_id FROM user_questions WHERE user_id = $user_id AND question_id = $question->id"));
            if (count($user_questions_ids) > 0) {
                continue;
            }
            $resposta = Question::find($question->id)->resposta;
            $question->columns = $this->getExpectedColumnsFromQuery($resposta);
            array_push($questionsWithoutAnswer, $question);
        }

        return view('dashboard', [
            'questions' => $questionsWithoutAnswer,
            'gaveFeedback' => !strlen($user->feedback) ? false : true,
        ]);
    }

    public function runQuery(Request $request)
    {
        $request->validate([
            'question_id' => 'required',
            'query' => 'required',
        ], [
            'question_id.required' => 'Questão sem ID válido.',
            'query.required' => 'Questão sem QUERY válida.',
        ]);

        try {
            $query = $request->get('query');

            $pdo = DB::connection()->getPdo();

            $stmt = $pdo->prepare($query);
            $stmt->execute();

            $record_count = $stmt->rowCount();

            $columns = array();
            for ($i = 0; $i < $stmt->columnCount(); $i++) {
                $col = $stmt->getColumnMeta($i);
                $columns[] = ['name' => $col['name'], 'type' => $col['native_type']];
            }

            $result = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $temp = new \stdClass;
                foreach ($columns as $column) {
                    $col_name = $column['name'];
                    $temp->$col_name = $row[$col_name];
                }
                array_push($result, $temp);
            }

            return response()->json([
                'success' => true,
                'msg' => 'OK',
                'data' => [
                    'record_count' => $record_count,
                    'columns' => $columns,
                    'result' => $result,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function saveQuery(Request $request)
    {
        $request->validate([
            'question_id' => 'required',
            'query' => 'required',
        ], [
            'question_id.required' => 'Questão sem ID válido.',
            'query.required' => 'Questão sem QUERY válida.',
        ]);

        $errorMsg = '';
        $error = false;

        try {
            /** @var Question */
            $question = Question::findOrFail($request->question_id);

            $correctQueryResult = DB::select(DB::raw($question->resposta));

            $userQuery = $request->get('query');
            $userQueryResult = DB::select(DB::raw($userQuery));

            foreach ($correctQueryResult as $index => $questionObject) {
                foreach ($questionObject as $column => $questionValue) {
                    if (!$userQueryResult[$index]->$column == $questionValue) {
                        $error = true;
                        break;
                    }
                }
                if ($error) {
                    break;
                }
            }

            $this->insertIntoUserQuestion($question, $userQuery, $errorMsg, $error);
        } catch (\Exception $e) {
            $this->insertIntoUserQuestion($question, $userQuery, $e->getMessage(), true);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Resposta enviada com sucesso!',
            'data' => [
                'question_id' => $question->id
            ]
        ], 200);
    }

    private function insertIntoUserQuestion(Question $question, string $userQuery, string $errorMsg, bool $error): void
    {
        /** @var User */
        $user = Auth::user();

        $userQuestion = UserQuestion::whereUserId($user->id)->whereQuestionId($question->id)->first();
        if (!$userQuestion) {
            $userQuestion = new UserQuestion();
        }

        $userQuestion->user_id = $user->id;
        $userQuestion->question_id = $question->id;
        $userQuestion->valor = $error ? 0 : $question->valor;
        $userQuestion->resposta = $userQuery;
        $userQuestion->error_msg = $errorMsg;
        $userQuestion->save();
    }

    private function getExpectedColumnsFromQuery(string $query): string
    {
        $result = DB::select(DB::raw($query));

        $columns = array_keys((array) $result[0]);

        return implode(', ', $columns);
    }

    public function reset(Request $request)
    {
        /** @var User */
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
               'success' => false,
               'msg' => 'Usuário não encontrado.'
            ], 400);
        }

        UserQuestion::whereUserId($user->id)->delete();

        $user->feedback = '';
        $user->save();

        return response()->json([
           'success' => true,
           'message' => 'Respostas removidas com sucesso!'
        ], 200);
    }
}
