<?php

namespace App\Http\Controllers\Resources\Kpi;

use App\Models\Line;
use App\Models\Shift;
use App\Models\Cleanliness;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CleanlinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/cleanlinesses",
     *      tags={"KPI Cleanliness"},
     *      summary="Show all cleanliness kpi",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of cleanliness kpi",
     *     @OA\Response(
     *         response=200,
     *         description="Show all cleanliness kpi."
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
            $cleanliness = Cleanliness::all();
            if ($cleanliness->isEmpty()) {
                return response()->json([
                    'message' => 'No hay KPI de limpieza actualmente',
                ], 404);
            }
            return response()->json($cleanliness);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de limpieza',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by line.
     */
    /**
     * @OA\Get(
     *     path="/api/cleanlinesses-line/{id}",
     *      tags={"KPI Cleanliness"},
     *      summary="Show all cleanliness kpi by line",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of cleanliness kpi by line",
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
     *         description="Show all cleanliness kpi by line."
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
            $cleanliness = Cleanliness::where('line_id', $id)->get();
            if ($cleanliness->isEmpty()) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de limpieza para esta linea',
                ], 404);
            }
            return response()->json($cleanliness);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de limpieza',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by shift.
     */
    /**
     * @OA\Get(
     *     path="/api/cleanlinesses-shift/{id}",
     *      tags={"KPI Cleanliness"},
     *      summary="Show all cleanliness kpi by shift",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of cleanliness kpi by shift",
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
     *         description="Show all cleanliness kpi by shift."
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

            $cleanliness = Cleanliness::where('shift_id', $id)->get();
            if ($cleanliness->isEmpty()) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de limpieza para este turno',
                ], 404);
            }
            return response()->json($cleanliness);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de limpieza',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/cleanlinesses/{id}",
     *      tags={"KPI Cleanliness"},
     *      summary="Create a new cleanliness KPI",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new cleanliness KPI with the received data",
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
     *         description="Cleanliness KPI object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"is_done", "comment", "uuid"},
     *                 @OA\Property(
     *                     property="is_done",
     *                     type="boolean",
     *                     description="The cleanliness status"
     *                 ),
     *                 @OA\Property(
     *                     property="comment",
     *                     type="boolean",
     *                     description="The cleanliness comment"
     *                 ),
     *                @OA\Property(
     *                     property="uuid",
     *                     type="uuid",
     *                     description="The shift uuid"
     *                 ),
     *                 example={"is_done": true, "comment": "Este es un comentario", "uuid": "c6b6f8c0-6f7b-11eb-9439-0242ac130002"}
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
                'is_done' => 'required|boolean',
                'comment' => 'required|string',
                'uuid' => 'required|uuid'
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

            $exists = Cleanliness::where('line_id', $id)->where('shift_id', $request->uuid)->first();

            if ($exists) {
                return response()->json([
                    'message' => 'Ya existe un KPI de limpieza para esta linea y turno',
                ], 400);
            }

            $cleanliness = Cleanliness::Create([
                'is_done' => $request->is_done,
                'comment' => $request->comment,
                'line_id' => $id,
                'shift_id' => $request->uuid,
            ]);

            return response()->json([
                'message' => 'Se ha creado el KPI de limpieza correctamente',
                'kpi' => $cleanliness
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al crear el KPI de limpieza',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
