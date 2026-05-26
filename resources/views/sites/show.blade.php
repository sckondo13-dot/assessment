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
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif
        {{-- 質問一覧 --}}
        <div
            x-data="{ open: false }"
            class="bg-white shadow rounded p-6 mt-6">

            {{-- タイトル --}}
            <div class="flex justify-between items-center">

                <button
                    @click="open = !open"
                    class="flex items-center gap-3 text-xl font-bold">

                    <span>質問一覧（{{ $site->questions->count() }}）</span>

                    <span x-text="open ? '▲' : '▼'"></span>

                </button>
                <div class="btn_container">
                    <div class="btn_inner">
                        <a href="{{ route('sites.questions.create', $site) }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded">

                            質問追加
                        </a>
                        <a href="{{ route('sites.questions.copy-form', $site) }}"
                            class="bg-purple-600 text-white px-4 py-2 rounded">

                            質問コピー

                        </a>
                    </div>
                    <div class="btn_inner">
                        <a href="{{ route('sites.questions.import.form', $site) }}"
                            class="bg-green-600 text-white px-4 py-2 rounded">

                            CSV登録
                        </a>
                        <a href="{{ route('sites.questions.export', $site) }}"
                            class="bg-gray-700 text-white px-4 py-2 rounded">

                            CSVダウンロード
                        </a>
                    </div>



                </div>

            </div>

            {{-- アコーディオン --}}
            <div x-show="open"
                x-transition
                class="mt-6">

                <table class="w-full">

                    <thead class="bg-gray-100">

                        <tr>
                            <th class="p-3 text-left">
                                対象役職
                            </th>

                            <th class="p-3 text-left">
                                質問
                            </th>

                            <th class="p-3 text-left">
                                タイプ
                            </th>

                            <th class="p-3"></th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($site->questions as $question)

                        <tr class="border-t">

                            <td class="p-3">
                                {{ $question->targetRole->name }}
                            </td>

                            <td class="p-3">
                                {{ $question->question_text }}
                            </td>

                            <td class="p-3">

                                @switch($question->type)

                                @case('yes_no')
                                YES / NO
                                @break

                                @case('comment')
                                コメント
                                @break

                                @case('score')
                                点数
                                @break

                                @endswitch

                            </td>

                            <td class="p-3 text-right">

                                <a href="{{ route('sites.questions.edit', [$site, $question]) }}"
                                    class="text-blue-500 mr-3">

                                    編集

                                </a>

                                <form action="{{ route('sites.questions.destroy', [$site, $question]) }}"
                                    method="POST"
                                    class="inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('削除しますか？')"
                                        class="text-red-500">

                                        削除

                                    </button>

                                </form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="4"
                                class="p-5 text-center text-gray-500">

                                質問がありません

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

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