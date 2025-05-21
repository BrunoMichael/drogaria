<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Oferta
 *
 * Representa uma promoção do tipo "Leve X, Pague Y" associada a um produto.
 *
 * @property int $id
 * @property int $produto_id
 * @property int $quantidade_levar
 * @property int $quantidade_pagar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\Produto $produto
 */
class Oferta extends Model
{
    use HasFactory;

    protected $fillable = [
        'produto_id',
        'quantidade_levar',
        'quantidade_pagar',
        'data_validade',
    ];

    /**
     * Relacionamento: Oferta pertence a um produto.
     *
     * @return BelongsTo
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
