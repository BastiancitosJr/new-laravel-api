<?php

namespace App\Http\Controllers\Resources;

use App\Models\Area;
use App\Models\User;
use App\Enums\UserRolesEnum;
use App\Models\ShiftManager;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resources\Create\ShiftManagerRequest as CreateShiftManagerRequest;
use App\Http\Requests\Resources\Update\ShiftManagerRequest as UpdateShiftManagerRequest;

class ShiftManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/shiftmanagers",
     *      tags={"ShiftManagers"},
     *      summary="Show all shift managers",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of shift managers",
     *     @OA\Response(
     *         response=200,
     *         description="Show all shift managers."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function index()
    {
        // Obtain all ShiftManagers
        $shiftmanagers = ShiftManager::orderBy('id', 'asc')->get();

        if ($shiftmanagers->isEmpty()) {
            return response()->json(['message' => 'No hay ningun ' . UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value)], 404);
        }

        $shiftmanagers->each(function ($shiftmanager) {
            $shiftmanager->user;
            $shiftmanager->area;
        });

        return response()->json($shiftmanagers);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/shiftmanagers",
     *      tags={"ShiftManagers"},
     *      summary="Create a new shift manager",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new shift manager with the received data",
     *      @OA\RequestBody(
     *         description="Shift manager object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"username", "password", "name", "name_area"},
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="The username's shift manager"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="The password's shift manager"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name of the shift manager"
     *                 ),
     *                 @OA\Property(
     *                     property="name_area",
     *                     type="string",
     *                     description="The area to which the shift manager belongs"
     *                 ),
     *                 example={"username": "new_shiftmanager@example.com", "password": "newPassword", "name": "new shiftmanager", "name_area": "Ventas"}
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
    public function store(CreateShiftManagerRequest $request)
    {
        try {
            $area = Area::where('name', $request->name_area)->first();
            if ($area) {
                $user = User::create([
                    'username' => $request->username,
                    'password' => bcrypt($request->password),
                    'role_id' => UserRolesEnum::SHIFTMANAGER->value,
                ]);

                $shiftmanager = ShiftManager::create([
                    'name' => $request->name,
                    'user_id' => $user->id,
                    'area_id' => $area->id,
                ]);

                return response()->json([
                    'message' => UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value) . ' creado',
                    'user' => $user,
                    'entity' => $shiftmanager
                ], 201);
            } else {
                return response()->json(['message' => 'El area no existe'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value), ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value), 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/shiftmanagers/{id}",
     *     tags={"ShiftManagers"},
     *     summary="Show shift manager",
     *     security={{"bearerAuth":{}}},
     *     description="Returns a shift manager",
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
     *         description="Show only one shift manager."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function show(string $id)
    {
        $shiftmanager = ShiftManager::find($id);
        if (!$shiftmanager) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value) . ' no encontrado'], 404);
        }
        $shiftmanager->user;
        $shiftmanager->area;

        return response()->json($shiftmanager);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *      path="/api/shiftmanagers/{id}",
     *      operationId="updateShiftManager",
     *      tags={"ShiftManagers"},
     *      summary="Update existing shift manager",
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
     *         description="Shift Manager object that needs to be updated. You can send 0 or more attributes in the body. If you send only one attribute, only that attribute will be updated.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name's shift manager"
     *                 ),
     *                 @OA\Property(
     *                     property="name_area",
     *                     type="string",
     *                     description="The area to which the shift manager belongs"
     *                 ),
     *                 example={"name": "existent shift manager", "name_area": "Mantenimiento"}
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
    public function update(UpdateShiftManagerRequest $request, string $id)
    {
        $shiftmanager = ShiftManager::find($id);

        if (!$shiftmanager) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value) . ' no encontrado'], 404);
        }
        if ($request->name_area) {
            $area = Area::where('name', $request->name_area)->first();
            if (!$area) {
                return response()->json(['message' => 'El area no existe'], 404);
            }
            $request['area_id'] = $area->id;
        }
        $data = $request->all();
        $shiftmanager->fill($data);
        $shiftmanager->save();

        return response()->json([
            'message' => UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value) . ' actualizado'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/shiftmanagers/{id}",
     *     tags={"ShiftManagers"},
     *     summary="Delete an shift manager by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Delete an shift manager by ID",
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
     *         description="Shift manager deleted successfully."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $shiftmanager = ShiftManager::find($id);

        if (!$shiftmanager) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value) . ' no encontrado'], 404);
        }

        try {
            $user = $shiftmanager->user;

            if ($user) {
                $user->delete();
            }

            $shiftmanager->delete();

            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::SHIFTMANAGER->value) . ' eliminado exitosamente'], 204);
        } catch (\Exception $e) {
            Log::error('Error al realizar la eliminacion', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al realizar la eliminacion'], 500);
        }
    }
}
