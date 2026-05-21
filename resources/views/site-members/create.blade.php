<x-app-layout>

    <div class="max-w-3xl mx-auto py-10">

        <div class="mb-6">

            <h1 class="text-2xl font-bold">
                メンバー追加
            </h1>

            <div class="text-gray-500 mt-2">
                {{ $site->name }}
            </div>

        </div>

        <form action="{{ route('site-members.store', $site) }}"
              method="POST"
              class="bg-white shadow rounded p-6 space-y-5">

            @csrf

            {{-- 従業員 --}}
            <div>

                <label class="block mb-1">
                    従業員
                </label>

                <select name="user_id"
                        class="w-full border rounded p-2">

                    <option value="">
                        選択してください
                    </option>

                    @foreach($users as $user)

                        <option value="{{ $user->id }}"
                            @selected(old('user_id') == $user->id)>

                            {{ $user->name }}

                        </option>

                    @endforeach

                </select>

                @error('user_id')
                    <div class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- 役職 --}}
            <div>

                <label class="block mb-1">
                    役職
                </label>

                <select name="role_id"
                        class="w-full border rounded p-2">

                    <option value="">
                        選択してください
                    </option>

                    @foreach($roles as $role)

                        <option value="{{ $role->id }}"
                            @selected(old('role_id') == $role->id)>

                            {{ $role->name }}

                        </option>

                    @endforeach

                </select>

                @error('role_id')
                    <div class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- 並び順 --}}
            <div>

                <label class="block mb-1">
                    表示順
                </label>

                <input type="number"
                       name="sort_order"
                       value="{{ old('sort_order', 0) }}"
                       class="w-full border rounded p-2">

            </div>

            <div class="flex gap-3">

                <button class="bg-blue-500 text-white px-5 py-2 rounded">

                    保存

                </button>

                <a href="{{ route('sites.show', $site) }}"
                   class="bg-gray-500 text-white px-5 py-2 rounded">

                    戻る

                </a>

            </div>

        </form>

    </div>

</x-app-layout>