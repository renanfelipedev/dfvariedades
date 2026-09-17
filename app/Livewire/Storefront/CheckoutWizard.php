<?php

namespace App\Livewire\Storefront;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class CheckoutWizard extends Component
{
    public bool $isOpen = false;

    public int $currentStep = 1;

    // Step 1: Dados pessoais e endereço
    public string $nome = '';

    public string $whatsapp = '';

    public string $cpf = '';

    public string $email = '';

    public string $tipo_entrega = 'entrega'; // 'entrega' ou 'retirada'

    public string $loja_retirada = 'Irecê - Bahia';

    public string $cep = '';

    public string $rua = '';

    public string $numero = '';

    public string $complemento = '';

    public string $bairro = '';

    public string $cidade = 'Irecê';

    public string $estado = 'BA';

    // Step 2: Opções de frete
    public string $opcao_frete = 'motoboy';

    public float $valor_frete = 0.0;

    public array $shippingOptions = [];

    // Step 3: Pagamento
    public string $forma_pagamento = 'pix'; // 'pix', 'credito', 'debito'

    // Order Success
    public ?Pedido $pedidoCriado = null;

    public string $whatsappRedirectUrl = '';

    public function mount(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->nome = $user->name ?? '';
            $this->email = $user->email ?? '';
        }
    }

    #[On('open-checkout')]
    public function open(CartService $cartService, ShippingService $shippingService): void
    {
        if ($cartService->getCount() === 0) {
            $this->dispatch('toast', message: 'Adicione itens à sacola antes de finalizar o pedido!');

            return;
        }

        $this->currentStep = 1;
        $this->pedidoCriado = null;
        $this->updateShippingOptions($cartService, $shippingService);
        $this->isOpen = true;
    }

    #[On('close-checkout')]
    public function close(): void
    {
        $this->isOpen = false;
    }

    public function goToStep(int $step, CartService $cartService, ShippingService $shippingService): void
    {
        if ($step === 2) {
            $this->validate([
                'nome' => 'required|min:3',
                'whatsapp' => 'required|min:9',
                'cpf' => 'required|min:11',
                'email' => 'required|email',
            ], [
                'nome.required' => 'Informe seu nome completo',
                'whatsapp.required' => 'Informe seu WhatsApp',
                'cpf.required' => 'Informe seu CPF para emissão do pedido',
                'email.required' => 'Informe seu e-mail',
            ]);

            if ($this->tipo_entrega === 'entrega') {
                $this->validate([
                    'cep' => 'required',
                    'rua' => 'required',
                    'numero' => 'required',
                    'bairro' => 'required',
                    'cidade' => 'required',
                    'estado' => 'required|size:2',
                ], [
                    'cep.required' => 'Informe o CEP de entrega',
                    'rua.required' => 'Informe o logradouro / rua',
                    'numero.required' => 'Informe o número',
                    'bairro.required' => 'Informe o bairro',
                    'cidade.required' => 'Informe a cidade',
                ]);
            }

            $this->updateShippingOptions($cartService, $shippingService);
        }

        $this->currentStep = $step;
    }

    public function updateShippingOptions(CartService $cartService, ShippingService $shippingService): void
    {
        $subtotal = $cartService->getSubtotal();
        if ($this->tipo_entrega === 'retirada') {
            $this->valor_frete = 0.0;
            $this->opcao_frete = 'Retirada na Loja ('.$this->loja_retirada.')';
            $this->shippingOptions = [
                [
                    'id' => 'retirada',
                    'nome' => 'Retirada na Loja ('.$this->loja_retirada.')',
                    'prazo' => 'Pronto para retirada em até 2 horas',
                    'valor' => 0.0,
                    'gratis' => true,
                ],
            ];
        } else {
            $options = $shippingService->calculateShipping($this->cep, $this->cidade, $subtotal);
            $this->shippingOptions = $options;
            if (! empty($options)) {
                $selected = collect($options)->firstWhere('id', $this->opcao_frete) ?? $options[0];
                $this->opcao_frete = $selected['nome'];
                $this->valor_frete = (float) $selected['valor'];
            }
        }
    }

    public function selectShippingOption(string $nome, float $valor): void
    {
        $this->opcao_frete = $nome;
        $this->valor_frete = $valor;
    }

    public function finishOrder(CartService $cartService): void
    {
        $cart = $cartService->getCart();
        if (empty($cart)) {
            $this->dispatch('toast', message: 'Sua sacola está vazia!');

            return;
        }

        $totals = $cartService->getTotals($this->valor_frete);

        // Cria o Pedido
        $pedido = Pedido::create([
            'user_id' => Auth::id(),
            'nome_cliente' => $this->nome,
            'whatsapp_cliente' => $this->whatsapp,
            'cpf_cliente' => $this->cpf,
            'email_cliente' => $this->email,
            'tipo_entrega' => $this->tipo_entrega,
            'loja_retirada' => $this->tipo_entrega === 'retirada' ? $this->loja_retirada : null,
            'cep' => $this->tipo_entrega === 'entrega' ? $this->cep : null,
            'rua' => $this->tipo_entrega === 'entrega' ? $this->rua : null,
            'numero' => $this->tipo_entrega === 'entrega' ? $this->numero : null,
            'complemento' => $this->tipo_entrega === 'entrega' ? $this->complemento : null,
            'bairro' => $this->tipo_entrega === 'entrega' ? $this->bairro : null,
            'cidade' => $this->tipo_entrega === 'entrega' ? $this->cidade : null,
            'estado' => $this->tipo_entrega === 'entrega' ? $this->estado : null,
            'opcao_frete' => $this->opcao_frete,
            'valor_frete' => $this->valor_frete,
            'subtotal' => $totals['subtotal'],
            'desconto' => $totals['desconto'],
            'taxa_pagamento' => $totals['taxa_pagamento'],
            'total' => $totals['total'],
            'forma_pagamento' => $this->forma_pagamento,
            'status' => 'pendente',
        ]);

        // Cria os itens
        foreach ($cart as $item) {
            PedidoItem::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $item['id'],
                'nome_produto' => $item['nome'],
                'preco_unitario' => $item['preco'],
                'quantidade' => $item['quantidade'],
                'subtotal' => $item['subtotal'],
                'imagem_url' => $item['imagem'],
            ]);
        }

        $this->pedidoCriado = $pedido;

        // Limpa o carrinho
        $cartService->clear();
        $this->dispatch('cart-updated');

        // Gera o link do WhatsApp
        $msg = "🛍️ *NOVO PEDIDO REALIZADO - DF VARIEDADES*\n";
        $msg .= "Código: *#{$pedido->codigo}*\n\n";
        $msg .= "👤 *Cliente:* {$pedido->nome_cliente}\n";
        $msg .= "📱 *WhatsApp:* {$pedido->whatsapp_cliente}\n";
        $msg .= '💳 *Pagamento:* '.strtoupper($pedido->forma_pagamento)."\n";
        $msg .= "📦 *Entrega:* {$pedido->opcao_frete}\n";
        if ($pedido->tipo_entrega === 'entrega') {
            $msg .= "📍 *Endereço:* {$pedido->rua}, {$pedido->numero} - {$pedido->bairro}, {$pedido->cidade}/{$pedido->estado}\n";
        }
        $msg .= "\n🛒 *Itens do Pedido:*\n";
        foreach ($cart as $item) {
            $msg .= "• {$item['quantidade']}x {$item['nome']} (R$ ".number_format($item['subtotal'], 2, ',', '.').")\n";
        }
        $msg .= "\n💰 *Subtotal:* R$ ".number_format($pedido->subtotal, 2, ',', '.')."\n";
        $msg .= '🚚 *Frete:* R$ '.number_format($pedido->valor_frete, 2, ',', '.')."\n";
        $msg .= '✨ *Total a Pagar:* R$ '.number_format($pedido->total, 2, ',', '.')."\n";

        $this->whatsappRedirectUrl = 'https://wa.me/5574999999999?text='.urlencode($msg);
    }

    public function render(CartService $cartService): View
    {
        $cart = $cartService->getCart();
        $totals = $cartService->getTotals($this->valor_frete);

        return view('livewire.storefront.checkout-wizard', [
            'cart' => $cart,
            'totals' => $totals,
        ]);
    }
}
