<x-app-layout>

    <div class="max-w-3xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">
            質問CSV登録
        </h1>

        <form method="POST"
            action="{{ route('sites.questions.import', $site) }}"
            enctype="multipart/form-data"
            class="bg-white shadow rounded p-6">

            @csrf

            <div class="mb-5">

                <label class="block mb-2 font-bold">
                    CSVファイル
                </label>

                <input type="file"
                    name="csv"
                    accept=".csv"
                    required>

            </div>

            <button
                class="bg-blue-600 text-white px-6 py-2 rounded">

                アップロード
            </button>

        </form>

    </div>

</x-app-layout>