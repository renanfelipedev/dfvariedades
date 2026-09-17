<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Categoria;
use App\Models\Colecao;
use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class StorefrontSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Marcas
        $marcasData = [
            [
                'nome' => 'O Boticário',
                'logo_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=300&auto=format&fit=crop',
                'descricao' => 'Perfumes clássicos, cremes hidratantes e maquiagens consagradas.',
                'cor' => '#B8892E',
                'ordem' => 1,
                'destaque' => true,
            ],
            [
                'nome' => 'Natura',
                'logo_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?q=80&w=300&auto=format&fit=crop',
                'descricao' => 'A força da biodiversidade brasileira em cuidados diários e fragrâncias.',
                'cor' => '#C9A84C',
                'ordem' => 2,
                'destaque' => true,
            ],
            [
                'nome' => 'Eudora',
                'logo_url' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?q=80&w=300&auto=format&fit=crop',
                'descricao' => 'Fragrâncias marcantes, maquiagens de alta performance e linha Siàge.',
                'cor' => '#9E7D30',
                'ordem' => 3,
                'destaque' => true,
            ],
            [
                'nome' => 'Wepink',
                'logo_url' => 'https://images.unsplash.com/photo-1526947425960-945c6e72858f?q=80&w=300&auto=format&fit=crop',
                'descricao' => 'Body splashes doces e envolventes e cosméticos virais.',
                'cor' => '#D4A843',
                'ordem' => 4,
                'destaque' => true,
            ],
            [
                'nome' => 'Cimed',
                'logo_url' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=300&auto=format&fit=crop',
                'descricao' => 'Hidratantes labiais Carmed e suplementação de beleza.',
                'cor' => '#E0C068',
                'ordem' => 5,
                'destaque' => false,
            ],
        ];

        $marcas = [];
        foreach ($marcasData as $mData) {
            $marcas[$mData['nome']] = Marca::updateOrCreate(
                ['slug' => Str::slug($mData['nome'])],
                array_merge($mData, ['slug' => Str::slug($mData['nome'])])
            );
        }

        // 2. Coleções e Categorias
        $colecoesData = [
            [
                'nome' => 'Perfumaria Feminina',
                'descricao' => 'As fragrâncias florais, orientais e doces mais desejadas.',
                'imagem_url' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=600&auto=format&fit=crop',
                'banner_url' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=1200&auto=format&fit=crop',
                'ordem' => 1,
                'destaque' => true,
                'categorias' => ['Eau de Parfum', 'Desodorante Colônia', 'Body Splash'],
            ],
            [
                'nome' => 'Perfumaria Masculina',
                'descricao' => 'Fragrâncias amadeiradas, frescas e sofisticadas para todos os estilos.',
                'imagem_url' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=600&auto=format&fit=crop',
                'banner_url' => 'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=1200&auto=format&fit=crop',
                'ordem' => 2,
                'destaque' => true,
                'categorias' => ['Amadeirados', 'Cítricos & Frescos', 'Orientais'],
            ],
            [
                'nome' => 'Cuidados com o Cabelo',
                'descricao' => 'Shampoos, máscaras de tratamento e óleos reconstrutores.',
                'imagem_url' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?q=80&w=600&auto=format&fit=crop',
                'banner_url' => 'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?q=80&w=1200&auto=format&fit=crop',
                'ordem' => 3,
                'destaque' => true,
                'categorias' => ['Máscaras Capilares', 'Kits de Tratamento', 'Finalizadores & Óleos'],
            ],
            [
                'nome' => 'Presentes & Kits Especiais',
                'descricao' => 'Kits presenteáveis completos com embalagem de luxo.',
                'imagem_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?q=80&w=600&auto=format&fit=crop',
                'banner_url' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?q=80&w=1200&auto=format&fit=crop',
                'ordem' => 4,
                'destaque' => true,
                'categorias' => ['Kits Femininos', 'Kits Masculinos', 'Lembrancinhas'],
            ],
            [
                'nome' => 'Skincare & Corpo',
                'descricao' => 'Hidratação profunda, óleos corporais e proteção facial.',
                'imagem_url' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=600&auto=format&fit=crop',
                'banner_url' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=1200&auto=format&fit=crop',
                'ordem' => 5,
                'destaque' => false,
                'categorias' => ['Hidratantes Corporais', 'Protetor Solar', 'Cuidados Labiais'],
            ],
        ];

        $colecoes = [];
        $categorias = [];

        foreach ($colecoesData as $cData) {
            $cats = $cData['categorias'] ?? [];
            unset($cData['categorias']);

            $col = Colecao::updateOrCreate(
                ['slug' => Str::slug($cData['nome'])],
                array_merge($cData, ['slug' => Str::slug($cData['nome'])])
            );
            $colecoes[$col->nome] = $col;

            foreach ($cats as $index => $catNome) {
                $categorias[$catNome] = Categoria::updateOrCreate(
                    ['slug' => Str::slug($catNome)],
                    [
                        'colecao_id' => $col->id,
                        'nome' => $catNome,
                        'slug' => Str::slug($catNome),
                        'ordem' => $index + 1,
                        'ativo' => true,
                    ]
                );
            }
        }

        // 3. Banners
        Banner::truncate();
        Banner::create([
            'titulo' => 'Lançamentos de Perfumaria & Beleza DF Variedades',
            'tipo_midia' => 'imagem',
            'url_midia' => 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=1400&auto=format&fit=crop',
            'link_tipo' => 'colecao',
            'link_id' => (string) ($colecoes['Perfumaria Feminina']->id ?? 1),
            'ordem' => 1,
            'ativo' => true,
        ]);
        Banner::create([
            'titulo' => 'Kits Perfeitos para Presentear com Frete Grátis',
            'tipo_midia' => 'imagem',
            'url_midia' => 'https://images.unsplash.com/photo-1513151233558-d860c5398176?q=80&w=1400&auto=format&fit=crop',
            'link_tipo' => 'colecao',
            'link_id' => (string) ($colecoes['Presentes & Kits Especiais']->id ?? 4),
            'ordem' => 2,
            'ativo' => true,
        ]);

        // 4. Produtos
        Produto::truncate();

        $produtosData = [
            // Flash deal
            [
                'nome' => 'Kit Lily Eau de Parfum + Creme Acetinado 250g',
                'marca' => 'O Boticário',
                'colecao' => 'Perfumaria Feminina',
                'categoria' => 'Eau de Parfum',
                'preco' => 389.90,
                'preco_promocional' => 289.90,
                'descricao' => 'O clássico Lily traz a pureza dos lírios com textura aveludada inconfundível.',
                'detalhes' => 'Contém: 1 Lily Eau de Parfum 75ml + 1 Creme Acetinado Corporal 250g. Fixação de mais de 12 horas na pele.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=700&auto=format&fit=crop',
                    'https://images.unsplash.com/photo-1547887537-6158d64c35b3?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => true,
                'flash_deal_fim' => Carbon::now()->addHours(14),
                'destaque' => true,
                'escolhido' => true,
                'presente' => true,
                'cabelo' => false,
                'estoque' => 15,
            ],
            // Escolhidos
            [
                'nome' => 'Malbec Black Desodorante Colônia 100ml',
                'marca' => 'O Boticário',
                'colecao' => 'Perfumaria Masculina',
                'categoria' => 'Amadeirados',
                'preco' => 249.90,
                'preco_promocional' => 199.90,
                'descricao' => 'Inspirado no misterioso processo de envelhecimento em carvalho negro.',
                'detalhes' => 'Fragrância oriental amadeirada marcante e sedutora com notas de carvalho tostado.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => true,
                'presente' => false,
                'cabelo' => false,
                'estoque' => 20,
            ],
            [
                'nome' => 'Essencial Supreme Feminino Deo Parfum 100ml',
                'marca' => 'Natura',
                'colecao' => 'Perfumaria Feminina',
                'categoria' => 'Eau de Parfum',
                'preco' => 265.00,
                'preco_promocional' => 219.00,
                'descricao' => 'Combinação exuberante da flor de ylang com a sofisticação das madeiras.',
                'detalhes' => 'Dura o dia todo. Perfeito para ocasiões noturnas e celebrações marcantes.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => true,
                'presente' => true,
                'cabelo' => false,
                'estoque' => 12,
            ],
            [
                'nome' => 'Kit Siàge Reconstrói os Fios (Shampoo + Condicionador + Máscara)',
                'marca' => 'Eudora',
                'colecao' => 'Cuidados com o Cabelo',
                'categoria' => 'Kits de Tratamento',
                'preco' => 219.90,
                'preco_promocional' => 179.90,
                'descricao' => 'Reconstrói até 1 ano de danos já na primeira aplicação com Óleo de Argan.',
                'detalhes' => 'Enriquecido com biotecnologia Affinité 4D para cabelos macios, fortes e com brilho espelhado.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1527799820374-dcf8d9d4a388?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => true,
                'presente' => false,
                'cabelo' => true,
                'estoque' => 25,
            ],
            // Perfeitos para Presentear
            [
                'nome' => 'Estojo Presente Eudora Deluxe com Clutch Exclusiva',
                'marca' => 'Eudora',
                'colecao' => 'Presentes & Kits Especiais',
                'categoria' => 'Kits Femininos',
                'preco' => 299.90,
                'preco_promocional' => 249.90,
                'descricao' => 'Um presente luxuoso com Eau de Parfum, loção acetinada e clutch de gala.',
                'detalhes' => 'Acompanha caixa rígida dourada especial para presentear com elegância.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1513151233558-d860c5398176?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => false,
                'presente' => true,
                'cabelo' => false,
                'estoque' => 8,
            ],
            [
                'nome' => 'Kit Natura Tododia Algodão Completo',
                'marca' => 'Natura',
                'colecao' => 'Presentes & Kits Especiais',
                'categoria' => 'Kits Femininos',
                'preco' => 149.90,
                'preco_promocional' => 119.90,
                'descricao' => 'Nutrição prebiótica com fragrância suave e confortável de algodão.',
                'detalhes' => 'Contém 1 Hidratante 400ml + 1 Caixa de Sabonetes em Barra com 5 unidades + 1 Body Splash 200ml.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => false,
                'presente' => true,
                'cabelo' => false,
                'estoque' => 30,
            ],
            // Top Cabelo
            [
                'nome' => 'Óleo Capilar Siàge Nutri Rosé 60ml',
                'marca' => 'Eudora',
                'colecao' => 'Cuidados com o Cabelo',
                'categoria' => 'Finalizadores & Óleos',
                'preco' => 79.90,
                'preco_promocional' => 64.90,
                'descricao' => 'Anti-tesoura: nutre profundamente e sela pontas duplas sem pesar.',
                'detalhes' => 'Com Elixir de Rosas e Argila Vermelha. Proteção térmica até 230°C.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1608248597359-0a8a65c27633?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => false,
                'presente' => false,
                'cabelo' => true,
                'estoque' => 40,
            ],
            [
                'nome' => 'Máscara Match Science Reconstrução 250g',
                'marca' => 'O Boticário',
                'colecao' => 'Cuidados com o Cabelo',
                'categoria' => 'Máscaras Capilares',
                'preco' => 84.90,
                'preco_promocional' => 69.90,
                'descricao' => 'Tecnologia avançada com Peptídeos Inteligentes e Óleo de Amaranto.',
                'detalhes' => 'Age diretamente na fibra capilar danificada por processos químicos.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1535585209827-a15fcdbc4c2d?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => false,
                'presente' => false,
                'cabelo' => true,
                'estoque' => 18,
            ],
            // Wepink & Cimed
            [
                'nome' => 'Body Splash VF Aqua 200ml',
                'marca' => 'Wepink',
                'colecao' => 'Perfumaria Feminina',
                'categoria' => 'Body Splash',
                'preco' => 119.90,
                'preco_promocional' => 89.90,
                'descricao' => 'Fragrância aquática e floral vibrante com excelente projeção.',
                'detalhes' => 'Ideal para uso diário após o banho, proporcionando frescor prolongado.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1526947425960-945c6e72858f?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => false,
                'presente' => false,
                'cabelo' => false,
                'estoque' => 22,
            ],
            [
                'nome' => 'Carmed Fini Beijos Hidratante Labial 10g',
                'marca' => 'Cimed',
                'colecao' => 'Skincare & Corpo',
                'categoria' => 'Cuidados Labiais',
                'preco' => 29.90,
                'preco_promocional' => 22.90,
                'descricao' => 'Aroma doce e nostálgico de Fini Beijos com alto poder de hidratação.',
                'detalhes' => 'Contém manteiga de cacau e efeito gloss labial suave.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1586495777744-4413f21062fa?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => false,
                'escolhido' => false,
                'presente' => false,
                'cabelo' => false,
                'estoque' => 60,
            ],
            [
                'nome' => 'Natura Homem Sagaz Deo Parfum 100ml',
                'marca' => 'Natura',
                'colecao' => 'Perfumaria Masculina',
                'categoria' => 'Orientais',
                'preco' => 219.00,
                'preco_promocional' => 169.90,
                'descricao' => 'Combinação ousada da madeira de cumaru com licor e notas de pimenta.',
                'detalhes' => 'Para homens modernos e confiantes. Fixação intensa.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1523293182086-7651a899d37f?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => false,
                'presente' => true,
                'cabelo' => false,
                'estoque' => 16,
            ],
            [
                'nome' => 'Elysée Eau de Parfum 50ml',
                'marca' => 'O Boticário',
                'colecao' => 'Perfumaria Feminina',
                'categoria' => 'Eau de Parfum',
                'preco' => 319.90,
                'preco_promocional' => 269.90,
                'descricao' => 'Fragrância chipre floral com frasco lapidado como uma joia.',
                'detalhes' => 'Ingredientes nobres como Mandarina Orpur e Rosa de Maio.',
                'imagens' => [
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?q=80&w=700&auto=format&fit=crop',
                ],
                'flash_deal' => false,
                'destaque' => true,
                'escolhido' => true,
                'presente' => true,
                'cabelo' => false,
                'estoque' => 10,
            ],
        ];

        foreach ($produtosData as $index => $pData) {
            $marca = $marcas[$pData['marca']] ?? null;
            $colecao = $colecoes[$pData['colecao']] ?? null;
            $categoria = $categorias[$pData['categoria']] ?? null;

            unset($pData['marca'], $pData['colecao'], $pData['categoria']);

            Produto::create(array_merge($pData, [
                'marca_id' => $marca?->id,
                'colecao_id' => $colecao?->id,
                'categoria_id' => $categoria?->id,
                'slug' => Str::slug($pData['nome']),
                'sku' => 'DFV-'.str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
                'ordem' => $index + 1,
                'ativo' => true,
            ]));
        }
    }
}
