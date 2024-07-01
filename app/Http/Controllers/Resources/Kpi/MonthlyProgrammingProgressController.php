<?php

namespace App\Http\Controllers\Resources\Kpi;

use App\Models\Line;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MonthlyProgrammingProgress;

class MonthlyProgrammingProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/monthly-pps",
     *      tags={"KPI Monthly Programming Progress"},
     *      summary="Show all monthly programming progress kpi",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of monthly programming progress kpi",
     *     @OA\Response(
     *         response=200,
     *         description="Show all monthly programming progress kpi."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function index()
    {
        try {
            $monthly_programming = MonthlyProgrammingProgress::all();
            if ($monthly_programming->isEmpty()) {
                return response()->json([
                    'message' => 'No hay progreso de programación mensual actualmente',
                ], 404);
            }
            return response()->json($monthly_programming);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los historicos de progreso de programación mensual',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by line.
     */
    /**
     * @OA\Get(
     *     path="/api/monthly-pps-line/{id}",
     *      tags={"KPI Monthly Programming Progress"},
     *      summary="Show all monthly programming progress kpi by line",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of monthly programming progress kpi by line",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Line ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *      @OA\Response(
     *         response=200,
     *         description="Show all monthly programming progress kpi by line."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function indexLine(string $id)
    {
        try {
            if (!Line::find($id)) {
                return response()->json([
                    'message' => 'No existe la linea',
                ], 404);
            }
            $monthly_programming = MonthlyProgrammingProgress::where('line_id', $id)->get();
            if ($monthly_programming->isEmpty()) {
                return response()->json([
                    'message' => 'No se ha encontrado progreso de programación mensual para esta linea',
                ], 404);
            }
            return response()->json($monthly_programming);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener el progreso de programación mensual',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by shift.
     */
    /**
     * @OA\Get(
     *     path="/api/monthly-pps-shift/{id}",
     *      tags={"KPI Monthly Programming Progress"},
     *      summary="Show all monthly programming progress kpi by shift",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of monthly programming progress kpi by shift",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Shift ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Show all monthly programming progress kpi by shift."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function indexShift(string $id)
    {
        try {
            $shift = Shift::find($id);
            if (!$shift) {
                return response()->json([
                    'message' => 'No existe el turno',
                ], 404);
            }

            $monthly_programming = MonthlyProgrammingProgress::where('shift_id', $id)->get();
            if ($monthly_programming->isEmpty()) {
                return response()->json([
                    'message' => 'No se ha encontrado progreso de programación mensual para este turno',
                ], 404);
            }
            return response()->json($monthly_programming);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener el progreso de programación mensual',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/monthly-pps/{id}",
     *      tags={"KPI Monthly Programming Progress"},
     *      summary="Create a new monthly programming progress KPI",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new monthly programming progress KPI with the received data",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Line ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *      @OA\RequestBody(
     *         description="monthly programming progress KPI object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"monthly_order", "line_name"},
     *                 @OA\Property(
     *                     property="monthly_order",
     *                     type="integer",
     *                     description="The monthly programming order"
     *                 ),
     *                 @OA\Property(
     *                     property="uuid",
     *                     type="uuid",
     *                     description="The shift uuid"
     *                 ),
     *                 example={"monthly_order": 40000, "uuid": "123e4567-e89b-12d3-a456-426614174000"}
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
    public function store(Request $request, string $id)
    {
        try {
            $this->validate($request, [
                'monthly_order' => 'required|numeric',
                'uuid' => 'required|uuid',
            ]);

            if (!Line::find($id)) {
                return response()->json([
                    'message' => 'No se ha encontrado la linea',
                ], 404);
            }

            $shift = Shift::find($request->uuid);
            if (!$shift) {
                return response()->json([
                    'message' => 'No existe el turno',
                ], 404);
            }
            if ($shift->end_time) {
                return response()->json([
                    'message' => 'El turno ha finalizado',
                ], 404);
            }

            $monthly_programming = MonthlyProgrammingProgress::Create([
                'monthly_order' => $request->monthly_order,
                'line_id' => $id,
                'shift_id' => $request->uuid,
            ]);

            return response()->json([
                'message' => 'Se ha creado el progreso de programación mensual correctamente',
                'kpi' => $monthly_programming
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al crear el progreso de programación mensual',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     *  Update the specified resource in storage.
     */
    /**
     * @OA\Put(
     *      path="/api/monthly-pps/{id}",
     *      operationId="updateMonthlyProgrammingProgress",
     *      tags={"KPI Monthly Programming Progress"},
     *      summary="Update existing Monthly Programming Progress KPI",
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
     *         description="Monthly Programming Progress KPI object that needs to be updated.",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="monthly_order",
     *                     type="string",
     *                     description="The monthly order of the monthly programming progress"
     *                 ),
     *                 example={"monthly_order": 50000}
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
    public function update(Request $request, string $id)
    {
        try {
            $this->validate($request, [
                'monthly_order' => 'required|numeric',
            ]);

            $monthly_programming = MonthlyProgrammingProgress::find($id);
            if (!$monthly_programming) {
                return response()->json([
                    'message' => 'No se ha encontrado el progreso de programación mensual',
                ], 404);
            }
            $monthly_programming->update([
                'monthly_order' => $request->monthly_order,
            ]);

            return response()->json([
                'message' => 'Se ha actualizado el progreso de programación mensual correctamente',
                'kpi' => $monthly_programming
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al actualizar el progreso de programación mensual',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
