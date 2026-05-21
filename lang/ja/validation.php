<?php

return [

    'required' => ':attribute は必須です。',
    'max' => [
        'string' => ':attribute は :max 文字以内で入力してください。',
    ],
    'integer' => ':attribute は数字で入力してください。',
    'date' => ':attribute は正しい日付を入力してください。',
    'exists' => '選択された :attribute は存在しません。',

    'attributes' => [
        'name' => '名前',
        'user_id' => '従業員',
        'role_id' => '役職',
        'sort_order' => '表示順',
        'start_date' => '開始日',
        'end_date' => '終了日',
        'evaluation_deadline' => '評価期限',
    ],

];