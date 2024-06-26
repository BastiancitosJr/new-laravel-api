<?php

namespace App\Http\Controllers\Resources;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resources\Update\UserRequest as UpdateUserRequest;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/users",
     *      tags={"Users"},
     *      summary="Show all users",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of users",
     *     @OA\Response(
     *         response=200,
     *         description="Show all users."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function index()
    {
        // Obtain all users
        $users = User::orderBy('id', 'asc')->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No hay usuarios'], 404);
        }

        $users->each(function ($user) {
            $user->entity;
        });

        return response()->json($users);
    }
    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Show user",
     *     security={{"bearerAuth":{}}},
     *     description="Returns a user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Resource ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Show only one user."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $user->entity;
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *      path="/api/users/{id}",
     *      operationId="updateUser",
     *      tags={"Users"},
     *      summary="Update existing user",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a string message",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Resource ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *      @OA\RequestBody(
     *         description="User object that needs to be updated. You can send 0 or more attributes in the body. If you send only one attribute, only that attribute will be updated.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="The user's username"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="The user's password"
     *                 ),
     *                 example={"username": "user@example.com", "password": "newPassword"}
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(
     *          response="default",
     *          description="An error occurred."
     *       )
     *     )
     *
     * Returns a string message
     */
    public function update(UpdateUserRequest $request, string $id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $data = $request->all();

        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->fill($data);
        $user->save();
        return response()->json([
            'message' => 'Usuario actualizado'
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Delete an user by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Delete an user by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Resource ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted successfully."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        try {
            $entity = $user->entity;

            if ($entity) {
                $entity->delete();
            }

            $user->delete();

            return response()->json(['message' => 'Usuario eliminado exitosamente'], 204);
        } catch (\Exception $e) {
            Log::error('Error al realizar la eliminacion', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al realizar la eliminacion', 'error' => $e->getMessage()], 500);
        }
    }
}
