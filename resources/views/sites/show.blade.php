<x-app-layout>

    <div class="max-w-5xl mx-auto py-10">

        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-3xl font-bold">
                    {{ $site->name }}
                </h1>

                <div class="text-gray-500 mt-2">
                    {{ $site->start_date }}
                    〜
                    {{ $site->end_date }}
                </div>

            </div>

            <a href="{{ route('sites.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded">

                戻る
            </a>

        </div>

        {{-- メンバー一覧 --}}
        <div class="bg-white shadow rounded p-6">

            <div class="flex justify-between items-center mb-5">

                <h2 class="text-xl font-bold">
                    現場メンバー
                </h2>

                <a href="{{ route('site-members.create', $site) }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded">

                    メンバー追加
                </a>

            </div>

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="p-3 text-left">名前</th>
                        <th class="p-3 text-left">役職</th>
                        <th class="p-3"></th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($site->members as $member)

                        <tr class="border-t">

                            <td class="p-3">
                                {{ $member->user->name }}
                            </td>

                            <td class="p-3">
                                {{ $member->role->name }}
                            </td>

                            <td class="p-3 text-right">

                                <form action="{{ route('site-members.destroy', $member) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('削除しますか？')"
                                            class="text-red-500">

                                        削除
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="3"
                                class="p-5 text-center text-gray-500">

                                メンバーがいません

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>