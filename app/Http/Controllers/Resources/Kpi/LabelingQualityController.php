<?php

namespace App\Http\Controllers\Resources\Kpi;

use App\Models\Line;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Models\LabelingQuality;
use App\Http\Controllers\Controller;

class LabelingQualityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/labeling-quality",
     *      tags={"KPI Labeling Quality"},
     *      summary="Show all labeling quality kpi",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of labeling quality kpi",
     *     @OA\Response(
     *         response=200,
     *         description="Show all labeling quality kpi."
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
            $labeling_quality = LabelingQuality::all();
            if ($labeling_quality->isEmpty()) {
                return response()->json([
                    'message' => 'No hay KPI de calidad de etiquetado actualmente',
                ], 404);
            }
            return response()->json($labeling_quality);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de calidad de etiquetado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by line.
     */
    /**
     * @OA\Get(
     *     path="/api/labeling-quality-line/{id}",
     *      tags={"KPI Labeling Quality"},
     *      summary="Show all labeling quality kpi by line",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of labeling quality kpi by line",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Resource ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Show all labeling quality kpi by line."
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
            $labeling_quality = LabelingQuality::where('line_id', $id)->get();
            if ($labeling_quality->isEmpty()) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de calidad de etiquetado para esta linea',
                ], 404);
            }
            return response()->json($labeling_quality);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de calidad de etiquetado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by shift.
     */
    /**
     * @OA\Get(
     *     path="/api/labeling-quality-shift/{id}",
     *      tags={"KPI Labeling Quality"},
     *      summary="Show all labeling quality kpi by shift",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of labeling quality kpi by shift",
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
     *         description="Show all labeling quality kpi by shift."
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

            $labeling_quality = LabelingQuality::where('shift_id', $id)->get();
            if ($labeling_quality->isEmpty()) {
                return response()->json([
                    'message' => 'No se han encontrado KPI de calidad de etiquetado para este turno',
                ], 404);
            }
            return response()->json($labeling_quality);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los KPI de calidad de etiquetado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/labeling-quality/{id}",
     *      tags={"KPI Labeling Quality"},
     *      summary="Create a new labeling quality KPI",
     *      security={{"bearerAuth":{}}},
     *      description="Creates a new labeling quality KPI with the received data",
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
     *         description="labeling quality KPI object that needs to be created",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"deviations", "audits", "line_name"},
     *                 @OA\Property(
     *                     property="deviations",
     *                     type="integer",
     *                     description="The deviations of the KPI"
     *                 ),
     *                 @OA\Property(
     *                     property="audits",
     *                     type="integer",
     *                     description="The audits of the KPI"
     *                 ),
     *                 @OA\Property(
     *                     property="comment",
     *                     type="string",
     *                     description="The comment of the KPI"
     *                 ),
     *                 @OA\Property(
     *                     property="uuid",
     *                     type="uuid",
     *                     description="The shift uuid"
     *                 ),
     *                 example={"deviations": 1, "audits": 4, "comment": "Este es un comentario", "uuid": "123e4567-e89b-12d3-a456-426614174000"}
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
                'deviations' => 'required|numeric',
                'audits' => 'required|numeric',
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

            $labeling_quality = LabelingQuality::Create([
                'deviations' => $request->deviations,
                'audits' => $request->audits,
                'comment' => $request->comment,
                'line_id' => $id,
                'shift_id' => $request->uuid,
            ]);

            return response()->json([
                'message' => 'Se ha creado el KPI de calidad de etiquetado correctamente',
                'kpi' => $labeling_quality
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al crear el KPI de calidad de etiquetado',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
