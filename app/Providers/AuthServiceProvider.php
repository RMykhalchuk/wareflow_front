<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * System Administrator (super_admin) має доступ до всього.
         *
         * ВАЖЛИВО: тут ми ТІЛЬКИ ПЕРЕВІРЯЄМО роль,
         * НІЧОГО не призначаємо!
         */
        Gate::before(function ($user, $ability) {
            $workingData = $user->workingData ?? null;

            if ($workingData && $workingData->hasRole('super_admin')) {
                return true;   // супер-адмін має повний доступ
            }

            return null;       // інші права перевіряються звичайними Gate/Policy
        });

        Gate::define('viewApiDocs', function ($user = null) {
            // Дозволити всім на не-продакшні середовищах
            if (! app()->environment('production')) {
                return true;
            }

            // На продакшені - тільки авторизованим користувачам
            return $user !== null;

            // Або тільки адмінам:
            // return $user && $user->isAdmin();
        });

        /**
         * Доступ до блоку довідників.
         * User НЕ повинен мати доступу.
         * Доступні: super_admin, admin.
         */
        Gate::define('view-dictionaries', function ($user) {
            $wd = $user->workingData ?? null;

            return $wd && $wd->hasAnyRole(['super_admin', 'admin']);
        });

        /**
         * Доступ до налаштувань середовища.
         * User НЕ повинен мати доступу.
         * Доступні: super_admin, admin.
         */
        Gate::define('manage-environment', function ($user) {
            $wd = $user->workingData ?? null;

            return $wd && $wd->hasAnyRole(['super_admin', 'admin']);
        });

        /**
         * Доступ до сторінки "Залишки ERP".
         * User НЕ повинен мати доступу.
         * Доступні: super_admin, admin.
         */
        Gate::define('view-erp-leftovers', function ($user) {
            $wd = $user->workingData ?? null;

            return $wd && $wd->hasAnyRole(['super_admin', 'admin']);
        });

        /**
         * Чи може поточний користувач змінювати роль КОНКРЕТНОГО юзера.
         *
         * Обмеження:
         *  - WMS Admin НЕ може змінювати роль користувача, який уже super_admin.
         *  - Змінювати роль super_admin може тільки super_admin.
         */
        Gate::define('edit-user-role', function ($actor, $targetUser) {
            $actorWD  = $actor->workingData ?? null;
            $targetWD = $targetUser->workingData ?? null;

            if (! $actorWD) {
                return false;
            }

            $targetIsSuper = $targetWD && $targetWD->hasRole('super_admin');

            // Якщо ціль не супер-адмін — роль можна змінити
            if (! $targetIsSuper) {
                return true;
            }

            // Якщо ціль — супер-адмін, змінювати може тільки супер-адмін
            return $actorWD->hasRole('super_admin');
        });

        /**
         * Чи може користувач призначати роль super_admin (будь-кому, включаючи себе).
         *
         * Обмеження:
         *  - admin НЕ може призначити super_admin.
         *  - тільки super_admin може видати комусь super_admin.
         */
        Gate::define('assign-superadmin-role', function ($actor) {
            $actorWD = $actor->workingData ?? null;

            return $actorWD && $actorWD->hasRole('super_admin');
        });
    }
}
