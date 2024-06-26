<?php

namespace App\Http\Controllers\Resources;

use App\Models\User;
use App\Enums\UserRolesEnum;
use App\Models\Administrative;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resources\Create\AdministrativeRequest as CreateAdministrativeRequest;
use App\Http\Requests\Resources\Update\AdministrativeRequest as UpdateAdministrativeRequest;

class AdministrativeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/administratives",
     *      tags={"Administratives"},
     *      summary="Show all administratives",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of administratives",
     *     @OA\Response(
     *         response=200,
     *         description="Show all administratives."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function index()
    {
        // Obtain all Administratives
        $administratives = Administrative::orderBy('id', 'asc')->get();

        if ($administratives->isEmpty()) {
            return response()->json(['message' => 'No hay ningun ' . UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value)], 404);
        }

        $administratives->each(function ($administrative) {
            $administrative->user;
        });

        return response()->json($administratives);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/administratives",
     *      tags={"Administratives"},
     *      summary="Create a new administrative",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new administrative with the received data",
     *      @OA\RequestBody(
     *         description="Administrative object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"username", "password", "name"},
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="The administrative's username"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="The new password for the user"
     *                 ),
     *                @OA\Property(
     *                    property="name",
     *                      type="string",
     *                      description="The administrative's name"
     *                 ),
     *                 example={"username": "new_administrative@example.com", "password": "newPassword", "name": "new administrative"}
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="successful operation"
     *       ),
     *       @OA\Response(
     *          response="default",
     *          description="An error occurred."
     *       )
     *     )
     *
     * Returns a message, user object, and entity object in JSON format
     */
    public function store(CreateAdministrativeRequest $request)
    {
        try {
            $user = User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role_id' => UserRolesEnum::ADMINISTRATIVE->value,
            ]);

            $administrative = Administrative::create([
                'name' => $request->name,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'message' => UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value) . ' creado',
                'user' => $user,
                'entity' => $administrative
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value), ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value), 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/administratives/{id}",
     *     tags={"Administratives"},
     *     summary="Show administrative",
     *     security={{"bearerAuth":{}}},
     *     description="Returns an administrative",
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
     *         description="Show only one administrative."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function show(string $id)
    {
        $administrative = Administrative::find($id);
        if (!$administrative) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value) . ' no encontrado'], 404);
        }
        $administrative->user;
        return response()->json($administrative);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *      path="/api/administratives/{id}",
     *      operationId="updateAdministrative",
     *      tags={"Administratives"},
     *      summary="Update existing administrative",
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
     *         description="Administrative object that needs to be updated. You can send 0 or more attributes in the body. If you send only one attribute, only that attribute will be updated.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The administrative's name"
     *                 ),
     *                 example={"name": "existent administrative"}
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
     */
    public function update(UpdateAdministrativeRequest $request, string $id)
    {
        $administrative = Administrative::find($id);

        if (!$administrative) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value) . ' no encontrado'], 404);
        }

        $data = $request->all();
        $administrative->fill($data);
        $administrative->save();

        return response()->json([
            'message' => UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value) . ' actualizado'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/administratives/{id}",
     *     tags={"Administratives"},
     *     summary="Delete an administrative by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Delete an administrative by ID",
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
     *         description="Administrative deleted successfully."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $administrative = Administrative::find($id);

        if (!$administrative) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value) . ' no encontrado'], 404);
        }

        try {
            $user = $administrative->user;

            if ($user) {
                $user->delete();
            }

            $administrative->delete();

            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::ADMINISTRATIVE->value) . ' eliminado exitosamente'], 204);
        } catch (\Exception $e) {
            Log::error('Error al realizar la eliminacion', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al realizar la eliminacion'], 500);
        }
    }
}
