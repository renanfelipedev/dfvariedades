<?php

namespace App\Livewire\Admin\Produtos;

use App\Models\Categoria;
use App\Models\Colecao;
use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Formulário de Produto')]
class Form extends Component
{
    use WithFileUploads;

    public ?int $produtoId = null;

    public string $nome = '';

    public string $slug = '';

    public ?int $marca_id = null;

    public ?int $colecao_id = null;

    public ?int $categoria_id = null;

    public string $preco = '';

    public string $preco_promocional = '';

    public int $estoque = 10;

    public int $estoque_minimo = 5;

    public string $sku = '';

    public string $descricao = '';

    public string $detalhes = '';

    /**
     * Lista de URLs de imagens já salvas no produto
     *
     * @var array<int, string>
     */
    public array $imagensExistentes = [];

    /**
     * Novos arquivos de imagem enviados via upload
     *
     * @var array<int, mixed>
     */
    public array $novasImagens = [];

    /**
     * Input auxiliar para adicionar imagem por URL avulsa
     */
    public string $novaImagemUrl = '';

    public bool $ativo = true;

    public bool $destaque = false;

    public bool $escolhido = false;

    public bool $presente = false;

    public bool $cabelo = false;

    public bool $flash_deal = false;

    public ?string $flash_deal_fim = null;

    public function mount(Produto|int|null $produto = null, ?int $id = null): void
    {
        $targetProduto = null;

        if ($produto instanceof Produto && $produto->exists) {
            $targetProduto = $produto;
        } elseif (is_numeric($produto)) {
            $targetProduto = Produto::findOrFail($produto);
        } elseif ($id) {
            $targetProduto = Produto::findOrFail($id);
        }

        if ($targetProduto) {
            $this->produtoId = $targetProduto->id;
            $this->nome = $targetProduto->nome;
            $this->slug = $targetProduto->slug;
            $this->marca_id = $targetProduto->marca_id;
            $this->colecao_id = $targetProduto->colecao_id;
            $this->categoria_id = $targetProduto->categoria_id;
            $this->preco = (string) $targetProduto->preco;
            $this->preco_promocional = $targetProduto->preco_promocional ? (string) $targetProduto->preco_promocional : '';
            $this->estoque = $targetProduto->estoque;
            $this->estoque_minimo = $targetProduto->estoque_minimo ?? 5;
            $this->sku = $targetProduto->sku ?? '';
            $this->descricao = $targetProduto->descricao ?? '';
            $this->detalhes = $targetProduto->detalhes ?? '';
            $this->imagensExistentes = is_array($targetProduto->imagens) ? $targetProduto->imagens : [];
            $this->ativo = (bool) $targetProduto->ativo;
            $this->destaque = (bool) $targetProduto->destaque;
            $this->escolhido = (bool) $targetProduto->escolhido;
            $this->presente = (bool) $targetProduto->presente;
            $this->cabelo = (bool) $targetProduto->cabelo;
            $this->flash_deal = (bool) $targetProduto->flash_deal;
            $this->flash_deal_fim = $targetProduto->flash_deal_fim ? $targetProduto->flash_deal_fim->format('Y-m-d\TH:i') : null;
        }
    }

    public function updatedNome(string $value): void
    {
        if (! $this->produtoId) {
            $this->slug = Str::slug($value);
        }
    }

    public function removerImagemExistente(int $index): void
    {
        if (isset($this->imagensExistentes[$index])) {
            unset($this->imagensExistentes[$index]);
            $this->imagensExistentes = array_values($this->imagensExistentes);
        }
    }

    public function removerNovaImagem(int $index): void
    {
        if (isset($this->novasImagens[$index])) {
            unset($this->novasImagens[$index]);
            $this->novasImagens = array_values($this->novasImagens);
        }
    }

    public function definirCapaExistente(int $index): void
    {
        if (isset($this->imagensExistentes[$index])) {
            $capa = $this->imagensExistentes[$index];
            unset($this->imagensExistentes[$index]);
            array_unshift($this->imagensExistentes, $capa);
            $this->imagensExistentes = array_values($this->imagensExistentes);
        }
    }

