<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Vendedor
 *
 * Representa um vendedor que realiza orçamentos para clientes.
 *
 * @property int $id
 * @property int $codigo
 * @property string $nome
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Orcamento[] $orcamentos
 * @property-read int|null $orcamentos_count
 */
class Vendedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    /**
     * Gera o código sequencial automático para o vendedor.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($vendedor) {
            $ultimoVendedor = self::orderByDesc('codigo')->first();
            if ($ultimoVendedor) {
                $vendedor->codigo = $ultimoVendedor->codigo + 1;
            } else {
                $vendedor->codigo = 1001;
            }
        });
    }

    /**
     * Relacionamento: Vendedor possui muitos orçamentos.
     *
     * @return HasMany
     */
    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class);
    }
}
