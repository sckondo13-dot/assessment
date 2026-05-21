<x-app-layout>

    <div class="max-w-3xl mx-auto py-10">

        <div class="mb-6">

            <h1 class="text-2xl font-bold">
                質問登録
            </h1>

            <div class="text-gray-500 mt-1">
                {{ $site->name }}
            </div>

        </div>

        <form action="{{ route('sites.questions.store', $site) }}"
            method="POST"
            class="bg-white shadow rounded p-6 space-y-5">

            @csrf

            @include('questions.form')

        </form>

    </div>

</x-app-layout>