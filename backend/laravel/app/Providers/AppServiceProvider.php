<?php

namespace App\Providers;

use App\Adapters\Presenters\TwoStepGeneratePresenter;
use App\Adapters\Presenters\TwoStepValidatePresenter;
use App\Domain\Interfaces\UserFactory;
use App\Domain\Interfaces\UserRepository;
use App\Domain\UseCases\TwoStepGenerate\TwoStepGenerateInputPort;
use App\Domain\UseCases\TwoStepGenerate\TwoStepGenerateInteractor;
use App\Domain\UseCases\TwoStepValidate\TwoStepValidateInputPort;
use App\Domain\UseCases\TwoStepValidate\TwoStepValidateInteractor;
use App\Factories\UserModelFactory;
use App\Http\Controllers\Api\Internal\TwoStepCodeGenerateController;
use App\Http\Controllers\Api\Internal\TwoStepCodeValidateController;
use App\Repositories\UserDbRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            UserFactory::class,
            UserModelFactory::class,
        );

        $this->app->bind(
            UserRepository::class,
            UserDbRepository::class,
        );

        $this->app
            ->when(TwoStepCodeValidateController::class)
            ->needs(TwoStepValidateInputPort::class)
            ->give(function ($app) {
                return $app->make(TwoStepValidateInteractor::class, [
                    'output' => $app->make(TwoStepValidatePresenter::class),
                ]);
            });
        
        $this->app
            ->when(TwoStepCodeGenerateController::class)
            ->needs(TwoStepGenerateInputPort::class)
            ->give(function ($app) {
                return $app->make(TwoStepGenerateInteractor::class, [
                    'output' => $app->make(TwoStepGeneratePresenter::class),
                ]);
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
