<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Вы должны принять :attribute.',
    'accepted_if' => 'Вы должны принять :attribute, когда :other содержит :value.',
    'active_url' => 'Поле :attribute должно быть действительным URL-адресом.',
    'after' => 'Поле :attribute должно быть датой после :date.',
    'after_or_equal' => 'Поле :attribute должно быть датой после или равной :date.',
    'alpha' => 'Поле :attribute может содержать только буквы.',
    'alpha_dash' => 'Поле :attribute может содержать только буквы, цифры, дефис и нижнее подчеркивание.',
    'alpha_num' => 'Поле :attribute может содержать только буквы и цифры.',
    'any_of' => 'Значение поля :attribute не найдено в списке разрешённых.',
    'array' => 'Поле :attribute должно быть массивом.',
    'ascii' => 'Поле :attribute должно содержать только однобайтовые цифро-буквенные символы.',
    'before' => 'Поле :attribute должно быть датой до :date.',
    'before_or_equal' => 'Поле :attribute должно быть датой до или равной :date.',
    'between' => [
        'array' => 'Количество элементов в поле :attribute должно быть от :min до :max.',
        'file' => 'Размер файла в поле :attribute должен быть от :min до :max Кб.',
        'numeric' => 'Значение поля :attribute должно быть от :min до :max.',
        'string' => 'Количество символов в поле :attribute должно быть от :min до :max.',
    ],
    'boolean' => 'Поле :attribute должно быть логического типа.',
    'can' => 'Поле :attribute содержит неавторизованное значение.',
    'confirmed' => 'Подтверждение поля :attribute не совпадает.',
    'contains' => 'В поле :attribute отсутствует необходимое значение.',
    'current_password' => 'Неверный пароль.',
    'date' => 'Поле :attribute должно быть корректной датой.',
    'date_equals' => 'Поле :attribute должно быть датой, равной :date.',
    'date_format' => 'Поле :attribute должно соответствовать формату :format.',
    'decimal' => 'Поле :attribute должно содержать :decimal десятичных знаков.',
    'declined' => 'Поле :attribute должно быть отклонено.',
    'declined_if' => 'Поле :attribute должно быть отклонено, когда :other равно :value.',
    'different' => 'Поля :attribute и :other должны различаться.',
    'digits' => 'Длина поля :attribute должна быть :digits цифр.',
    'digits_between' => 'Длина поля :attribute должна быть от :min до :max цифр.',
    'dimensions' => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute содержит повторяющееся значение.',
    'doesnt_contain' => 'Поле :attribute не должно содержать ни одного из следующих значений: :values.',
    'doesnt_end_with' => 'Поле :attribute не должно заканчиваться ни одним из следующих значений: :values.',
    'doesnt_start_with' => 'Поле :attribute не должно начинаться ни одним из следующих значений: :values.',
    'email' => 'Поле :attribute должно быть действительным электронным адресом.',
    'encoding' => 'Поле :attribute должно быть в кодировке :encoding.',
    'ends_with' => 'Поле :attribute должно заканчиваться одним из следующих значений: :values.',
    'enum' => 'Выбранное значение для :attribute некорректно.',
    'exists' => 'Выбранное значение для :attribute некорректно.',
    'extensions' => 'Файл в поле :attribute должен иметь одно из следующих расширений: :values.',
    'file' => 'Поле :attribute должно быть файлом.',
    'filled' => 'Поле :attribute обязательно для заполнения.',
    'gt' => [
        'array' => 'Количество элементов в поле :attribute должно быть больше :value.',
        'file' => 'Размер файла в поле :attribute должен быть больше :value Кб.',
        'numeric' => 'Значение поля :attribute должно быть больше :value.',
        'string' => 'Количество символов в поле :attribute должно быть больше :value.',
    ],
    'gte' => [
        'array' => 'Количество элементов в поле :attribute должно быть :value или больше.',
        'file' => 'Размер файла в поле :attribute должен быть не менее :value Кб.',
        'numeric' => 'Значение поля :attribute должно быть :value или больше.',
        'string' => 'Количество символов в поле :attribute должно быть :value или больше.',
    ],
    'hex_color' => 'Поле :attribute должно быть корректным HEX-цветом.',
    'image' => 'Поле :attribute должно быть изображением.',
    'in' => 'Выбранное значение для :attribute некорректно.',
    'in_array' => 'Поле :attribute должно существовать в :other.',
    'in_array_keys' => 'Поле :attribute должно содержать как минимум один из следующих ключей: :values.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'ip' => 'Поле :attribute должно быть действительным IP-адресом.',
    'ipv4' => 'Поле :attribute должно быть действительным IPv4-адресом.',
    'ipv6' => 'Поле :attribute должно быть действительным IPv6-адресом.',
    'json' => 'Поле :attribute должно быть валидной JSON-строкой.',
    'list' => 'Поле :attribute должно быть списком.',
    'lowercase' => 'Поле :attribute должно быть в нижнем регистре.',
    'lt' => [
        'array' => 'Количество элементов в поле :attribute должно быть меньше :value.',
        'file' => 'Размер файла в поле :attribute должен быть меньше :value Кб.',
        'numeric' => 'Значение поля :attribute должно быть меньше :value.',
        'string' => 'Количество символов в поле :attribute должно быть меньше :value.',
    ],
    'lte' => [
        'array' => 'Количество элементов в поле :attribute должно быть :value или меньше.',
        'file' => 'Размер файла в поле :attribute должен быть не более :value Кб.',
        'numeric' => 'Значение поля :attribute должно быть не более :value.',
        'string' => 'Количество символов в поле :attribute должно быть не более :value.',
    ],
    'mac_address' => 'Поле :attribute должно быть действительным MAC-адресом.',
    'max' => [
        'array' => 'Количество элементов в поле :attribute не должно превышать :max.',
        'file' => 'Размер файла в поле :attribute не должен быть больше :max Кб.',
        'numeric' => 'Значение поля :attribute не должно быть больше :max.',
        'string' => 'Количество символов в поле :attribute не должно превышать :max.',
    ],
    'max_digits' => 'Поле :attribute должно содержать не более :max цифр.',
    'mimes' => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'mimetypes' => 'Поле :attribute должно быть файлом одного из следующих типов: :values.',
    'min' => [
        'array' => 'Количество элементов в поле :attribute должно быть не меньше :min.',
        'file' => 'Размер файла в поле :attribute должен быть не менее :min Кб.',
        'numeric' => 'Значение поля :attribute должно быть не меньше :min.',
        'string' => 'Количество символов в поле :attribute должно быть не менее :min.',
    ],
    'min_digits' => 'Поле :attribute должно содержать не менее :min цифр.',
    'missing' => 'Поле :attribute должно отсутствовать.',
    'missing_if' => 'Поле :attribute должно отсутствовать, когда :other равно :value.',
    'missing_unless' => 'Поле :attribute должно отсутствовать, если только :other не равно :value.',
    'missing_with' => 'Поле :attribute должно отсутствовать, когда указано :values.',
    'missing_with_all' => 'Поле :attribute должно отсутствовать, когда указаны все значения из :values.',
    'multiple_of' => 'Поле :attribute должно быть кратным :value.',
    'not_in' => 'Выбранное значение для :attribute некорректно.',
    'not_regex' => 'Формат поля :attribute некорректен.',
    'numeric' => 'Поле :attribute должно быть числом.',
    'password' => [
        'letters' => 'Поле :attribute должно содержать как минимум одну букву.',
        'mixed' => 'Поле :attribute должно содержать как минимум одну заглавную и одну строчную букву.',
        'numbers' => 'Поле :attribute должно содержать как минимум одну цифру.',
        'symbols' => 'Поле :attribute должно содержать как минимум один символ.',
        'uncompromised' => 'Указанное значение :attribute скомпрометировано в результате утечки данных. Пожалуйста, выберите другое значение.',
    ],
    'present' => 'Поле :attribute должно присутствовать.',
    'present_if' => 'Поле :attribute должно присутствовать, когда :other равно :value.',
    'present_unless' => 'Поле :attribute должно присутствовать, если только :other не равно :value.',
    'present_with' => 'Поле :attribute должно присутствовать, когда указано :values.',
    'present_with_all' => 'Поле :attribute должно присутствовать, когда указаны все значения из :values.',
    'prohibited' => 'Поле :attribute запрещено.',
    'prohibited_if' => 'Поле :attribute запрещено, когда :other равно :value.',
    'prohibited_if_accepted' => 'Поле :attribute запрещено, когда :other принято.',
    'prohibited_if_declined' => 'Поле :attribute запрещено, когда :other отклонено.',
    'prohibited_unless' => 'Поле :attribute запрещено, если только :other не входит в список :values.',
    'prohibits' => 'Поле :attribute запрещает присутствие :other.',
    'regex' => 'Формат поля :attribute некорректен.',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys' => 'Поле :attribute должно содержать ключи: :values.',
    'required_if' => 'Поле :attribute обязательно для заполнения, когда :other равно :value.',
    'required_if_accepted' => 'Поле :attribute обязательно для заполнения, когда :other принято.',
    'required_if_declined' => 'Поле :attribute обязательно для заполнения, когда :other отклонено.',
    'required_unless' => 'Поле :attribute обязательно для заполнения, если только :other не входит в список :values.',
    'required_with' => 'Поле :attribute обязательно для заполнения, когда указано :values.',
    'required_with_all' => 'Поле :attribute обязательно для заполнения, когда указаны все значения из :values.',
    'required_without' => 'Поле :attribute обязательно для заполнения, когда не указано :values.',
    'required_without_all' => 'Поле :attribute обязательно для заполнения, когда не указано ни одно из значений :values.',
    'same' => 'Поля :attribute и :other должны совпадать.',
    'size' => [
        'array' => 'Количество элементов в поле :attribute должно быть равным :size.',
        'file' => 'Размер файла в поле :attribute должен быть равен :size Кб.',
        'numeric' => 'Значение поля :attribute должно быть равным :size.',
        'string' => 'Количество символов в поле :attribute должно быть равным :size.',
    ],
    'starts_with' => 'Поле :attribute должно начинаться с одного из следующих значений: :values.',
    'string' => 'Поле :attribute должно быть строкой.',
    'timezone' => 'Поле :attribute должно быть действительным часовым поясом.',
    'unique' => 'Поле :attribute уже занято.',
    'uploaded' => 'Загрузка файла :attribute не удалась.',
    'uppercase' => 'Поле :attribute должно быть в верхнем регистре.',
    'url' => 'Поле :attribute должно быть действительным URL-адресом.',
    'ulid' => 'Поле :attribute должно быть действительным ULID.',
    'uuid' => 'Поле :attribute должно быть действительным UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'имя',
        'full_name' => 'ФИО',
        'first_name' => 'имя',
        'last_name' => 'фамилия',
        'middle_name' => 'отчество',
        'email' => 'email',
        'phone' => 'телефон',
        'password' => 'пароль',
        'hourly_rate' => 'часовая ставка',
        'bio' => 'биография',
        'age' => 'возраст',
        'birth_date' => 'дата рождения',
        'gender' => 'пол',
        'status' => 'статус',
        'parent_id' => 'родитель',
        'current_course_id' => 'текущий курс',
        'group_id' => 'группа',
        'course_id' => 'курс',
        'lesson_id' => 'урок',
        'teacher_id' => 'преподаватель',
        'student_id' => 'ученик',
        'subscription_id' => 'абонемент',
        'attendance_id' => 'посещаемость',
        'description' => 'описание',
        'title' => 'название',
        'text' => 'текст',
        'content' => 'содержимое',
        'date' => 'дата',
        'time' => 'время',
        'role' => 'роль',
    ],

];
