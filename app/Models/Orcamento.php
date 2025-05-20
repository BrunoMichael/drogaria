<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Orcamento
 *
 * Representa um orçamento feito por um vendedor para um cliente,
 * contendo diversos itens orçados.
 *
 * @property int $id
 * @property int $vendedor_id
 * @property int $cliente_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Vendedor $vendedor
 * @property-read \App\Models\Cliente $cliente
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ItemOrcamento[] $itens
 * @property-read int|null $itens_count
 */
class Orcamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendedor_id',
        'cliente_id',
    ];

    /**
     * Relacionamento: Orcamento pertence a um vendedor.
     *
     * @return BelongsTo
     */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Vendedor::class);
    }

    /**
     * Relacionamento: Orcamento pertence a um cliente.
     *
     * @return BelongsTo
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacionamento: Orcamento possui muitos itens.
     *
     * @return HasMany
     */
    public function itens(): HasMany
    {
        return $this->hasMany(ItemOrcamento::class);
    }
}
