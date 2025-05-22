<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Pessoa
 *
 * Representa uma pessoa que pode ser cliente, vendedor ou ambos.
 *
 * @property int $id
 * @property int $codigo
 * @property string $nome
 * @property bool $eh_cliente
 * @property bool $eh_vendedor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Orcamento[] $orcamentos
 * @property-read int|null $orcamentos_count
 */
class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'eh_cliente',
        'eh_vendedor',
    ];

    protected $casts = [
        'eh_cliente' => 'boolean',
        'eh_vendedor' => 'boolean',
    ];

    /**
     * Evento de criação da pessoa para gerar o código incremental único.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($pessoa) {
            $ultima = self::orderByDesc('codigo')->first();
            $pessoa->codigo = $ultima ? $ultima->codigo + 1 : 1001;
        });

        static::deleting(function ($pessoa) {
            if ($pessoa->orcamentos()->exists()) {
                throw new \Exception('Não é possível excluir uma pessoa com orçamentos relacionados.');
            }
        });
    }

    /**
     * Relacionamento: Pessoa possui muitos orçamentos.
     * Apenas se for cliente.
     */
    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class, 'cliente_id');
    }
}
