<?php

namespace App\Http\Controllers;

use App\Models\SiteMember;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\EvaluationAnswer;

class EvaluationController extends Controller
{
    /**
     * 評価対象一覧
     */
    public function index()
    {
        $user = Auth::user();

        $mySites = SiteMember::with([
            'site.members.user',
            'site.members.role',
            'site.questions',
        ])
            ->where('user_id', $user->id)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | 現場ごとの回答済み一覧
    |--------------------------------------------------------------------------
    */

        $answeredTargetsBySite = [];

        foreach ($mySites as $mySite) {

            $answeredTargetsBySite[$mySite->site_id] =
                Evaluation::where(
                    'evaluator_user_id',
                    $user->id
                )
                ->where(
                    'site_id',
                    $mySite->site_id
                )
                ->pluck('target_user_id')
                ->toArray();
        }

        return view('evaluations.index', compact(
            'mySites',
            'user',
            'answeredTargetsBySite'
        ));
    }

    /**
     * 確認画面
     */
    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'answers' => 'required|array',
        ]);

        $site = Site::findOrFail(
            $validated['site_id']
        );

        /*
    |--------------------------------------------------------------------------
    | 回答されている質問ID取得
    |--------------------------------------------------------------------------
    */

        $questionIds = [];

        foreach ($validated['answers'] as $targetUserId => $answers) {

            foreach ($answers as $questionId => $answer) {

                $questionIds[] = $questionId;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | 質問取得
    |--------------------------------------------------------------------------
    */

        $questions = Question::whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        /*
    |--------------------------------------------------------------------------
    | 対象ユーザー取得
    |--------------------------------------------------------------------------
    */

        $targetUsers = User::whereIn(
            'id',
            array_keys($validated['answers'])
        )->get()->keyBy('id');

        return view('evaluations.confirm', compact(
            'site',
            'questions',
            'targetUsers',
            'validated'
        ));
    }

    /**
     * 評価保存
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'answers' => 'required|array',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($validated, $user) {

            /*
        |--------------------------------------------------------------------------
        | 対象ユーザーごと保存
        |--------------------------------------------------------------------------
        */

            foreach ($validated['answers'] as $targetUserId => $answers) {

                /*
            |--------------------------------------------------------------------------
            | 既に回答済みか
            |--------------------------------------------------------------------------
            */

                $already = Evaluation::where(
                    'site_id',
                    $validated['site_id']
                )
                    ->where(
                        'evaluator_user_id',
                        $user->id
                    )
                    ->where(
                        'target_user_id',
                        $targetUserId
                    )
                    ->exists();

                /*
            |--------------------------------------------------------------------------
            | 回答済みならスキップ
            |--------------------------------------------------------------------------
            */

                if ($already) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | 全部空ならスキップ
            |--------------------------------------------------------------------------
            */

                $hasAnswer = false;

                foreach ($answers as $answer) {

                    if (
                        !empty($answer['answer']) ||
                        !empty($answer['comment']) ||
                        !empty($answer['score'])
                    ) {
                        $hasAnswer = true;
                        break;
                    }
                }

                if (!$hasAnswer) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | evaluations 作成
            |--------------------------------------------------------------------------
            */

                $evaluation = Evaluation::create([
                    'site_id' => $validated['site_id'],

                    'evaluator_user_id' => $user->id,

                    'target_user_id' => $targetUserId,

                    'submitted_at' => now(),
                ]);

                /*
            |--------------------------------------------------------------------------
            | answers 保存
            |--------------------------------------------------------------------------
            */

                foreach ($answers as $questionId => $answer) {

                    EvaluationAnswer::create([

                        'evaluation_id' => $evaluation->id,

                        'question_id' => $questionId,

                        'answer_bool' => $answer['answer'] ?? null,

                        'answer_text' => $answer['comment'] ?? null,

                        'answer_score' => $answer['score'] ?? null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('evaluations.index')
            ->with('success', '評価を送信しました');
    }
}
