<?php

namespace App\Http\Controllers\Resources\Kpi;

use App\Models\Line;

use App\Models\Shift;
use App\Models\Productivity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/productivities",
     *      tags={"KPI Productivity"},
     *      summary="Show all productivity kpi",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of productivity kpi",
     *     @OA\Response(
     *         response=200,
     *         description="Show all productivity kpi."
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
            $productivity = Productivity::all();
            if ($productivity->isEmpty()) {
                return response()->json([
                    'message' => 'No hay KPI de productividad actualmente',
                ], 404);
            }
            return response()->json($productivity);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de productividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by line.
     */
    /**
     * @OA\Get(
     *     path="/api/productivities-line/{id}",
     *      tags={"KPI Productivity"},
     *      summary="Show all productivity kpi by line",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of productivity kpi by line",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Line ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Show all productivity kpi by line."
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
            $productivity = Productivity::where('line_id', $id)->first();
            if (!$productivity) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de productividad para esta linea',
                ], 404);
            }
            return response()->json($productivity);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de productividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by shift.
     */
    /**
     * @OA\Get(
     *     path="/api/productivities-shift/{id}",
     *      tags={"KPI Productivity"},
     *      summary="Show all productivity kpi by shift",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of productivity kpi by shift",
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
     *         description="Show all productivity kpi by shift."
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

            $productivity = Productivity::where('shift_id', $id)->first();
            if (!$productivity) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de productividad para este turno',
                ], 404);
            }
            return response()->json($productivity);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de productividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/productivities/{id}",
     *      tags={"KPI Productivity"},
     *      summary="Create a new productivity KPI",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new productivity KPI with the received data",
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
     *         description="Productivity KPI  object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"packed_tons", "tons_produced", "uuid"},
     *                 @OA\Property(
     *                     property="packed_tons",
     *                     type="string",
     *                     description="The packed tons of the KPI"
     *                 ),
     *                 @OA\Property(
     *                     property="tons_produced",
     *                     type="string",
     *                     description="The tons produced of the KPI"
     *                 ),
     *                 @OA\Property(
     *                     property="uuid",
     *                     type="uuid",
     *                     description="The shift uuid"
     *                 ),
     *                 example={"packed_tons": 4, "tons_produced": 1000, "uuid": "d7f3c2f3-0d4c-4f2e-8b5e-4e6c3c3f4e2c"}
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
                'packed_tons' => 'required|numeric',
                'tons_produced' => 'required|numeric',
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

            $productivity = Productivity::Create([
                'packed_tons' => $request->packed_tons,
                'tons_produced' => $request->tons_produced,
                'line_id' => $id,
                'shift_id' => $request->uuid,
            ]);

            return response()->json([
                'message' => 'Se ha creado el KPI de productividad correctamente',
                'kpi' => $productivity
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al crear el KPI de productividad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
