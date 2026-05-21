<x-app-layout>

    <div class="max-w-5xl mx-auto py-10">

        <div class="flex justify-between items-center mb-6">

            <div>

                <h1 class="text-2xl font-bold">
                    質問一覧
                </h1>

                <div class="text-gray-500 mt-1">
                    {{ $site->name }}
                </div>

            </div>

            <a href="{{ route('sites.questions.create', $site) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded">

                質問追加
            </a>

        </div>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white shadow rounded">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="p-3 text-left">対象役職</th>
                        <th class="p-3 text-left">質問</th>
                        <th class="p-3 text-left">タイプ</th>
                        <th class="p-3"></th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($questions as $question)

                    <tr class="border-t">

                        <td class="p-3">
                            {{ $question->targetRole->name }}
                        </td>

                        <td class="p-3">
                            {{ $question->question_text }}
                        </td>

                        <td class="p-3">
                            {{ $question->type }}
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

                                <button onclick="return confirm('削除しますか？')"
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

</x-app-layout>