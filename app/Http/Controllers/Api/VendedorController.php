<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VendedorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/vendedores",
     *     summary="Retorna a lista de todos os vendedores cadastrados",
     *     tags={"Vendedores"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista completa de vendedores",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Maria dos Santos"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T12:00:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $vendedors = Vendedor::all();
        return response()->json($vendedors);
    }

    /**
     * @OA\Get(
     *     path="/api/vendedores/{id}",
     *     summary="Consulta um vendedor específico pelo ID",
     *     tags={"Vendedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identificador único do vendedor",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do vendedor encontrados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nome", type="string", example="Maria dos Santos"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vendedor não encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $vendedor = Vendedor::findOrFail($id);
        return response()->json($vendedor);
    }

    /**
     * @OA\Post(
     *     path="/api/vendedores",
     *     summary="Cria um novo vendedor",
     *     tags={"Vendedores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome"},
     *             @OA\Property(property="nome", type="string", maxLength=255, example="Maria dos Santos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vendedor criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nome", type="string", example="Maria dos Santos"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'nome' => 'required|max:255',
            ]);
            $vendedor = Vendedor::create($validatedData);
            return response()->json($vendedor, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/vendedores/{id}",
     *     summary="Atualiza os dados de um vendedor existente",
     *     tags={"Vendedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identificador do vendedor",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", maxLength=255, example="Maria dos Santos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendedor atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="nome", type="string", example="Maria dos Santos"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-02T12:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vendedor não encontrado"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $vendedor = Vendedor::findOrFail($id);
        try {
            $validatedData = $request->validate([
                'nome' => 'sometimes|required|max:255',
            ]);
            $vendedor->update($validatedData);
            return response()->json($vendedor);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/vendedores/{id}",
     *     summary="Exclui um vendedor pelo ID",
     *     tags={"Vendedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do vendedor a ser removido",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Vendedor excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vendedor não encontrado"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->delete();
        return response()->json(null, 204);
    }
}
