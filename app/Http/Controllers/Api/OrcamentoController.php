<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orcamento;
use App\Models\Produto;
use App\Models\Oferta;
use App\Models\Pessoa; // Import the Pessoa model
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon; // Import Carbon for date comparisons

class OrcamentoController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/orcamentos",
     * summary="Lista todos os orçamentos com seus itens, cliente e vendedor",
     * tags={"Orçamentos"},
     * @OA\Response(
     * response=200,
     * description="Lista de orçamentos retornada com sucesso",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="cliente_id", type="integer", example=1),
     * @OA\Property(property="vendedor_id", type="integer", example=2),
     * @OA\Property(property="status", type="string", example="rascunho"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-01T10:00:00.000000Z"),
     * @OA\Property(
     * property="cliente",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="nome", type="string", example="Cliente A")
     * ),
     * @OA\Property(
     * property="vendedor",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="nome", type="string", example="Vendedor B")
     * ),
     * @OA\Property(
     * property="itens",
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="produto_id", type="integer", example=5),
     * @OA\Property(property="quantidade", type="integer", example=3),
     * @OA\Property(property="preco_unitario", type="number", format="float", example=19.99),
     * @OA\Property(property="desconto", type="number", format="float", example=2.5),
     * @OA\Property(
     * property="produto",
     * type="object",
     * @OA\Property(property="id", type="integer", example=5),
     * @OA\Property(property="descricao", type="string", example="Produto X")
     * )
     * )
     * )
     * )
     * )
     * )
     * )
     */
    public function index(): JsonResponse
    {
        $orcamentos = Orcamento::with(['cliente', 'vendedor', 'itens.produto'])->get();
        return response()->json($orcamentos);
    }

    /**
     * @OA\Get(
     * path="/api/orcamentos/{id}",
     * summary="Obtém os detalhes de um orçamento específico",
     * tags={"Orçamentos"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID do orçamento",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Detalhes do orçamento retornados com sucesso",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="cliente_id", type="integer", example=1),
     * @OA\Property(property="vendedor_id", type="integer", example=2),
     * @OA\Property(property="status", type="string", example="rascunho"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-01T10:00:00.000000Z"),
     * @OA\Property(
     * property="cliente",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="nome", type="string", example="Cliente A")
     * ),
     * @OA\Property(
     * property="vendedor",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="nome", type="string", example="Vendedor B")
     * ),
     * @OA\Property(
     * property="itens",
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="produto_id", type="integer", example=5),
     * @OA\Property(property="quantidade", type="integer", example=3),
     * @OA\Property(property="preco_unitario", type="number", format="float", example=19.99),
     * @OA\Property(property="desconto", type="number", format="float", example=2.5),
     * @OA\Property(
     * property="produto",
     * type="object",
     * @OA\Property(property="id", type="integer", example=5),
     * @OA\Property(property="descricao", type="string", example="Produto X")
     * )
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Orçamento não encontrado"
     * )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $orcamento = Orcamento::with(['cliente', 'vendedor', 'itens.produto'])->findOrFail($id);
        return response()->json($orcamento);
    }

    /**
     * @OA\Post(
     * path="/api/orcamentos",
     * summary="Cria um novo orçamento",
     * tags={"Orçamentos"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"cliente_id", "vendedor_id", "itens"},
     * @OA\Property(property="cliente_id", type="integer", example=1, description="ID da Pessoa que é o Cliente"),
     * @OA\Property(property="vendedor_id", type="integer", example=2, description="ID da Pessoa que é o Vendedor"),
     * @OA\Property(
     * property="itens",
     * type="array",
     * @OA\Items(
     * type="object",
     * required={"produto_id", "quantidade"},
     * @OA\Property(property="produto_id", type="integer", example=5),
     * @OA\Property(property="quantidade", type="integer", example=3)
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Orçamento criado com sucesso",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="cliente_id", type="integer", example=1),
     * @OA\Property(property="vendedor_id", type="integer", example=2),
     * @OA\Property(property="status", type="string", example="rascunho"),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-01T10:00:00.000000Z"),
     * @OA\Property(
     * property="cliente",
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="nome", type="string", example="Cliente A")
     * ),
     * @OA\Property(
     * property="vendedor",
     * type="object",
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="nome", type="string", example="Vendedor B")
     * ),
     * @OA\Property(
     * property="itens",
     * type="array",
     * @OA\Items(
     * type="object",
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="produto_id", type="integer", example=5),
     * @OA\Property(property="quantidade", type="integer", example=3),
     * @OA\Property(property="preco_unitario", type="number", format="float", example=19.99),
     * @OA\Property(property="desconto", type="number", format="float", example=2.5),
     * @OA\Property(
     * property="produto",
     * type="object",
     * @OA\Property(property="id", type="integer", example=5),
     * @OA\Property(property="descricao", type="string", example="Produto X")
     * )
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Erro de validação.",
     * @OA\JsonContent(
     * example={
     * "errors": {
     * "campo": {
     * "mensagem"
     * }
     * }
     * }
     * )
     * )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'cliente_id' => [
                    'required',
                    'exists:pessoas,id',
                    // Custom validation rule to check if client is also a vendor
                    function ($attribute, $value, $fail) use ($request) {
                        $vendedorId = $request->input('vendedor_id');
                        if ($vendedorId && $value == $vendedorId) {
                            $fail('O cliente não pode ser o mesmo que o vendedor.');
                        }
                        // Optionally, check if the person is actually marked as a client
                        if (!Pessoa::where('id', $value)->where('eh_cliente', true)->exists()) {
                            $fail('O ID do cliente não corresponde a uma pessoa marcada como cliente.');
                        }
                    },
                ],
                'vendedor_id' => [
                    'required',
                    'exists:pessoas,id',
                    // Optionally, check if the person is actually marked as a vendor
                    function ($attribute, $value, $fail) {
                        if (!Pessoa::where('id', $value)->where('eh_vendedor', true)->exists()) {
                            $fail('O ID do vendedor não corresponde a uma pessoa marcada como vendedor.');
                        }
                    },
                ],
                // Removed 'data_orcamento' as it's not present in your Filament form and might be redundant with 'created_at'
                'itens' => 'required|array|min:1',
                'itens.*.produto_id' => 'required|exists:produtos,id',
                'itens.*.quantidade' => 'required|integer|min:1',
            ]);

            // Create the Orcamento with a default status (e.g., 'rascunho')
            $orcamento = Orcamento::create([
                'cliente_id' => $validatedData['cliente_id'],
                'vendedor_id' => $validatedData['vendedor_id'],
                'status' => 'rascunho', // Set a default status for new budgets
                // 'data_orcamento' would go here if you decide to add it
            ]);

            foreach ($validatedData['itens'] as $itemData) {
                $produto = Produto::findOrFail($itemData['produto_id']);
                $precoUnitario = $produto->preco;
                $quantidade = $itemData['quantidade'];
                $descontoAplicado = 0; // Default no discount

                // --- Start: Replicate Filament's Discount Logic ---
                // Find the best applicable offer
                $oferta = Oferta::where('produto_id', $produto->id)
                    ->where('quantidade_levar', '<=', $quantidade)
                    ->whereDate('data_validade', '>=', Carbon::today())
                    ->orderBy('quantidade_levar', 'desc') // Get the offer with the largest 'quantidade_levar' first
                    ->first();

                if ($oferta) {
                    $grupos = floor($quantidade / $oferta->quantidade_levar);
                    $sobra = $quantidade % $oferta->quantidade_levar;

                    $quantidadePaga = $grupos * $oferta->quantidade_pagar + $sobra;

                    $totalSemDesconto = $quantidade * $precoUnitario;
                    $totalComDesconto = $quantidadePaga * $precoUnitario;

                    if ($totalSemDesconto > 0) { // Avoid division by zero
                        $descontoAplicado = round((1 - ($totalComDesconto / $totalSemDesconto)) * 100, 2);
                    }
                }
                // --- End: Replicate Filament's Discount Logic ---

                $orcamento->itens()->create([
                    'produto_id' => $produto->id,
                    'quantidade' => $quantidade, // Use the quantity from the request
                    'preco_unitario' => $precoUnitario,
                    'desconto' => $descontoAplicado, // Apply the calculated discount
                ]);
            }

            // Return the created budget with all its relationships loaded
            return response()->json($orcamento->load(['itens.produto', 'cliente', 'vendedor']), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return response()->json(['error' => 'Ocorreu um erro ao criar o orçamento.', 'message' => $e->getMessage()], 500);
        }
    }
}