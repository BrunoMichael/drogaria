<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orcamento;
use App\Models\Produto;
use App\Models\Oferta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrcamentoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/orcamentos",
     *     summary="Lista todos os orçamentos com seus itens, cliente e vendedor",
     *     tags={"Orçamentos"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de orçamentos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="cliente_id", type="integer", example=1),
     *                 @OA\Property(property="vendedor_id", type="integer", example=2),
     *                 @OA\Property(property="data_orcamento", type="string", format="date", example="2024-05-01"),
     *                 @OA\Property(
     *                     property="cliente",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nome", type="string", example="Cliente A")
     *                 ),
     *                 @OA\Property(
     *                     property="vendedor",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="nome", type="string", example="Vendedor B")
     *                 ),
     *                 @OA\Property(
     *                     property="itens",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=10),
     *                         @OA\Property(property="produto_id", type="integer", example=5),
     *                         @OA\Property(property="quantidade", type="integer", example=3),
     *                         @OA\Property(property="preco_unitario", type="number", format="float", example=19.99),
     *                         @OA\Property(property="desconto", type="number", format="float", example=2.5),
     *                         @OA\Property(
     *                             property="produto",
     *                             type="object",
     *                             @OA\Property(property="id", type="integer", example=5),
     *                             @OA\Property(property="nome", type="string", example="Produto X")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $orcamentos = Orcamento::with(['cliente', 'vendedor', 'itens.produto'])->get();
        return response()->json($orcamentos);
    }

    /**
     * @OA\Get(
     *     path="/api/orcamentos/{id}",
     *     summary="Obtém os detalhes de um orçamento específico",
     *     tags={"Orçamentos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do orçamento",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do orçamento retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="vendedor_id", type="integer", example=2),
     *             @OA\Property(property="data_orcamento", type="string", format="date", example="2024-05-01"),
     *             @OA\Property(
     *                 property="cliente",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Cliente A")
     *             ),
     *             @OA\Property(
     *                 property="vendedor",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="nome", type="string", example="Vendedor B")
     *             ),
     *             @OA\Property(
     *                 property="itens",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="produto_id", type="integer", example=5),
     *                     @OA\Property(property="quantidade", type="integer", example=3),
     *                     @OA\Property(property="preco_unitario", type="number", format="float", example=19.99),
     *                     @OA\Property(property="desconto", type="number", format="float", example=2.5),
     *                     @OA\Property(
     *                         property="produto",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="nome", type="string", example="Produto X")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Orçamento não encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $orcamento = Orcamento::with(['cliente', 'vendedor', 'itens.produto'])->findOrFail($id);
        return response()->json($orcamento);
    }

    /**
     * @OA\Post(
     *     path="/api/orcamentos",
     *     summary="Cria um novo orçamento",
     *     tags={"Orçamentos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"cliente_id", "vendedor_id", "data_orcamento", "itens"},
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="vendedor_id", type="integer", example=2),
     *             @OA\Property(property="data_orcamento", type="string", format="date", example="2024-05-01"),
     *             @OA\Property(
     *                 property="itens",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"produto_id", "quantidade"},
     *                     @OA\Property(property="produto_id", type="integer", example=5),
     *                     @OA\Property(property="quantidade", type="integer", example=3)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Orçamento criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="cliente_id", type="integer", example=1),
     *             @OA\Property(property="vendedor_id", type="integer", example=2),
     *             @OA\Property(property="data_orcamento", type="string", format="date", example="2024-05-01"),
     *             @OA\Property(
     *                 property="cliente",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Cliente A")
     *             ),
     *             @OA\Property(
     *                 property="vendedor",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="nome", type="string", example="Vendedor B")
     *             ),
     *             @OA\Property(
     *                 property="itens",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="produto_id", type="integer", example=5),
     *                     @OA\Property(property="quantidade", type="integer", example=3),
     *                     @OA\Property(property="preco_unitario", type="number", format="float", example=19.99),
     *                     @OA\Property(property="desconto", type="number", format="float", example=2.5),
     *                     @OA\Property(
     *                         property="produto",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=5),
     *                         @OA\Property(property="nome", type="string", example="Produto X")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação.",
     *         @OA\JsonContent(
     *             example={
     *                 "errors": {
     *                     "campo": {
     *                         "mensagem"
     *                     }
     *                 }
     *             }
     *         )
     *     )

     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'vendedor_id' => 'required|exists:vendedores,id',
                'data_orcamento' => 'required|date',
                'itens' => 'required|array|min:1',
                'itens.*.produto_id' => 'required|exists:produtos,id',
                'itens.*.quantidade' => 'required|integer|min:1',
            ]);

            $clienteNome = optional(\App\Models\Cliente::find($validatedData['cliente_id']))->nome;
            $vendedorNome = optional(\App\Models\Vendedor::find($validatedData['vendedor_id']))->nome;

            if (strtolower(trim($clienteNome)) === strtolower(trim($vendedorNome))) {
                throw ValidationException::withMessages([
                    'cliente_id' => ['O cliente não pode ser o mesmo que o vendedor.'],
                ]);
            }

            $orcamento = Orcamento::create([
                'cliente_id' => $validatedData['cliente_id'],
                'vendedor_id' => $validatedData['vendedor_id'],
                'data_orcamento' => $validatedData['data_orcamento'],
            ]);

            foreach ($validatedData['itens'] as $itemData) {
                $produto = Produto::findOrFail($itemData['produto_id']);
                $precoUnitario = $produto->preco;
                $desconto = Oferta::where('produto_id', $produto->id)
                    ->whereDate('inicio', '<=', now())
                    ->whereDate('fim', '>=', now())
                    ->value('desconto') ?? 0;

                $orcamento->itens()->create([
                    'produto_id' => $produto->id,
                    'quantidade' => $itemData['quantidade'],
                    'preco_unitario' => $precoUnitario,
                    'desconto' => $desconto,
                ]);
            }

            return response()->json($orcamento->load(['itens.produto', 'cliente', 'vendedor']), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        }
    }
}
