<?php

namespace App\Http\Controllers;

use App\Mappers\UserMapper;
use App\Models\User\User;
use App\Repositories\UserRepository;
use App\Support\Requests\NicknameUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NicknameController extends Controller
{
    public function __construct(private UserRepository $userRepository, private UserMapper $userMapper)
    {
    }

    /**
         * @OA\Get(
         *     path="/api/user/{nickname}",
         *     tags={"Users"},
         *     summary="Find user by nickname",
         *     description="returns user",
         *     @OA\Parameter(
         *          name="nickname",
         *          in="path",
         *          description="Find user by nickname",
         *          required=true,
         *          @OA\Schema(
         *              type="string",
         *          )
         *      ),
         *     @OA\Response(
         *         response=200,
         *         description="User Details",
         *         @OA\JsonContent(ref="#/components/schemas/UserMapper"),
         *     ),
         *     @OA\Response(response=404, description="No User Found"),

         * )
         *
         * @param User $user
         *
         * @return JsonResponse
         */
    public function show(Request $request)
    {
        try {
            $user         = User::where('nick_name', $request->nickname)->firstOrFail();
            $responseBody = $this->userMapper->single($user);
            $status       = 200;
        } catch (ModelNotFoundException $exception) {
            $responseBody = $exception->getMessage();
            $status       = 422;
        }
        return \Response::json(
            $responseBody,
            $status,
            []
        );
    }

    /**
     * @OA\Put(
     *     path="/api/update-nickname/{user}",
     *     tags={"Users"},
     *     summary="Update user nickname",
     *     description="Update user nickname",
     *     @OA\Parameter(
     *          name="user",
     *          in="path",
     *          description="ID of user",
     *          required=true,
     *          example=1,
     *          @OA\Schema(
     *              type="integer",
     *              format="int64"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/NicknameUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User after the update",
     *         @OA\JsonContent(ref="#/components/schemas/UserMapper"),
     *     ),
     *     @OA\Response(response=422, description="Failed validation of given params"),
     * )
     *
     * @param NicknameUpdateRequest $request
     * @param User              $user
     *
     * @return JsonResponse
     */
    public function update(NicknameUpdateRequest $request, User $user): JsonResponse
    {
        $data = [
            'nick_name' => trim($request->input('nick_name')),
        ];

        $user->fill($data)->save();

        return \Response::json($this->userMapper->single($user), 200);
    }
}
