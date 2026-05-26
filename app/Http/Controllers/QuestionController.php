<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Site;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * CSV画面
     */
    public function importForm(Site $site)
    {
        return view('questions.import', compact('site'));
    }

    /**
     * CSV取込
     */
    public function import(Request $request, Site $site)
    {
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('csv')->getRealPath();

        $rows = array_map(
            'str_getcsv',
            file($path)
        );

        // ヘッダー削除
        unset($rows[0]);

        foreach ($rows as $row) {

            // Shift-JIS → UTF-8
            $row = array_map(function ($value) {

                return mb_convert_encoding(
                    $value,
                    'UTF-8',
                    'SJIS-win'
                );
            }, $row);

            // 重複チェック
            $exists = Question::where('site_id', $site->id)
                ->where('question_text', $row[1])
                ->where('target_role_id', $row[0])
                ->exists();

            // 重複していなければ登録
            if (!$exists) {

                Question::create([
                    'site_id' => $site->id,
                    'target_role_id' => $row[0],
                    'question_text' => $row[1],
                    'type' => $row[2],
                    'sort_order' => $row[3] ?? 0,
                ]);
            }
        }

        return redirect()
            ->route('sites.show', $site)
            ->with('success', 'CSV登録しました');
    }

    /**
     * CSV出力
     */
    public function export(Site $site)
    {
        $questions = $site->questions()
            ->orderBy('sort_order')
            ->get();

        $filename = 'questions_' . $site->id . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
            "attachment; filename={$filename}",
        ];

        $callback = function () use ($questions) {

            $handle = fopen('php://output', 'w');

            // ヘッダー
            fputcsv($handle, [
                mb_convert_encoding('職長：1、作業員：2', 'SJIS-win', 'UTF-8'),
                mb_convert_encoding('質問内容', 'SJIS-win', 'UTF-8'),
                mb_convert_encoding('質問タイプ', 'SJIS-win', 'UTF-8'),
                mb_convert_encoding('表示順', 'SJIS-win', 'UTF-8'),
            ]);

            foreach ($questions as $question) {

                fputcsv($handle, [
                    mb_convert_encoding($question->target_role_id, 'SJIS-win', 'UTF-8'),
                    mb_convert_encoding($question->question_text, 'SJIS-win', 'UTF-8'),
                    mb_convert_encoding($question->type, 'SJIS-win', 'UTF-8'),
                    mb_convert_encoding($question->sort_order, 'SJIS-win', 'UTF-8'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream(
            $callback,
            200,
            $headers
        );
    }

    /**
     * コピー画面
     */
    public function copyForm(Site $site)
    {
        $sites = Site::with('questions')
            ->orderBy('sort_order')
            ->get();

        return view('questions.copy', compact(
            'site',
            'sites'
        ));
    }

    /**
     * 質問コピー保存
     */
    public function copyStore(Request $request, Site $site)
    {
        $validated = $request->validate([
            'from_site_id' => 'required|exists:sites,id',
        ]);

        // コピー元
        $fromSiteId = $validated['from_site_id'];

        // コピー元質問取得
        $questions = Question::where(
            'site_id',
            $fromSiteId
        )->get();

        foreach ($questions as $question) {

            $exists = Question::where('site_id', $site->id)
                ->where('question_text', $question->question_text)
                ->where('target_role_id', $question->target_role_id)
                ->exists();

            if (!$exists) {

                Question::create([
                    'site_id' => $site->id,
                    'target_role_id' => $question->target_role_id,
                    'question_text' => $question->question_text,
                    'type' => $question->type,
                    'is_required' => $question->is_required,
                    'sort_order' => $question->sort_order,
                ]);
            }
        }

        return redirect()
            ->route('sites.show', $site)
            ->with('success', '質問をコピーしました');
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
