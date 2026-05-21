<x-app-layout>

    <div class="max-w-6xl mx-auto py-10">

        <h1 class="text-3xl font-bold mb-8">
            評価一覧
        </h1>

        {{-- success --}}
        @if(session('success'))

        <div class="bg-green-100 text-green-700 p-4 rounded mb-6">

            {{ session('success') }}

        </div>

        @endif

        {{-- error --}}
        @if(session('error'))

        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">

            {{ session('error') }}

        </div>

        @endif

        @forelse($mySites as $mySite)

        <div class="bg-white shadow rounded p-6 mb-8">

            {{-- 現場名 --}}
            <div class="mb-5">

                <h2 class="text-2xl font-bold">
                    {{ $mySite->site->name }}
                </h2>

                <div class="text-gray-500 mt-1">

                    {{ $mySite->site->start_date }}
                    〜
                    {{ $mySite->site->end_date }}

                </div>

            </div>

            {{-- 現場単位form --}}
            <form method="POST"
                action="{{ route('evaluations.confirm') }}">

                @csrf

                <input type="hidden"
                    name="site_id"
                    value="{{ $mySite->site->id }}">

                {{-- メンバー一覧 --}}
                <table class="w-full">

                    <thead class="bg-gray-100">

                        <tr>

                            <th class="p-3 text-left">
                                名前
                            </th>

                            <th class="p-3 text-left">
                                役職
                            </th>

                            <th class="p-3 text-left">
                                状態
                            </th>

                            <th class="p-3"></th>

                        </tr>

                    </thead>

                    @foreach($mySite->site->members as $member)

                    @continue($member->user_id == $user->id)

                    @php

                    $answered = in_array(
                    $member->user_id,
                    $answeredTargetsBySite[$mySite->site_id] ?? []
                    );

                    $questions = $mySite->site->questions
                    ->where(
                    'target_role_id',
                    $member->role_id
                    );

                    @endphp

                    <tbody x-data="{ open: false }">

                        {{-- メイン行 --}}
                        <tr class="border-t">

                            <td class="p-3">

                                {{ $member->user->name }}

                            </td>

                            <td class="p-3">

                                {{ $member->role->name }}

                            </td>

                            <td class="p-3">

                                @if($answered)

                                <span class="text-green-600 font-bold">

                                    回答済み

                                </span>

                                @else

                                <span class="text-red-500">

                                    未回答

                                </span>

                                @endif

                            </td>

                            <td class="p-3 text-right">

                                @if(!$answered)

                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="bg-blue-500 text-white px-4 py-2 rounded">

                                    評価入力

                                </button>

                                @endif

                            </td>

                        </tr>

                        {{-- アコーディオン --}}
                        @if(!$answered)

                        <tr x-show="open"
                            x-transition>

                            <td colspan="4"
                                class="bg-gray-50 p-6">

                                {{-- 対象ユーザー --}}
                                <input type="hidden"
                                    name="targets[]"
                                    value="{{ $member->user_id }}">

                                <div class="space-y-6">

                                    @foreach($questions as $question)

                                    <div class="bg-white p-4 rounded border">

                                        {{-- 質問 --}}
                                        <div class="font-bold mb-3">

                                            {{ $question->question_text }}

                                        </div>

                                        {{-- YES / NO --}}
                                        @if($question->type == 'yes_no')

                                        <div class="flex gap-5">

                                            <label>

                                                <input type="radio"
                                                    name="answers[{{ $member->user_id }}][{{ $question->id }}][answer]"
                                                    value="1">

                                                YES

                                            </label>

                                            <label>

                                                <input type="radio"
                                                    name="answers[{{ $member->user_id }}][{{ $question->id }}][answer]"
                                                    value="0">

                                                NO

                                            </label>

                                        </div>

                                        @endif

                                        {{-- コメント --}}
                                        @if($question->type == 'comment')

                                        <textarea
                                            name="answers[{{ $member->user_id }}][{{ $question->id }}][comment]"
                                            rows="4"
                                            class="w-full border rounded p-2"></textarea>

                                        @endif

                                        {{-- 点数 --}}
                                        @if($question->type == 'score')

                                        <select
                                            name="answers[{{ $member->user_id }}][{{ $question->id }}][score]"
                                            class="border rounded p-2">

                                            <option value="">
                                                選択してください
                                            </option>

                                            @for($i = 1; $i <= 5; $i++)

                                                <option value="{{ $i }}">

                                                {{ $i }}

                                                </option>

                                                @endfor

                                        </select>

                                        @endif

                                    </div>

                                    @endforeach

                                </div>

                            </td>

                        </tr>

                        @endif

                    </tbody>

                    @endforeach

                </table>

                {{-- 現場単位送信 --}}
                <div class="mt-8 text-right">

                    <button
                        class="bg-blue-500 text-white px-4 py-2 rounded">

                        確認画面へ →

                    </button>

                </div>

            </form>

        </div>

        @empty

        <div class="bg-white shadow rounded p-10 text-center text-gray-500">

            所属現場がありません

        </div>

        @endforelse

    </div>

</x-app-layout>