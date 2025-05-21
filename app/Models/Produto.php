<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Produto
 *
 * Representa um produto disponível na drogaria.
 *
 * @property int $id
 * @property int $codigo
 * @property string $descricao
 * @property float $preco
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Oferta[] $ofertas
 * @property-read int|null $ofertas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ItemOrcamento[] $itensOrcamento
 * @property-read int|null $itensOrcamento_count
 */
class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao',
        'preco',
    ];

    /**
     * Evento de criação do produto para gerar o código incremental único.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($produto) {
            $ultima = self::orderByDesc('codigo')->first();
            $produto->codigo = $ultima ? $ultima->codigo + 1 : 1001;
        });
    }

    /**
     * Relacionamento: Produto pode ter muitas ofertas.
     *
     * @return HasMany
     */
    public function ofertas(): HasMany
    {
        return $this->hasMany(Oferta::class);
    }

    /**
     * Relacionamento: Produto pode estar em muitos itens de orçamento.
     *
     * @return HasMany
     */
    public function itensOrcamento(): HasMany
    {
        return $this->hasMany(ItemOrcamento::class);
    }
}
