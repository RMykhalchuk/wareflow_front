<?php

namespace App\Http\Requests\Terminal;

use App\Http\Requests\RequestJson;
use Illuminate\Foundation\Http\FormRequest;

class MoveLeftoversOrContainerRequest extends FormRequest
{
    use RequestJson;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cell_id' => [
                'nullable',
                'uuid',
                'exists:cells,id',
            ],

            'container_id' => [
                'nullable',
                'uuid',
                'exists:container_registers,id',
            ],

            'leftovers' => [
                'nullable',
                'array',
            ],
            'leftovers.*.id' => 'required|uuid|exists:leftovers,id',
            'leftovers.*.package_id' => 'required|uuid|exists:packages,id',
            'leftovers.*.quantity' => 'required|integer|min:1',

            'containers' => [
                'nullable',
                'array',
            ],
            'containers.*' => 'uuid|exists:container_registers,id'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $hasCellId = $this->filled('cell_id');
            $hasContainerId = $this->filled('container_id');
            $hasLeftovers = $this->filled('leftovers') && is_array($this->leftovers) && count($this->leftovers) > 0;
            $hasContainers = $this->filled('containers') && is_array($this->containers) && count($this->containers) > 0;

            // Перевірка 1: Має бути або cell_id, або container_id (але не обидва)
            if (!$hasCellId && !$hasContainerId) {
                $validator->errors()->add(
                    'cell_id',
                    'Необхідно вказати cell_id або container_id'
                );
                return;
            }

            if ($hasCellId && $hasContainerId) {
                $validator->errors()->add(
                    'cell_id',
                    'Не можна вказувати cell_id разом з container_id'
                );
                return;
            }

            // Сценарій 1: Переміщення окремого контейнера (container_id заданий)
            if ($hasContainerId) {
                if (!$hasLeftovers) {
                    $validator->errors()->add(
                        'leftovers',
                        'При переміщенні контейнера leftovers є обов\'язковим'
                    );
                }

                if ($hasContainers) {
                    $validator->errors()->add(
                        'containers',
                        'Не можна вказувати containers при переміщенні окремого контейнера'
                    );
                }
            }

            // Сценарій 2: Переміщення в комірку (cell_id заданий)
            if ($hasCellId) {
                if (!$hasLeftovers && !$hasContainers) {
                    $validator->errors()->add(
                        'cell_id',
                        'При переміщенні в комірку необхідно вказати leftovers або containers'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'cell_id.exists' => 'Вказана комірка не існує',
            'container_id.exists' => 'Вказаний контейнер не існує',
            'leftovers.*.id.exists' => 'Один з залишків не існує',
            'leftovers.*.package_id.exists' => 'Одне з пакувань не існує',
            'containers.*.exists' => 'Один з контейнерів не існує',
        ];
    }
}

