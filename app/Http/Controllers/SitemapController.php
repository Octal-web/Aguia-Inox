<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use App\Models\OpcionalCategoria;
use App\Models\Pagina;
use App\Models\Post;
use App\Models\ProdutoCategoria;
use App\Models\Segmento;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController
{
    public function __invoke()
    {
        $sitemap = Sitemap::create();

        $paginas = Pagina::query()
            ->where('excluido', null)
            ->get();

        foreach ($paginas as $pagina) {
            $route = $pagina->controladora . '.' . $pagina->acao;

            $ultimaModificacao = Conteudo::query()
                ->where([
                    'excluido' => NULL,
                    'controladora' => $pagina->controladora,
                    'acao' => $pagina->acao
                ])
                ->orderByDesc('modificado')
                ->first();

            if (
                Route::has($route) &&
                $pagina->acao !== 'enviar' && $pagina->controladora !== 'Pesquisar'
            ) {
                $sitemap->add(
                    Url::create(route($route))
                        ->setLastModificationDate(
                            $ultimaModificacao->modificado ?? $ultimaModificacao->criado ?? $pagina->modificado ?? $pagina->criado
                        )
                        ->setPriority($pagina->controladora === 'Politicas' ? 0.3 : 1.0)
                );
            }
        }

        Segmento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->get()
            ->each(function ($segmento) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Segmentos.segmento', [
                            'slug' => $segmento->slug,
                        ])
                    )
                        ->setLastModificationDate($segmento->modificado ?? $segmento->criado)
                        ->setPriority(0.8)
                );
            });

        ProdutoCategoria::query()
            ->with(['segmento', 'produtos'])
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($categoria) use ($sitemap) {
                foreach ($categoria->produtos as $produto) {

                    $sitemap->add(
                        Url::create(
                            route('Produtos.produto', [
                                'segmento' => $categoria->segmento->slug,
                                'slug' => $produto->slug,
                            ])
                        )
                            ->setLastModificationDate($produto->modificado ?? $produto->criado)
                            ->setPriority(0.7)
                    );
                }
            });

        Post::query()
            ->with(['postCategoria'])
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($post) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('News.post', [
                            'categoria' => $post->postCategoria->slug,
                            'slug' => $post->slug,
                        ])
                    )
                        ->setLastModificationDate($post->modificado ?? $post->criado)
                        ->setPriority(0.6)
                );
            });

        OpcionalCategoria::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($opcional) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Opcionais.opcional', [
                            'categoria' => $opcional->slug,
                        ])
                    )
                        ->setLastModificationDate($opcional->modificado ?? $opcional->criado)
                        ->setPriority(0.6)
                );
            });

        return $sitemap;
    }
}
