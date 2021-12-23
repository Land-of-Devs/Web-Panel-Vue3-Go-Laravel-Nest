<?php

namespace App\Http\Controllers\Api\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TicketRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Response;
use App\Http\Requests\Tickets\TicketStatusRequest;

class TicketController extends Controller
{
    use ApiResponseTrait;

    public $ticketsRepository;

    public $not_found;

    public function __construct(TicketRepository $ticketsRepository)
    {
        $this->ticketsRepository = $ticketsRepository;
        $this->not_found = Response::HTTP_NOT_FOUND;
    }

    public function all()
    {
        $data = $this->ticketsRepository->all();
        return self::apiResponseSuccess($data, 'Found ' . count($data) . ' Tickets');
    }

    public function status(TicketStatusRequest $request)
    {
        $request->validate();
        // try {
        //     $data = $this->ticketsRepository->setStatus($request->tickets);
        //     if ($data) {
        //         $msg = 'Tickets Deleted Successfully !';
        //         return self::apiResponseSuccess($data, $msg);
        //     }
        //     $msg = 'Tickets Not Found';
        //     return self::apiResponseError(null, $msg, $this->not_found);
        // } catch (\Exception $e) {
        //     return self::apiServerError($e->getMessage());
        // }
    }

    public function delete(Request $request)
    {
        try {
            $data = $this->ticketsRepository->delete($request->tickets);
            if ($data) {
                $msg = 'Tickets Deleted Successfully !';
                return self::apiResponseSuccess($data, $msg);
            }
            $msg = 'Tickets Not Found';
            return self::apiResponseError(null, $msg, $this->not_found);
        } catch (\Exception $e) {

            return self::apiServerError($e->getMessage());
        }
    }
}
