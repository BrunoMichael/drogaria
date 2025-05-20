<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProdutoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/produtos",
     *     summary="Listar todos os produtos",
     *     tags={"Produtos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="codigo", type="string", example="ABC123"),
     *                 @OA\Property(property="descricao", type="string", example="Produto Exemplo"),
     *                 @OA\Property(property="preco", type="number", format="float", example=99.99),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-20T14:30:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T14:30:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $produtos = Produto::all();
        return response()->json($produtos);
    }

    /**
     * @OA\Get(
     *     path="/api/produtos/{id}",
     *     summary="Exibir um produto específico",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="codigo", type="string", example="ABC123"),
     *             @OA\Property(property="descricao", type="string", example="Produto Exemplo"),
     *             @OA\Property(property="preco", type="number", format="float", example=99.99),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-20T14:30:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T14:30:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $produto = Produto::findOrFail($id);
        return response()->json($produto);
    }

    /**
     * @OA\Post(
     *     path="/api/produtos",
     *     summary="Criar um novo produto",
     *     tags={"Produtos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"codigo", "descricao", "preco"},
     *             @OA\Property(property="codigo", type="string", example="ABC123"),
     *             @OA\Property(property="descricao", type="string", example="Produto Exemplo"),
     *             @OA\Property(property="preco", type="number", format="float", example=99.99)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="codigo", type="string", example="ABC123"),
     *             @OA\Property(property="descricao", type="string", example="Produto Exemplo"),
     *             @OA\Property(property="preco", type="number", format="float", example=99.99),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-20T14:30:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T14:30:00Z")
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
                'codigo' => 'required|unique:produtos|max:255',
                'descricao' => 'required|max:255',
                'preco' => 'required|numeric|min:0',
            ]);
            $produto = Produto::create($validatedData);
            return response()->json($produto, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/produtos/{id}",
     *     summary="Atualizar um produto",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="codigo", type="string", example="XYZ999"),
     *             @OA\Property(property="descricao", type="string", example="Produto Atualizado"),
     *             @OA\Property(property="preco", type="number", format="float", example=149.90)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="codigo", type="string", example="XYZ999"),
     *             @OA\Property(property="descricao", type="string", example="Produto Atualizado"),
     *             @OA\Property(property="preco", type="number", format="float", example=149.90),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-20T14:30:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-20T14:30:00Z")
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
     *         description="Produto não encontrado"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $produto = Produto::findOrFail($id);
        try {
            $validatedData = $request->validate([
                'codigo' => 'sometimes|required|unique:produtos,codigo,' . $id . '|max:255',
                'descricao' => 'sometimes|required|max:255',
                'preco' => 'sometimes|required|numeric|min:0',
            ]);
            $produto->update($validatedData);
            return response()->json($produto);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/produtos/{id}",
     *     summary="Deletar um produto",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Produto removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();
        return response()->json(null, 204);
    }
}
