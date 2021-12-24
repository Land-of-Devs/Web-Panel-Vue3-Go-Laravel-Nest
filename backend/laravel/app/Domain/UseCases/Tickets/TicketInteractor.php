<?php

namespace App\Domain\UseCases\Tickets;

use App\Domain\Interfaces\Tickets\TicketFactory;
use App\Domain\Interfaces\Tickets\TicketRepository;
use App\Domain\Interfaces\ViewModel;

class TicketInteractor implements TicketInputPort
{
    public function __construct(
        private TicketOutputPort $output,
        private TicketRepository $repository,
        private TicketFactory $factory
    ) {
    }

    public function all(): ViewModel
    {
        $success = $this->repository->all();
        if ($success) {
            return $this->output->listTickets($success);
        } else {
            return $this->output->fail('List of Products Not Found!!!', 404);
        }
    }

    public function show(string $id): ViewModel
    {
        $success = $this->repository->find($id);
        if ($success) {
            return $this->output->ticket($success);
        } else {
            return $this->output->fail('Product Not Found!!!', 404);
        }
    }

    public function delete(array $ids): ViewModel
    {
        $success = $this->repository->delete($ids);

        if ($success->result) {
            if ($success->count > 0) {
                return $this->output->success('Products were successfully deleted!!!', 300, $success);
            } else {
                return $this->output->success("Wasn't deleted any product!!", 300, $success);
            }
        } else {
            return $this->output->fail('Delete Failed!!!', 400);
        }
    }

    public function status(array $ids, string $status): ViewModel
    {
        $success = $this->repository->status($ids, $status);

        if ($success->result) {
            if ($success->count > 0) {
                return $this->output->success('Product status were successfully changed!!!', 300, $success);
            } else {
                return $this->output->success("Wasn't change any product status!!", 300, $success);
            }
        } else {
            return $this->output->fail('Prodcut status change Failed!!!', 400);
        }
    }
}
