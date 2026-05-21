<x-app-layout>

    <div class="max-w-5xl mx-auto py-10">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold">
                現場一覧
            </h1>

            <a href="{{ route('sites.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded">

                新規登録
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
                        <th class="p-3 text-left">現場名</th>
                        <th class="p-3 text-left">工期</th>
                        <th class="p-3 text-left">評価期限</th>
                        <th class="p-3"></th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($sites as $site)

                    <tr class="border-t">

                        <td class="p-3">

                            <a href="{{ route('sites.show', $site) }}"
                                class="text-blue-500 hover:underline">

                                {{ $site->name }}

                            </a>

                        </td>

                        <td class="p-3">
                            {{ $site->start_date }}
                            〜
                            {{ $site->end_date }}
                        </td>

                        <td class="p-3">
                            {{ $site->evaluation_deadline }}
                        </td>

                        <td class="p-3 text-right">

                            <a href="{{ route('sites.edit', $site) }}"
                                class="text-blue-500 mr-3">

                                編集
                            </a>

                            <form action="{{ route('sites.destroy', $site) }}"
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
                        <td colspan="4" class="p-5 text-center text-gray-500">
                            現場がありません
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>