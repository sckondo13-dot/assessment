<x-app-layout>

    <div class="max-w-7xl mx-auto py-10">

        <h1 class="text-3xl font-bold mb-8">

            評価結果一覧

        </h1>
        <form method="GET"
            class="bg-white shadow rounded p-5 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- 評価者 --}}
                <div>

                    <label class="block text-sm font-bold mb-2">

                        評価者

                    </label>

                    <select
                        name="evaluator_user_id"
                        class="w-full border rounded p-2">

                        <option value="">
                            全員
                        </option>

                        @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            @selected(request('evaluator_user_id')==$user->id)>

                            {{ $user->name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                {{-- 現場 --}}
                <div>

                    <label class="block text-sm font-bold mb-2">

                        現場

                    </label>

                    <select
                        name="site_id"
                        class="w-full border rounded p-2">

                        <option value="">
                            全現場
                        </option>

                        @foreach($sites as $site)

                        <option
                            value="{{ $site->id }}"
                            @selected(request('site_id')==$site->id)>

                            {{ $site->name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                {{-- ボタン --}}
                <div class="flex items-end">

                    <button
                        class="
                    bg-blue-500
                    hover:bg-blue-600
                    text-white
                    font-bold
                    px-6
                    py-2
                    rounded
                ">

                        検索

                    </button>

                </div>

            </div>

        </form>
        <div class="bg-white shadow rounded overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 text-left">
                            現場
                        </th>

                        <th class="p-3 text-left">
                            評価者
                        </th>

                        <th class="p-3 text-left">
                            被評価者
                        </th>

                        <th class="p-3 text-left">
                            回答日時
                        </th>

                        <th class="p-3"></th>

                    </tr>

                </thead>

                @forelse($evaluations as $evaluation)

                <tbody x-data="{ open: false }">

                    {{-- メイン行 --}}
                    <tr class="border-t">

                        <td class="p-3">

                            {{ $evaluation->site->name }}

                        </td>

                        <td class="p-3">

                            {{ $evaluation->evaluator->name }}

                        </td>

                        <td class="p-3">

                            {{ $evaluation->targetUser->name }}

                        </td>

                        <td class="p-3">

                            {{ $evaluation->submitted_at }}

                        </td>

                        <td class="p-3 text-right">

                            <button
                                type="button"
                                @click="open = !open"
                                class="
                                        bg-blue-500
                                        hover:bg-blue-600
                                        text-white
                                        px-4
                                        py-2
                                        rounded
                                    ">

                                詳細

                            </button>

                        </td>

                    </tr>

                    {{-- アコーディオン --}}
                    <tr x-show="open"
                        x-transition>

                        <td colspan="5"
                            class="bg-gray-50 p-6">

                            <div class="space-y-5">

                                @foreach($evaluation->answers as $answer)

                                <div class="bg-white border rounded p-5">

                                    {{-- 質問 --}}
                                    <div class="font-bold text-lg mb-3">

                                        {{ $answer->question->question_text }}

                                    </div>

                                    {{-- 回答 --}}
                                    <div class="text-lg">

                                        {{-- YES / NO --}}
                                        @if(!is_null($answer->answer_bool))

                                        <div>

                                            回答：

                                            {{ (int)$answer->answer_bool === 1 ? 'YES' : 'NO' }}

                                        </div>

                                        @endif

                                        {{-- コメント --}}
                                        @if($answer->answer_text)

                                        <div class="whitespace-pre-wrap">

                                            {{ $answer->answer_text }}

                                        </div>

                                        @endif

                                        {{-- 点数 --}}
                                        @if(!is_null($answer->answer_score))

                                        <div>

                                            {{ $answer->answer_score }} 点

                                        </div>

                                        @endif

                                    </div>

                                </div>

                                @endforeach

                            </div>

                        </td>

                    </tr>

                </tbody>

                @empty

                <tbody>

                    <tr>

                        <td colspan="5"
                            class="p-6 text-center text-gray-500">

                            データがありません

                        </td>

                    </tr>

                </tbody>

                @endforelse

            </table>

        </div>

        <div class="mt-6">

            {{ $evaluations->links() }}

        </div>

    </div>

</x-app-layout>