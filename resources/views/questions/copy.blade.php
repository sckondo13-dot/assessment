<x-app-layout>

    <div class="max-w-3xl mx-auto py-10">

        <div class="bg-white shadow rounded p-6">

            <h1 class="text-2xl font-bold mb-6">
                質問コピー
            </h1>

            <div class="mb-6">

                <div class="text-gray-500">
                    コピー元
                </div>

                <div class="text-xl font-bold">
                    {{ $site->name }}
                </div>

            </div>

            <form method="POST"
                action="{{ route('sites.questions.copy.store', $site) }}">

                @csrf

                <div class="mb-4">

                    <label class="block mb-2 font-bold">
                        コピー元現場
                    </label>

                    <select
                        name="from_site_id"
                        class="w-full border rounded p-2">

                        @foreach($sites as $copySite)

                        @if($copySite->id != $site->id)

                        <option value="{{ $copySite->id }}">
                            {{ $copySite->name }}
                        </option>

                        @endif

                        @endforeach

                    </select>

                </div>

                <button
                    class="bg-blue-600 text-white px-6 py-2 rounded">

                    コピーする

                </button>

            </form>

        </div>

    </div>

    <script>
        function confirmCopy() {

            const select = document.getElementById('target_site_id');

            const option = select.options[select.selectedIndex];

            const hasQuestions = option.dataset.hasQuestions;

            if (hasQuestions == 1) {

                return confirm(
                    'コピー先には既に質問があります。\n既存質問を削除して上書きしますか？'
                );
            }

            return true;
        }
    </script>

</x-app-layout>