<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Site;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * 一覧
     */
    public function index(Site $site)
    {
        if (
            !auth()->user()->isAdmin()
            &&
            !auth()->user()->isSuperAdmin()
        ) {

            abort(403);
        }

        $questions = Question::with('targetRole')
            ->where('site_id', $site->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('questions.index', compact(
            'site',
            'questions'
        ));
    }

    /**
     * 登録画面
     */
    public function create(Site $site)
    {
        if (
            !auth()->user()->isAdmin()
            &&
            !auth()->user()->isSuperAdmin()
        ) {

            abort(403);
        }
        $roles = Role::orderBy('sort_order')->get();

        return view('questions.create', compact(
            'site',
            'roles'
        ));
    }

    /**
     * 保存
     */
    public function store(Request $request, Site $site)
    {
        $validated = $request->validate([
            'target_role_id' => 'required|exists:roles,id',
            'question_text' => 'required',
            'type' => 'required',
            'sort_order' => 'nullable|integer',
        ]);

        Question::create([
            'site_id' => $site->id,
            ...$validated,
        ]);

        return redirect()
            ->route('sites.show', $site)
            ->with('success', '質問を登録しました');
    }

    /**
     * 編集画面
     */
    public function edit(Site $site, Question $question)
    {
        if (
            !auth()->user()->isAdmin()
            &&
            !auth()->user()->isSuperAdmin()
        ) {

            abort(403);
        }
        $roles = Role::orderBy('sort_order')->get();

        return view('questions.edit', compact(
            'site',
            'question',
            'roles'
        ));
    }

    /**
     * 更新
     */
    public function update(
        Request $request,
        Site $site,
        Question $question
    ) {
        $validated = $request->validate([
            'target_role_id' => 'required|exists:roles,id',
            'question_text' => 'required',
            'type' => 'required',
            'sort_order' => 'nullable|integer',
        ]);

        $question->update($validated);

        return redirect()
            ->route('sites.show', $site)
            ->with('success', '質問を更新しました');
    }

    /**
     * 削除
     */
    public function destroy(Site $site, Question $question)
    {
        $question->delete();

        return redirect()
            ->route('sites.show', $site)
            ->with('success', '質問を削除しました');
    }
}
