<x-app-layout>

    <div class="max-w-3xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">
            現場編集
        </h1>

        <form action="{{ route('sites.update', $site) }}"
              method="POST"
              class="bg-white shadow rounded p-6 space-y-4">

            @csrf
            @method('PUT')

            @include('sites.form')

        </form>

    </div>

</x-app-layout>