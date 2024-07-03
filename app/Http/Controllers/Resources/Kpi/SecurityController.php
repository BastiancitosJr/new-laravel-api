<?php

namespace App\Http\Controllers\Resources\Kpi;

use App\Models\Line;
use App\Models\Shift;
use App\Models\Security;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SecurityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/securities",
     *      tags={"KPI Security"},
     *      summary="Show all security kpi",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of security kpi",
     *     @OA\Response(
     *         response=200,
     *         description="Show all security kpi."
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
            $security = Security::all();
            if ($security->isEmpty()) {
                return response()->json([
                    'message' => 'No hay KPI de seguridad actualmente',
                ], 404);
            }
            return response()->json($security);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de seguridad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by line.
     */
    /**
     * @OA\Get(
     *     path="/api/securities-line/{id}",
     *      tags={"KPI Security"},
     *      summary="Show all security kpi by line",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of security kpi by line",
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
     *         description="Show all security kpi by line."
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
            $security = Security::where('line_id', $id)->first();
            if ($security->isEmpty()) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de seguridad para esta linea',
                ], 404);
            }
            return response()->json($security);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de seguridad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by shift.
     */
    /**
     * @OA\Get(
     *     path="/api/securities-shift/{id}",
     *      tags={"KPI Security"},
     *      summary="Show all security kpi by shift",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of security kpi by shift",
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
     *         description="Show all security kpi by shift."
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

            $security = Security::where('shift_id', $id)->first();
            if ($security->isEmpty()) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de seguridad para este turno',
                ], 404);
            }
            return response()->json($security);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de seguridad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/securities/{id}",
     *      tags={"KPI Security"},
     *      summary="Create a new security KPI",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new security KPI with the received data",
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
     *         description="security KPI object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"comment", "uuid"},
     *                 @OA\Property(
     *                     property="comment",
     *                     type="string",
     *                     description="The peer observations comment"
     *                 ),
     *                @OA\Property(
     *                     property="uuid",
     *                     type="uuid",
     *                     description="The shift uuid"
     *                 ),
     *                 example={"comment": "Este es un comentario", "uuid": "c6b6f8c0-6f7b-11eb-9439-0242ac130002"}
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
                'comment' => 'required|string',
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

            $exists = Security::where('line_id', $id)->where('shift_id', $request->uuid)->first();

            if ($exists) {
                return response()->json([
                    'message' => 'Ya existe un KPI de seguridad para esta linea y turno',
                ], 400);
            }

            $security = Security::Create([
                'comment' => $request->comment,
                'line_id' => $id,
                'shift_id' => $request->uuid,
            ]);

            return response()->json([
                'message' => 'Se ha creado el KPI de seguridad correctamente',
                'kpi' => $security
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al crear el KPI de seguridad',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
