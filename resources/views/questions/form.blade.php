{{-- 対象役職 --}}
<div>

    <label class="block mb-1">
        対象役職
    </label>

    <select name="target_role_id"
        class="w-full border rounded p-2">

        <option value="">
            選択してください
        </option>

        @foreach($roles as $role)

        <option value="{{ $role->id }}"
            @selected(old( 'target_role_id' ,
            $question->target_role_id ?? ''
            ) == $role->id)>

            {{ $role->name }}

        </option>

        @endforeach

    </select>

    @error('target_role_id')
    <div class="text-red-500 text-sm mt-1">
        {{ $message }}
    </div>
    @enderror

</div>

{{-- 質問文 --}}
<div>

    <label class="block mb-1">
        質問
    </label>

    <textarea name="question_text"
        rows="4"
        class="w-full border rounded p-2">{{ old(
                  'question_text',
                  $question->question_text ?? ''
              ) }}</textarea>

    @error('question_text')
    <div class="text-red-500 text-sm mt-1">
        {{ $message }}
    </div>
    @enderror

</div>

{{-- タイプ --}}
<div>

    <label class="block mb-1">
        回答タイプ
    </label>

    <select name="type"
        class="w-full border rounded p-2">

        <option value="yes_no"
            @selected(old( 'type' ,
            $question->type ?? ''
            ) == 'yes_no')>

            YES / NO

        </option>

        <option value="comment"
            @selected(old( 'type' ,
            $question->type ?? ''
            ) == 'comment')>

            コメント

        </option>

        <option value="score"
            @selected(old( 'type' ,
            $question->type ?? ''
            ) == 'score')>

            点数

        </option>

    </select>

    @error('type')
    <div class="text-red-500 text-sm mt-1">
        {{ $message }}
    </div>
    @enderror

</div>

{{-- 表示順 --}}
<div>

    <label class="block mb-1">
        表示順
    </label>

    <input type="number"
        name="sort_order"
        value="{{ old(
               'sort_order',
               $question->sort_order ?? 0
           ) }}"
        class="w-full border rounded p-2">

</div>

<div class="flex gap-3">

    <button class="bg-blue-500 text-white px-5 py-2 rounded">

        保存

    </button>

    <a href="{{ route('sites.questions.index', $site) }}"
        class="bg-gray-500 text-white px-5 py-2 rounded">

        戻る

    </a>

</div>