    public function adicionarImagemPorUrl(): void
    {
        $url = trim($this->novaImagemUrl);
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $this->imagensExistentes[] = $url;
            $this->novaImagemUrl = '';
            $this->dispatch('toast', message: 'URL de imagem adicionada!');
        } else {
            $this->addError('novaImagemUrl', 'Informe uma URL de imagem válida (iniciando com http:// ou https://)');
        }
    }

    public function save(): mixed
    {
        $this->validate([
            'nome' => 'required|min:3',
            'slug' => 'required|unique:produtos,slug,'.($this->produtoId ?: 'NULL').',id',
            'preco' => 'required|numeric|min:0.01',
            'preco_promocional' => 'nullable|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'estoque_minimo' => 'nullable|integer|min:0',
            'novasImagens.*' => 'nullable|image|max:10240', // max 10MB per image
        ], [
            'nome.required' => 'Informe o nome do produto',
            'slug.unique' => 'Este slug já está sendo utilizado',
            'preco.required' => 'Informe o preço do produto',
            'novasImagens.*.image' => 'O arquivo enviado deve ser uma imagem válida (JPG, PNG, WEBP, GIF, AVIF).',
            'novasImagens.*.max' => 'Cada imagem deve ter no máximo 10MB.',
        ]);

        $imagensFinais = $this->imagensExistentes;

        // Processa upload das novas imagens
        if (! empty($this->novasImagens)) {
            foreach ($this->novasImagens as $novaImagem) {
                if ($novaImagem) {
                    $path = $novaImagem->store('produtos', 'public');
                    $imagensFinais[] = Storage::disk('public')->url($path);
                }
            }
        }

        // Se não houver nenhuma imagem, atribui uma padrão elegante
        if (empty($imagensFinais)) {
            $imagensFinais = ['https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=600&auto=format&fit=crop'];
        }

        $data = [
            'nome' => $this->nome,
            'slug' => $this->slug ?: Str::slug($this->nome),
            'marca_id' => $this->marca_id ?: null,
            'colecao_id' => $this->colecao_id ?: null,
            'categoria_id' => $this->categoria_id ?: null,
            'preco' => (float) str_replace(',', '.', $this->preco),
            'preco_promocional' => $this->preco_promocional !== '' ? (float) str_replace(',', '.', $this->preco_promocional) : null,
            'estoque' => $this->estoque,
            'estoque_minimo' => $this->estoque_minimo !== null ? $this->estoque_minimo : 5,
            'sku' => $this->sku ?: null,
            'descricao' => $this->descricao ?: null,
            'detalhes' => $this->detalhes ?: null,
            'imagens' => array_values($imagensFinais),
            'ativo' => $this->ativo,
            'destaque' => $this->destaque,
            'escolhido' => $this->escolhido,
            'presente' => $this->presente,
            'cabelo' => $this->cabelo,
            'flash_deal' => $this->flash_deal,
            'flash_deal_fim' => $this->flash_deal_fim ?: null,
        ];

        if ($this->produtoId) {
            $produto = Produto::findOrFail($this->produtoId);
            $produto->update($data);
            $msg = 'Produto atualizado com sucesso!';
        } else {
            Produto::create($data);
            $msg = 'Produto cadastrado com sucesso!';
        }

        session()->flash('toast', $msg);

        return redirect()->route('admin.produtos.index');
    }

    public function render(): View
    {
        $marcas = Marca::orderBy('nome')->get();
        $colecoes = Colecao::orderBy('nome')->get();
        $categorias = $this->colecao_id
            ? Categoria::where('colecao_id', $this->colecao_id)->orderBy('nome')->get()
            : Categoria::orderBy('nome')->get();

        return view('livewire.admin.produtos.form', [
            'marcas' => $marcas,
            'colecoes' => $colecoes,
            'categorias' => $categorias,
        ]);
    }
}
