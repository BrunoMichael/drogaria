<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ItemOrcamento
 *
 * Representa um item individual dentro de um orçamento, relacionando um produto
 * com quantidade, preço unitário e desconto aplicado.
 *
 * @property int $id
 * @property int $orcamento_id
 * @property int $produto_id
 * @property int $quantidade
 * @property float $preco_unitario
 * @property float|null $desconto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Orcamento $orcamento
 * @property-read \App\Models\Produto $produto
 */
class ItemOrcamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'orcamento_id',
        'produto_id',
        'quantidade',
        'preco_unitario',
        'desconto',
    ];

    /**
     * Relacionamento: item pertence a um orçamento.
     *
     * @return BelongsTo
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Relacionamento: item pertence a um produto.
     *
     * @return BelongsTo
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
