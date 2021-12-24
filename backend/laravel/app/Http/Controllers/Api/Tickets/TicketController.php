<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Http\Controllers\Controller;
use App\Domain\UseCases\Tickets\TicketInputPort;
use App\Http\Requests\Tickets\TicketIdsRequest;

class TicketController extends Controller
{
    public function __construct(
        private TicketInputPort $interactor,
    ) {}

    public function index()
    {
        $viewModel = $this->interactor->all();

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }

    public function show(string $id){
        $viewModel = $this->interactor->show($id);

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }

    public function status(TicketIdsRequest $request)
    {
        $requestV = $request->validated();
        $viewModel = $this->interactor->status($requestV['ids'], $requestV['status']);

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }
        return null;
    }

    public function delete(TicketIdsRequest $request)
    {
        $viewModel = $this->interactor->delete($request->validated()['ids']);

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }
        return null;
    }
}
