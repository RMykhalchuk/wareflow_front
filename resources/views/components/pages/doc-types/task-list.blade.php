@props(['tasks' => []])

@php
    // мапа іконок
    $icons = [
        'move_replenishment' => 'task-move-replenishment.svg',
        'pick' => 'task-pick.svg',
        'move_to_check' => 'task-move-check.svg',
        'move_sorting' => 'task-move-sorting.svg',
        'move_to_shipping' => 'task-move-shipping.svg',
        'move_internal' => 'task-move-internal.svg',
        'move_putaway' => 'task-move-putaway.svg',

        'ship' => 'task-ship.svg',
        'unload' => 'task-unload.svg',

        'accept' => 'task-accept.svg',
        'check' => 'task-check.svg',
    ];
@endphp

<div class="py-1">
    <div class="list-group gap-1 overflow-y-auto px-1" id="task-list" style="height: 450px">
        @forelse ($tasks as $task)
            @php
                $isFixed = isset($task['order'], $task['original']) && $task['order'] != $task['original'];
                $icon = $icons[$task['type']] ?? 'task-default.svg';
            @endphp

            <div
                class="d-flex align-items-center gap-50 js-group-data"
                data-title="{{ $task['title'] ?? '' }}"
                data-key="{{ $task['key'] ?? '' }}"
                data-type="{{ $task['type'] ?? '' }}"
                data-fixed="{{ $task['fixedPosition'] ?? '' }}"
                data-original="{{ $task['original'] ?? '' }}"
                data-order="{{ $task['order'] ?? '' }}"
                data-is-fixed="{{ $isFixed ? '1' : '0' }}"
            >
                <div class="fw-bold task-index">
                    {{ $task['order'] ?? $loop->iteration }}
                </div>

                <div
                    class="d-flex flex-grow-1 task-move-trigger justify-content-between align-items-center list-group-item border rounded border-secondary-subtle"
                    style="cursor: pointer"
                >
                    <div class="d-flex align-items-center">
                        <div class="rounded border-secondary-subtle bg-light-secondary me-50 p-50">
                            <img
                                src="{{ asset('assets/icons/entity/document-type/task/' . $icon) }}"
                                id="{{ $task['order'] ?? $loop->iteration }}-img"
                                alt="{{ $task['title'] }}"
                                height="34"
                                width="34"
                            />
                        </div>

                        <div>
                            <div class="fw-semibold d-flex">
                                <div>{{ $task['title'] }}</div>

                                @if (! empty($task['enabled']) && $task['enabled'])
                                    <div class="ms-1 task-icon border-dark rounded px-25">
                                        @if ($isFixed)
                                            <i data-feather="arrow-down"></i>
                                        @else
                                            <i data-feather="arrow-up"></i>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <small class="text-muted">
                                {{ __('localization.document_types.tasks.descriptions.' . $task['type']) }}
                            </small>
                        </div>
                    </div>

                    @if (array_key_exists('enabled', $task))
                        <div class="form-check form-switch ms-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                {{ $task['enabled'] ? 'checked' : '' }}
                            />
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center font-medium-5 fw-bold py-3">
                {{ __('localization.document_types.tasks.no_tasks_message') }}
            </div>
        @endforelse
    </div>
</div>
