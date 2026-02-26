<?php

namespace App\Http\Requests\Web\Warehouse;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class WarehouseScheduleRequest extends FormRequest
{
    use RequestJson;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'graphic' => 'nullable|array',
            'graphic.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_array($value)) {
                        $fail("Поле $attribute має бути об'єктом {Day: [start_at, end_at, break_start_at, break_end_at]}");

                        return;
                    }

                    $dayName = array_key_first($value);
                    $dayData = $value[$dayName] ?? null;

                    if ($dayData === 'holiday') {
                        return;
                    }

                    if (!is_array($dayData) || count($dayData) !== 4) {
                        $fail("Поле $attribute для $dayName має містити рівно 4 елементи: [start_at, end_at, break_start_at, break_end_at]");
                    }
                }
            ],

            'conditions' => 'nullable|array',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $graphics = [];
        $conditions = [];

        // Отримуємо всі дані з запиту
        $allData = $this->all();

        ksort($allData);

        foreach ($allData as $key => $value) {
            if (str_starts_with($key, 'graphic_')) {
                $index = (int) substr($key, 8);
                $graphics[$index] = json_decode($value, true);
            }

            if (str_starts_with($key, 'conditions_')) {
                // Видаляємо префікс "conditions_"
                $keyWithoutPrefix = substr($key, 11); // 11 = довжина "conditions_"

                // Знаходимо перший "_" який відділяє індекс від назви поля
                $firstUnderscorePos = strpos($keyWithoutPrefix, '_');

                if ($firstUnderscorePos !== false) {
                    $index = (int) substr($keyWithoutPrefix, 0, $firstUnderscorePos);
                    $field = substr($keyWithoutPrefix, $firstUnderscorePos + 1);

                    if (!isset($conditions[$index])) {
                        $conditions[$index] = [];
                    }

                    $conditions[$index][$field] = $value;
                }
            }
        }

        ksort($graphics);
        ksort($conditions);

        if (!empty($graphics)) {
            $this->merge(['graphic' => $graphics]);
        }

        if (!empty($conditions)) {
            $this->merge(['conditions' => $conditions]);
        }
    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        return [
            'graphic'           => __('localization.warehouse.create.block_3.work_schedule_title'),
            'graphic.*'         => __('localization.warehouse.create.block_3.work_schedule_title'),

            'conditions'                => __('localization.warehouse.create.block_3.special_conditions_title'),
            'conditions.*.date_from'    => __('localization.warehouse.create.add_condition_modal.select_period_period_label'),
            'conditions.*.type_id'      => __('localization.warehouse.create.add_condition_modal.condition_name_label'),
        ];
    }
}
