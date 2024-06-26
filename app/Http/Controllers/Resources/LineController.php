<?php

namespace App\Http\Controllers\Resources;

use App\Models\Line;
use App\Models\User;
use App\Enums\UserRolesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resources\Create\LineRequest as CreateLineRequest;
use App\Http\Requests\Resources\Update\LineRequest as UpdateLineRequest;
use App\Models\Area;
use Illuminate\Support\Facades\Log;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/lines",
     *      tags={"Lines"},
     *      summary="Show all lines",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of lines",
     *     @OA\Response(
     *         response=200,
     *         description="Show all lines."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function index()
    {
        // Obtain all lines
        $lines = Line::orderBy('id', 'asc')->get();

        if ($lines->isEmpty()) {
            return response()->json(['message' => 'No hay ninguna ' . UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value)], 404);
        }

        $lines->each(function ($line) {
            $line->user;
            $line->area;
        });

        return response()->json($lines);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/lines",
     *      tags={"Lines"},
     *      summary="Create a new line",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new line with the received data",
     *      @OA\RequestBody(
     *         description="Line object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"username", "password", "name", "name_area"},
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="The line's username"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="The new password for the user"
     *                 ),
     *                @OA\Property(
     *                    property="name",
     *                      type="string",
     *                      description="The line's name"
     *                 ),
     *                 @OA\Property(
     *                    property="name_area",
     *                    type="string",
     *                    description="The name of the area to which the line belongs"
     *                 ),
     *                 example={"username": "new_line@example.com", "password": "newPassword", "name": "new line", "name_area": "Ventas"}
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
    public function store(CreateLineRequest $request)
    {
        try {

            $area = Area::where('name', $request->name_area)->first();
            if ($area) {

                $user = User::create([
                    'username' => $request->username,
                    'password' => bcrypt($request->password),
                    'role_id' => UserRolesEnum::LINE->value,
                ]);

                $line = Line::create([
                    'name' => $request->name,
                    'user_id' => $user->id,
                    'area_id' => $area->id,
                ]);

                return response()->json([
                    'message' => UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value) . ' creada',
                    'user' => $user,
                    'entity' => $line
                ], 201);
            } else {
                return response()->json(['message' => 'El area no existe'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value), ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value), 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/lines/{id}",
     *     tags={"Lines"},
     *     summary="Show line",
     *     security={{"bearerAuth":{}}},
     *     description="Returns a line",
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
     *         description="Show only one line."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function show(string $id)
    {
        $line = Line::find($id);
        if (!$line) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value) . ' no encontrada'], 404);
        }
        $line->user;
        $line->area;

        return response()->json($line);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *      path="/api/lines/{id}",
     *      operationId="updateLine",
     *      tags={"Lines"},
     *      summary="Update existing line",
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
     *         description="Line object that needs to be updated. You can send 0 or more attributes in the body. If you send only one attribute, only that attribute will be updated.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The line's name"
     *                 ),
     *                 @OA\Property(
     *                     property="name_area",
     *                     type="string",
     *                     description="The name of the area to which the line belongs"
     *                 ),
     *                 example={"name": "existent line", "name_area": "Mantenimiento"}
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
    public function update(UpdateLineRequest $request, string $id)
    {
        $line = Line::find($id);

        if (!$line) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value) . ' no encontrada'], 404);
        }
        if ($request->name_area) {
            $area = Area::where('name', $request->name_area)->first();
            if (!$area) {
                return response()->json(['message' => 'El area no existe'], 404);
            }
            $line->area_id = $area->id;
        }
        $data = $request->all();
        $line->fill($data);
        $line->save();

        return response()->json([
            'message' => UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value) . ' actualizada'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/lines/{id}",
     *     tags={"Lines"},
     *     summary="Delete an line by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Delete an line by ID",
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
     *         description="Line deleted successfully."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $line = Line::find($id);

        if (!$line) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value) . ' no encontrada'], 404);
        }

        try {
            $user = $line->user;

            if ($user) {
                $user->delete();
            }

            $line->delete();

            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::LINE->value) . ' eliminada exitosamente'], 204);
        } catch (\Exception $e) {
            Log::error('Error al realizar la eliminacion', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al realizar la eliminacion'], 500);
        }
    }
}
