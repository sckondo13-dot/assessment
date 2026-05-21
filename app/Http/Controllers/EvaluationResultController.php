<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Site;

class EvaluationResultController extends Controller
{
    public function index(Request $request)
    {
        if (
            !auth()->user()->isSuperAdmin()
            &&
            !auth()->user()->isAdmin()
        ) {

            abort(403);
        }

        /*
    |--------------------------------------------------------------------------
    | 検索用
    |--------------------------------------------------------------------------
    */

        $users = User::orderBy('name')->get();

        $sites = Site::orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | 一覧
    |--------------------------------------------------------------------------
    */

        $evaluations = Evaluation::with([
            'site',
            'evaluator',
            'targetUser',
            'answers.question',
        ])

            /*
        |--------------------------------------------------------------------------
        | 評価者検索
        |--------------------------------------------------------------------------
        */

            ->when(
                $request->evaluator_user_id,
                function ($query) use ($request) {

                    $query->where(
                        'evaluator_user_id',
                        $request->evaluator_user_id
                    );
                }
            )

            /*
        |--------------------------------------------------------------------------
        | 現場検索
        |--------------------------------------------------------------------------
        */

            ->when(
                $request->site_id,
                function ($query) use ($request) {

                    $query->where(
                        'site_id',
                        $request->site_id
                    );
                }
            )

            ->latest()

            ->paginate(30)

            ->withQueryString();

        return view(
            'evaluation-results.index',
            compact(
                'evaluations',
                'users',
                'sites'
            )
        );
    }

    /**
     * 詳細
     */
    public function show(Evaluation $evaluation)
    {
        if (
            !auth()->user()->isSuperAdmin()
            &&
            !auth()->user()->isAdmin()
        ) {

            abort(403);
        }

        $evaluation->load([
            'site',
            'evaluator',
            'targetUser',
            'answers.question',
        ]);

        return view(
            'evaluation-results.show',
            compact('evaluation')
        );
    }
}
