<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Site;
use App\Models\User;
use App\Models\SiteMember;
use Illuminate\Http\Request;

class SiteMemberController extends Controller
{
    /**
     * 追加画面
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
        $users = User::orderBy('name')->get();

        $roles = Role::orderBy('sort_order')->get();

        return view('site-members.create', compact(
            'site',
            'users',
            'roles'
        ));
    }

    /**
     * 保存
     */
    public function store(Request $request, Site $site)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'sort_order' => 'nullable|integer',
        ]);

        $member = SiteMember::withTrashed()
            ->where('site_id', $site->id)
            ->where('user_id', $validated['user_id'])
            ->first();

        // 削除済みなら復元
        if ($member && $member->trashed()) {

            $member->restore();

            $member->update([
                'role_id' => $validated['role_id'],
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            return redirect()
                ->route('sites.show', $site)
                ->with('success', 'メンバーを再登録しました');
        }

        // 既に存在
        if ($member) {

            return back()
                ->withErrors([
                    'user_id' => '既に登録されています'
                ])
                ->withInput();
        }

        // 新規登録
        SiteMember::create([
            'site_id' => $site->id,
            'user_id' => $validated['user_id'],
            'role_id' => $validated['role_id'],
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('sites.show', $site)
            ->with('success', 'メンバーを追加しました');
    }

    /**
     * 削除
     */
    public function destroy(SiteMember $siteMember)
    {
        $siteId = $siteMember->site_id;

        $siteMember->delete();

        return redirect()
            ->route('sites.show', $siteId)
            ->with('success', 'メンバーを削除しました');
    }
}
