<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * 一覧
     */
    public function index()
    {
        $sites = Site::orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        return view('sites.index', compact('sites'));
    }

    /**
     * 登録画面
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * 保存
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'evaluation_deadline' => 'nullable|date',
            'sort_order' => 'nullable|integer',
        ]);

        Site::create($validated);

        return redirect()
            ->route('sites.index')
            ->with('success', '現場を登録しました');
    }

    /**
     * 編集画面
     */
    public function edit(Site $site)
    {
        return view('sites.edit', compact('site'));
    }

    /**
     * 更新
     */
    public function update(Request $request, Site $site)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'evaluation_deadline' => 'nullable|date',
            'sort_order' => 'nullable|integer',
        ]);

        $site->update($validated);

        return redirect()
            ->route('sites.index')
            ->with('success', '現場を更新しました');
    }

    /**
     * 詳細
     */
    public function show(Site $site)
    {
        $site->load([
            'members.user',
            'members.role',
            'questions.targetRole',
        ]);

        return view('sites.show', compact('site'));
    }

    /**
     * 削除
     */
    public function destroy(Site $site)
    {
        $site->delete();

        return redirect()
            ->route('sites.index')
            ->with('success', '現場を削除しました');
    }
}
