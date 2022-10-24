<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use PaymentApp\Base\ResponseBuilder;
use PaymentApp\Base\Traits\HandelServiceResponse;
use PaymentApp\UserModule\Requests\GetUsersRequest;
use PaymentApp\UserModule\Services\UserService;

class UsersApiController extends Controller
{
    use HandelServiceResponse;
    protected UserService $userService;
    protected ResponseBuilder $responseBuilder;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->responseBuilder = new ResponseBuilder();
    }

    public function getUsers(GetUsersRequest $request): JsonResponse
    {
        $requestData = $request->all();
        $data = $this->userService->getUsersWithTransactions(
            statuses: $requestData['status']??[],
            currencies: $requestData['currency']??[],
            minAmount: $requestData['minAmount']??null,
            maxAmount: $requestData['maxAmount']??null,
            dateFrom: $requestData['dateFrom']??'',
            dateTo: $requestData['dateTo']??'',
            pageSize: $requestData['pageSize']??10,
            pageNo: $requestData['pageNo']??1,
        );

        return $this->getServiceResponse($this->userService,$data);
    }

}
