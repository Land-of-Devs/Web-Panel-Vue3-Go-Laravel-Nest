<?php

namespace App\Providers;

//------[ IMPORTS ]------\\
use Illuminate\Support\ServiceProvider;

//USER
use App\Domain\Interfaces\Users\UserFactory;
use App\Domain\Interfaces\Users\UserRepository;
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


//TICKET
use App\Domain\Interfaces\Tickets\TicketFactory;
use App\Domain\Interfaces\Tickets\TicketRepository;
use App\Adapters\Presenters\TicketPresenter;
use App\Domain\UseCases\Tickets\TicketInputPort;
use App\Domain\UseCases\Tickets\TicketInteractor;
use App\Factories\TicketModelFactory;
use App\Http\Controllers\Api\Tickets\TicketController;
use App\Repositories\TicketDbRepository;

// STATS 
use App\Adapters\Presenters\StatsPresenter;
use App\Domain\UseCases\Stats\StatsInteractor;
use App\Domain\UseCases\Stats\StatsInputPort;
use App\Http\Controllers\Api\Stats\StatsController;

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

        //TICKET
        $this->app->bind(
            TicketFactory::class,
            TicketModelFactory::class
        );
        $this->app->bind(
            TicketRepository::class,
            TicketDbRepository::class
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

        //TICKET
        $this->app
            ->when(TicketController::class)
            ->needs(TicketInputPort::class)
            ->give(function ($app) {
                return $app->make(TicketInteractor::class, [
                    'output' => $app->make(TicketPresenter::class)
                ]);
            });

        // STATS
        $this->app
            ->when(StatsController::class)
            ->needs(StatsInputPort::class)
            ->give(function ($app){
                return $app->make(StatsInteractor::class, [
                    'output' => $app->make(StatsPresenter::class)
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
