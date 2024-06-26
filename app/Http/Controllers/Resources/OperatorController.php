<?php

namespace App\Http\Controllers\Resources;

use App\Models\Area;
use App\Models\Line;
use App\Models\User;
use App\Models\Operator;
use App\Enums\UserRolesEnum;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Resources\Create\OperatorRequest as CreateOperatorRequest;
use App\Http\Requests\Resources\Update\OperatorRequest as UpdateOperatorRequest;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/operators",
     *      tags={"Operators"},
     *      summary="Show all operators",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of operators",
     *     @OA\Response(
     *         response=200,
     *         description="Show all operators."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function index()
    {
        // Obtain all Operators
        $operators = Operator::orderBy('id', 'asc')->get();

        if ($operators->isEmpty()) {
            return response()->json(['message' => 'No hay ningun ' . UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value)], 404);
        }

        $operators->each(function ($operator) {
            $operator->user;
            $operator->line;
            $operator->area;
        });

        return response()->json($operators);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/operators",
     *      tags={"Operators"},
     *      summary="Create a new operator",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new operator with the received data",
     *      @OA\RequestBody(
     *         description="Operator object that needs to be created. Username & password are optional in case the operator doesn't need a user account",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "rut", "name_area", "name_line"},
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     description="The operator's username"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="The operator's password"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The operator's name"
     *                 ),
     *                 @OA\Property(
     *                     property="rut",
     *                     type="string",
     *                     description="The operator's rut"
     *                 ),
     *                 @OA\Property(
     *                     property="name_area",
     *                     type="string",
     *                     description="The area to which the operator belongs"
     *                 ),
     *                 @OA\Property(
     *                     property="name_line",
     *                     type="string",
     *                     description="The line to which the operator belongs"
     *                 ),
     *                 example={"username": "new_operator@example.com", "password": "newPassword", "name": "New Operator", "rut": "22.222.222-2", "name_area": "Ventas", "name_line": "Linea de envasado 1"}
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
    public function store(CreateOperatorRequest $request)
    {
        try {
            $area = Area::where('name', $request->name_area)->first();
            $line = Line::where('name', $request->name_line)->first();
            $user = null;
            if ($area && $line) {
                if ($request->username) {
                    $user = User::create([
                        'username' => $request->username,
                        'password' => bcrypt($request->password),
                        'role_id' => UserRolesEnum::OPERATOR->value,
                    ]);

                    $operator = Operator::create([
                        'name' => $request->name,
                        'rut' => $request->rut,
                        'user_id' => $user->id,
                        'line_id' => $line->id,
                        'area_id' => $area->id,
                    ]);
                } else {
                    $operator = Operator::create([
                        'name' => $request->name,
                        'rut' => $request->rut,
                        'user_id' => null,
                        'line_id' => $line->id,
                        'area_id' => $area->id,
                    ]);
                }

                return response()->json([
                    'message' => UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value) . ' creado',
                    'user' => $user,
                    'entity' => $operator
                ], 201);
            } else {
                return response()->json(['message' => 'El area o linea no existe'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value), ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al crear ' . UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value), 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/operators/{id}",
     *     tags={"Operators"},
     *     summary="Show operator",
     *     security={{"bearerAuth":{}}},
     *     description="Returns a operator",
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
     *         description="Show only one operator."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function show(string $id)
    {
        $operator = Operator::find($id);
        if (!$operator) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value) . ' no encontrado'], 404);
        }
        $operator->user;
        $operator->line;
        $operator->area;
        return response()->json($operator);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *      path="/api/operators/{id}",
     *      operationId="updateOperator",
     *      tags={"Operators"},
     *      summary="Update existing operator",
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
     *         description="Operator object that needs to be updated. You can send 0 or more attributes in the body. If you send only one attribute, only that attribute will be updated.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="The name of the operator"
     *                 ),
     *                 @OA\Property(
     *                     property="name_area",
     *                     type="string",
     *                     description="The area to which the operator belongs"
     *                 ),
     *                 @OA\Property(
     *                     property="name_line",
     *                     type="string",
     *                     description="The line to which the operator belongs"
     *                ),
     *                 example={"name": "Updated Operator", "name_area": "Mantenimiento", "name_line": "Linea de envasado 1"}
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
    public function update(UpdateOperatorRequest $request, string $id)
    {
        $operator = Operator::find($id);

        if (!$operator) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value) . ' no encontrado'], 404);
        }
        if ($request->name_area) {
            $area = Area::where('name', $request->name_area)->first();
            if (!$area) {
                return response()->json(['message' => 'El area no existe'], 404);
            }
            $request['area_id'] = $area->id;
        }

        if ($request->name_line) {
            $line = Line::where('name', $request->name_line)->first();
            if (!$line) {
                return response()->json(['message' => 'La linea no existe'], 404);
            }
            $request['line_id'] = $line->id;
        }

        $data = $request->all();
        $operator->fill($data);
        $operator->save();

        return response()->json([
            'message' => UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value) . ' actualizado'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/operators/{id}",
     *     tags={"Operators"},
     *     summary="Delete an operator by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Delete an operator by ID",
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
     *         description="Operator deleted successfully."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $operator = Operator::find($id);

        if (!$operator) {
            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value) . ' no encontrado'], 404);
        }

        try {
            $user = $operator->user;

            if ($user) {
                $user->delete();
            }

            $operator->delete();

            return response()->json(['message' => UserRolesEnum::idToRoleString(UserRolesEnum::OPERATOR->value) . ' eliminado exitosamente'], 204);
        } catch (\Exception $e) {
            Log::error('Error al realizar la eliminacion', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al realizar la eliminacion'], 500);
        }
    }
}
