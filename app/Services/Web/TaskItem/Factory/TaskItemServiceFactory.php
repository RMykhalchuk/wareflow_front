<?php

namespace App\Services\Web\TaskItem\Factory;

class TaskItemServiceFactory {
    private static array $serviceInstances = [];

    public static function createService(TaskType $taskType): TaskItemServiceInterface
    {
        // Використовуємо Singleton pattern для кожного типу сервісу
        $typeValue = $taskType->value;

        if (!isset(self::$serviceInstances[$typeValue])) {
            self::$serviceInstances[$typeValue] = match ($taskType) {
                TaskType::EMAIL => new EmailTaskItemService(),
                TaskType::SMS => new SmsTaskItemService(),
                TaskType::NOTIFICATION => new NotificationTaskItemService(),
                TaskType::REPORT => new ReportTaskItemService(),
                TaskType::DATA_SYNC => new DataSyncTaskItemService(),
            };
        }

        return self::$serviceInstances[$typeValue];
    }

    // Метод для отримання всіх доступних типів сервісів
    public static function getSupportedTaskTypes(): array
    {
        return TaskType::cases();
    }

    // Метод для очищення кешу сервісів (корисно для тестування)
    public static function clearServiceCache(): void
    {
        self::$serviceInstances = [];
    }
}
