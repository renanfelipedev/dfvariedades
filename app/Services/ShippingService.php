<?php

namespace App\Services;

class ShippingService
{
    public const FRETE_GRATIS_MINIMO = 150.00;

    public const MOTOBOY_PADRAO = 8.00;

    /**
     * Verifica se a localidade é coberta para entrega direta (Irecê e região)
     */
    public function isDeliveryAvailable(?string $cidade, ?string $uf, ?string $cep): bool
    {
        $digits = preg_replace('/\D/', '', (string) $cep);
        $loc = strtolower(trim((string) $cidade));
        $loc = iconv('UTF-8', 'ASCII//TRANSLIT', $loc) ?: $loc;
        $state = strtoupper(trim((string) $uf));

        if (str_contains($loc, 'irece') && ($state === 'BA' || empty($state))) {
            return true;
        }

        if (str_contains($loc, 'luis eduardo') || str_contains($loc, 'lem')) {
            return true;
        }

        // Faixa de CEP de Irecê (44870-000 a 44879-999)
        if (str_starts_with($digits, '4487')) {
            return true;
        }

        return false;
    }

    /**
     * Calcula as opções de entrega disponíveis
     */
    public function calculateShipping(?string $cep, ?string $cidade, float $subtotal): array
    {
        $options = [];
        $isLocal = $this->isDeliveryAvailable($cidade, 'BA', $cep);
        $isFreteGratis = $subtotal >= self::FRETE_GRATIS_MINIMO;

        if ($isLocal) {
            $valorMotoboy = $isFreteGratis ? 0.00 : self::MOTOBOY_PADRAO;
            $options[] = [
                'id' => 'motoboy',
                'nome' => 'Motoboy DF Express',
                'prazo' => 'Entrega hoje ou em até 24h',
                'valor' => $valorMotoboy,
                'gratis' => $isFreteGratis,
                'destaque' => true,
            ];

            $options[] = [
                'id' => 'retirada_irece',
                'nome' => 'Retirada na Loja (Irecê - BA)',
                'prazo' => 'Pronto em 1 hora',
                'valor' => 0.00,
                'gratis' => true,
                'destaque' => false,
            ];

            $options[] = [
                'id' => 'retirada_lem',
                'nome' => 'Retirada na Loja (Luís Eduardo Magalhães)',
                'prazo' => 'Pronto em 2 horas',
                'valor' => 0.00,
                'gratis' => true,
                'destaque' => false,
            ];
        } else {
            // Entrega nacional / Correios
            $pacValor = $isFreteGratis ? 0.00 : 24.90;
            $options[] = [
                'id' => 'correios_pac',
                'nome' => 'Correios PAC',
                'prazo' => '5 a 8 dias úteis',
                'valor' => $pacValor,
                'gratis' => $isFreteGratis,
                'destaque' => true,
            ];

            $options[] = [
                'id' => 'correios_sedex',
                'nome' => 'Correios SEDEX Express',
                'prazo' => '2 a 3 dias úteis',
                'valor' => 38.50,
                'gratis' => false,
                'destaque' => false,
            ];
        }

        return $options;
    }
}
