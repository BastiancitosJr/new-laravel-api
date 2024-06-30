<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/shifts",
     *      tags={"Shifts"},
     *      summary="Show all shifts",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a list of shifts",
     *     @OA\Response(
     *         response=200,
     *         description="Show all shifts."
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
            $shifts = Shift::all();
            if ($shifts->isEmpty()) {
                return response()->json([
                    'message' => 'No se han encontrado turnos',
                ], 404);
            }

            return response()->json($shifts);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener los turnos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource by line.
     */
    /**
     * @OA\Get(
     *     path="/api/shifts/{id}",
     *     tags={"Shifts"},
     *     summary="Show shift by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Returns an shift",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Resource ID",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Show only one shift."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $shift = Shift::find($id);
            if (!$shift) {
                return response()->json([
                    'message' => 'No se ha encontrado el turno',
                ], 404);
            }

            return response()->json($shift);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener el turno',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the active shift.
     */
    /**
     * @OA\Get(
     *     path="/api/shifts/active",
     *      tags={"Shifts"},
     *      summary="Show the active shift",
     *      security={{"bearerAuth":{}}},
     *      description="Returns an active shift",
     *     @OA\Response(
     *         response=200,
     *         description="Show the active shift."
     *     ),
     *     @OA\Response(
     *        response=404,
     *       description="No active shift."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function active()
    {
        try {
            $shift = Shift::where('end_time', null)->first();
            if (!$shift) {
                return response()->json([
                    'message' => 'No hay turno activo',
                ], 404);
            }

            return response()->json($shift);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al obtener el turno activo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *      path="/api/shifts/open",
     *      tags={"Shifts"},
     *      summary="Start a new shift",
     *      security={{"bearerAuth":{}}},
     *      description="Start a new shift",
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
    public function store()
    {
        try {
            if (Shift::where('end_time', null)->first()) {
                return response()->json([
                    'message' => 'Ya existe un turno activo',
                ], 400);
            }
            $currentTime = Carbon::now();

            if ($currentTime->lt(Carbon::parse($currentTime->toDateString() . ' 12:00:00'))) {
                $shift_name = 'DIA';
            } elseif ($currentTime->lt(Carbon::parse($currentTime->toDateString() . ' 20:00:00'))) {
                $shift_name = 'TARDE';
            } else {
                $shift_name = 'NOCHE';
            }

            $shift = Shift::create([
                'shift' => $shift_name,
                'end_time' => null,
                'shift_manager_id' => auth()->user()->shiftmanager->id,
            ]);

            return response()->json($shift, 201);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Se ha producido un error al crear el turno',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Close the active shift.
     */
    /**
     * @OA\Post(
     *     path="/api/shifts/close",
     *      tags={"Shifts"},
     *      summary="Close the active shift",
     *      security={{"bearerAuth":{}}},
     *      description="Returns a active shift",
     *     @OA\Response(
     *         response=200,
     *         description="Close the active shift."
     *     ),
     *     @OA\Response(
     *        response=404,
     *       description="No active shift."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="An error occurred."
     *     )
     * )
     */
    public function close()
    {
        try {
            $shift = Shift::where('end_time', null)->first();
            if (!$shift) {
                return response()->json([
                    'message' => 'No hay turno activo',
                ], 404);
            }

            $shift->end_time = now();
            $shift->save();

            return response()->json([
                'message' => 'Se ha cerrado el turno correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Se ha producido un error al actualizar el turno',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
