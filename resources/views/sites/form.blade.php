<div>
    <label class="block mb-1">現場名</label>

    <input type="text"
           name="name"
           value="{{ old('name', $site->name ?? '') }}"
           class="w-full border rounded p-2">

    @error('name')
        <div class="text-red-500 text-sm">
            {{ $message }}
        </div>
    @enderror
</div>

<div>
    <label class="block mb-1">開始日</label>

    <input type="date"
           name="start_date"
           value="{{ old('start_date', $site->start_date ?? '') }}"
           class="w-full border rounded p-2">
</div>

<div>
    <label class="block mb-1">終了日</label>

    <input type="date"
           name="end_date"
           value="{{ old('end_date', $site->end_date ?? '') }}"
           class="w-full border rounded p-2">
</div>

<div>
    <label class="block mb-1">評価期限</label>

    <input type="date"
           name="evaluation_deadline"
           value="{{ old('evaluation_deadline', $site->evaluation_deadline ?? '') }}"
           class="w-full border rounded p-2">
</div>

<div>
    <label class="block mb-1">表示順</label>

    <input type="number"
           name="sort_order"
           value="{{ old('sort_order', $site->sort_order ?? 0) }}"
           class="w-full border rounded p-2">
</div>

<div>
    <button class="bg-blue-500 text-white px-5 py-2 rounded">
        保存
    </button>
</div>