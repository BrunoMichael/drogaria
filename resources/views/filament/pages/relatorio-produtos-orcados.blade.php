<x-filament::page>
    {{ $this->table }}

    <div class="mt-4">
        <h3>Total Geral: {{ $this->getTotalGeral() }}</h3>
    </div>
</x-filament::page>