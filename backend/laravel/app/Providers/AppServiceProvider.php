<?php

namespace App\Providers;

//------[ IMPORTS ]------\\
use Illuminate\Support\ServiceProvider;

//USER
use App\Domain\Interfaces\UserFactory;
use App\Domain\Interfaces\UserRepository;
use App\Repositories\UserDbRepository;
use App\Factories\UserModelFactory;

//TWC
use App\Adapters\Presenters\TwoStepGeneratePresenter;
use App\Adapters\Presenters\TwoStepValidatePresenter;
use App\Domain\UseCases\TwoStepGenerate\TwoStepGenerateInputPort;
use App\Domain\UseCases\TwoStepGenerate\TwoStepGenerateInteractor;
use App\Domain\UseCases\TwoStepValidate\TwoStepValidateInputPort;
use App\Domain\UseCases\TwoStepValidate\TwoStepValidateInteractor;
use App\Http\Controllers\Api\Internal\TwoStepCodeGenerateController;
use App\Http\Controllers\Api\Internal\TwoStepCodeValidateController;

//PRODUCT
use App\Domain\Interfaces\Products\ProductFactory;
use App\Domain\Interfaces\Products\ProductRepository;
use App\Adapters\Presenters\ProductPresenter;
use App\Domain\UseCases\Products\ProductInputPort;
use App\Domain\UseCases\Products\ProductInteractor;
use App\Factories\ProductModelFactory;
use App\Http\Controllers\Api\Products\ProductController;
use App\Repositories\ProductDbRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //------[ INTERFACES IMPLEMENTATIONS]------\\

        //USER
        $this->app->bind(
            UserFactory::class,
            UserModelFactory::class
        );
        $this->app->bind(
            UserRepository::class,
            UserDbRepository::class
        );

        //PRODUCT
        $this->app->bind(
            ProductFactory::class,
            ProductModelFactory::class
        );
        $this->app->bind(
            ProductRepository::class,
            ProductDbRepository::class
        );

        //------[ CONTROLLER INSTANCING ]------\\

        //TSC VALIDATE
        $this->app
            ->when(TwoStepCodeValidateController::class)
            ->needs(TwoStepValidateInputPort::class)
            ->give(function ($app) {
                return $app->make(TwoStepValidateInteractor::class, [
                    'output' => $app->make(TwoStepValidatePresenter::class)
                ]);
            });

        //TSC GENERATE
        $this->app
            ->when(TwoStepCodeGenerateController::class)
            ->needs(TwoStepGenerateInputPort::class)
            ->give(function ($app) {
                return $app->make(TwoStepGenerateInteractor::class, [
                    'output' => $app->make(TwoStepGeneratePresenter::class)
                ]);
            });

        //PRODUCT
        $this->app
            ->when(ProductController::class)
            ->needs(ProductInputPort::class)
            ->give(function ($app){
                return $app->make(ProductInteractor::class, [
                    'output' => $app->make(ProductPresenter::class)
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
