<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pessoa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PessoaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/pessoas",
     *     summary="Retorna a lista de todas as pessoas cadastradas",
     *     tags={"Pessoas"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista completa de pessoas",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="eh_cliente", type="boolean"),
     *             @OA\Property(property="eh_vendedor", type="boolean"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         ))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(Pessoa::all());
    }

    /**
     * @OA\Get(
     *     path="/api/pessoas/{id}",
     *     summary="Consulta uma pessoa específica pelo ID",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados da pessoa",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="eh_cliente", type="boolean"),
     *             @OA\Property(property="eh_vendedor", type="boolean"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Pessoa não encontrada")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $pessoa = Pessoa::findOrFail($id);
        return response()->json($pessoa);
    }

    /**
     * @OA\Post(
     *     path="/api/pessoas",
     *     summary="Cria uma nova pessoa",
     *     tags={"Pessoas"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome"},
     *             @OA\Property(property="nome", type="string", maxLength=255),
     *             @OA\Property(property="eh_cliente", type="boolean"),
     *             @OA\Property(property="eh_vendedor", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Pessoa criada com sucesso"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'eh_cliente' => 'boolean',
                'eh_vendedor' => 'boolean',
            ]);

            $pessoa = Pessoa::create($validated);
            return response()->json($pessoa, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/pessoas/{id}",
     *     summary="Atualiza os dados de uma pessoa",
     *     tags={"Pessoas"},
     *     @OA\Parameter(
     *         name="id", in="path", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", maxLength=255),
     *             @OA\Property(property="eh_cliente", type="boolean"),
     *             @OA\Property(property="eh_vendedor", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pessoa atualizada com sucesso"),
     *     @OA\Response(response=422, description="Erro de validação"),
     *     @OA\Response(response=404, description="Pessoa não encontrada")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $pessoa = Pessoa::findOrFail($id);

        try {
            $validated = $request->validate([
                'nome' => 'sometimes|required|string|max:255',
                'eh_cliente' => 'sometimes|boolean',
                'eh_vendedor' => 'sometimes|boolean',
            ]);

            $pessoa->update($validated);
            return response()->json($pessoa);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/pessoas/{id}",
     *     summary="Exclui uma pessoa",
     *     tags={"Pessoas"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Pessoa excluída com sucesso"),
     *     @OA\Response(response=404, description="Pessoa não encontrada")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $pessoa = Pessoa::findOrFail($id);
        $pessoa->delete();

        return response()->json(null, 204);
    }
}
