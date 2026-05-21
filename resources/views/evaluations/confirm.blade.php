<x-app-layout>

    <div class="max-w-5xl mx-auto py-10">

        <h1 class="text-3xl font-bold mb-8">

            確認画面

        </h1>

        <div class="bg-white shadow rounded p-6">

            {{-- 現場 --}}
            <div class="mb-8">

                <div class="text-gray-500">

                    現場

                </div>

                <div class="text-2xl font-bold">

                    {{ $site->name }}

                </div>

            </div>

            <form method="POST"
                action="{{ route('evaluations.store') }}">

                @csrf

                <input type="hidden"
                    name="site_id"
                    value="{{ $site->id }}">

                {{-- 対象者ループ --}}
                @foreach($validated['answers'] as $targetUserId => $answers)

                @php

                $targetUser = $targetUsers[$targetUserId];

                @endphp

                <div class="border rounded-xl p-6 mb-8 bg-gray-50">

                    {{-- 対象者 --}}
                    <div class="mb-6">

                        <div class="text-gray-500">

                            評価対象

                        </div>

                        <div class="text-2xl font-bold">

                            {{ $targetUser->name }}

                        </div>

                    </div>

                    {{-- hidden --}}
                    <input type="hidden"
                        name="targets[]"
                        value="{{ $targetUserId }}">

                    {{-- 質問ループ --}}
                    @foreach($answers as $questionId => $answer)

                    @php

                    $question = $questions[$questionId];

                    @endphp

                    <div class="bg-white border rounded p-5 mb-5">

                        {{-- 質問 --}}
                        <div class="font-bold text-lg mb-3">

                            {{ $question->question_text }}

                        </div>

                        <div class="text-lg">

                            {{-- YES / NO --}}
                            @if(isset($answer['answer']))

                            {{ (int)$answer['answer'] === 1 ? 'YES' : 'NO' }}

                            <input type="hidden"
                                name="answers[{{ $targetUserId }}][{{ $questionId }}][answer]"
                                value="{{ $answer['answer'] }}">

                            @endif

                            {{-- コメント --}}
                            @if(isset($answer['comment']))

                            <div class="whitespace-pre-wrap">

                                {{ $answer['comment'] }}

                            </div>

                            <input type="hidden"
                                name="answers[{{ $targetUserId }}][{{ $questionId }}][comment]"
                                value="{{ $answer['comment'] }}">

                            @endif

                            {{-- 点数 --}}
                            @if(isset($answer['score']))

                            <div>

                                {{ $answer['score'] }} 点

                            </div>

                            <input type="hidden"
                                name="answers[{{ $targetUserId }}][{{ $questionId }}][score]"
                                value="{{ $answer['score'] }}">

                            @endif

                        </div>

                    </div>

                    @endforeach

                </div>

                @endforeach

                {{-- ボタン --}}
                <div class="flex justify-between mt-10">

                    <button
                        type="button"
                        onclick="history.back()"
                        class="
                            bg-gray-500
                            text-white
                            font-bold
                            px-4 py-2 rounded
                        ">

                        戻る

                    </button>

                    <button
                        class="
                            bg-rose-500
                            text-white
                            font-bold
                            px-4 py-2 rounded
                            shadow-lg
                            transition
                        ">

                        送信する

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>