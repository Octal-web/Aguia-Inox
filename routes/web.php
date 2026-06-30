<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstitucionalController;
use App\Http\Controllers\SegmentosController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\OpcionaisController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PesquisarController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ParceirosController;
use App\Http\Controllers\TrabalheConoscoController;
use App\Http\Controllers\PoliticasController;

use App\Http\Controllers\Manager\UsuariosController as UsuariosController;
use App\Http\Controllers\Manager\PaginasController as ManagerPaginasController;
use App\Http\Controllers\Manager\ConteudosController as ManagerConteudosController;
use App\Http\Controllers\Manager\ImagensController as ManagerImagensController;
use App\Http\Controllers\Manager\FinderController as ManagerFinderController;
use App\Http\Controllers\Manager\HomeController as ManagerHomeController;
use App\Http\Controllers\Manager\SlidesController as ManagerSlidesController;
use App\Http\Controllers\Manager\ClientesController as ManagerClientesController;
use App\Http\Controllers\Manager\SelosController as ManagerSelosController;
use App\Http\Controllers\Manager\DiferenciaisController as ManagerDiferenciaisController;
use App\Http\Controllers\Manager\InstitucionalController as ManagerInstitucionalController;
use App\Http\Controllers\Manager\SegmentosController as ManagerSegmentosController;
use App\Http\Controllers\Manager\ProdutosCategoriasController as ManagerProdutosCategoriasController;
use App\Http\Controllers\Manager\ProdutosController as ManagerProdutosController;
use App\Http\Controllers\Manager\ImagensProdutosController as ManagerImagensProdutosController;
use App\Http\Controllers\Manager\OpcionaisController as ManagerOpcionaisController;
use App\Http\Controllers\Manager\OpcionaisModelosController as ManagerOpcionaisModelosController;
use App\Http\Controllers\Manager\OpcionaisCategoriasController as ManagerOpcionaisCategoriasController;
use App\Http\Controllers\Manager\DownloadsController as ManagerDownloadsController;
use App\Http\Controllers\Manager\ContatoController as ManagerContatoController;
use App\Http\Controllers\Manager\DepartamentosController as ManagerDepartamentosController;
use App\Http\Controllers\Manager\NewsletterController as ManagerNewsletterController;
use App\Http\Controllers\Manager\ParceriasController as ManagerParceriasController;
use App\Http\Controllers\Manager\NewsController as ManagerNewsController;
use App\Http\Controllers\Manager\PostsController as ManagerPostsController;
use App\Http\Controllers\Manager\PostsCategoriasController as ManagerPostsCategoriasController;
use App\Http\Controllers\Manager\PoliticasController as ManagerPoliticasController;
use App\Http\Controllers\Manager\TrabalheConoscoController as ManagerTrabalheConoscoController;
use App\Http\Controllers\SitemapController;

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('Home.index');

    Route::get('/sitemap.xml', [SitemapController::class, '__invoke'])->name('Sitemap.index');

    Route::get('/empresa', [InstitucionalController::class, 'index'])->name('Institucional.index');

    Route::get('/segmentos/{slug?}', [SegmentosController::class, 'segmento'])->name('Segmentos.segmento');

    Route::get('/produto/{segmento?}/{slug?}', [ProdutosController::class, 'produto'])->name('Produtos.produto');
    Route::get('/produto/{segmento?}/{slug?}/download/{id?}', [ProdutosController::class, 'downloadArquivo'])->name('Produtos.downloadArquivo');

    Route::get('/news', [NewsController::class, 'index'])->name('News.index');
    Route::get('/news/{categoria?}/{slug?}', [NewsController::class, 'post'])->name('News.post');

    Route::get('/opcionais', [OpcionaisController::class, 'index'])->name('Opcionais.index');
    Route::get('/opcionais/{categoria?}', [OpcionaisController::class, 'opcional'])->name('Opcionais.opcional');

    Route::get('/pesquisar', [PesquisarController::class, 'index'])->name('Pesquisar.index');

    Route::get('/contato', [ContatoController::class, 'index'])->name('Contato.index');
    Route::post('/contato/enviar', [ContatoController::class, 'enviar'])->name('Contato.enviar');

    Route::post('/newsletter/enviar', [NewsletterController::class, 'enviar'])->name('Newsletter.enviar');

    Route::post('/parceiros/enviar', [ParceirosController::class, 'enviar'])->name('Parceiros.enviar');

    Route::get('/trabalhe-conosco', [TrabalheConoscoController::class, 'index'])->name('TrabalheConosco.index');

    Route::get('/politica-de-privacidade', [PoliticasController::class, 'privacidade'])->name('Politicas.privacidade');
    Route::get('/politica-de-cookies', [PoliticasController::class, 'cookies'])->name('Politicas.cookies');
    Route::get('/politica-canal-de-denuncias', [PoliticasController::class, 'canalDenuncia'])->name('Politicas.canalDenuncia');
});

Route::prefix('/manager')->group(function () {
    Route::get('/', [UsuariosController::class, 'login'])->name('Manager.Usuarios.login');
    Route::post('/', ['as' => 'login', 'uses' => 'App\Http\Controllers\Manager\UsuariosController@authenticate']);

    Route::post('/usuarios/logout', [UsuariosController::class, 'logout'])->name('Manager.Usuarios.logout');

    Route::group(['middleware' => ['auth']], function () {
        Route::post('/paginas/editar/{id}', [ManagerPaginasController::class, 'editarAction'])->name('Manager.Paginas.editar');

        Route::post('/conteudos/editar/{id}', [ManagerConteudosController::class, 'editarAction'])->name('Manager.Conteudos.editar');
        Route::post('/conteudos/baixar-arquivo/{id}', [ManagerConteudosController::class, 'baixarArquivo'])->name('Manager.Conteudos.baixarArquivo');

        Route::get('/imagens/{id}', [ManagerImagensController::class, 'conteudo'])->name('Manager.Imagens.conteudo');
        Route::post('/imagens/conteudo/adicionar/{id}', [ManagerImagensController::class, 'novo'])->name('Manager.Imagens.novo');

        Route::post('/imagens/conteudo/ordenar/{id}', [ManagerImagensController::class, 'ordenar'])->name('Manager.Imagens.ordenar');
        Route::post('/imagens/conteudo/visibilidade/{id}', [ManagerImagensController::class, 'visibilidade'])->name('Manager.Imagens.visibilidade');
        Route::post('/imagens/conteudo/excluir/{id}', [ManagerImagensController::class, 'excluir'])->name('Manager.Imagens.excluir');

        Route::get('/finder/list', [ManagerFinderController::class, 'list'])->name('Manager.Finder.list');
        Route::post('/finder/upload', [ManagerFinderController::class, 'upload'])->name('Manager.Finder.upload');
        Route::delete('/finder/delete', [ManagerFinderController::class, 'delete'])->name('Manager.Finder.delete');
        Route::post('/finder/rename', [ManagerFinderController::class, 'rename'])->name('Manager.Finder.rename');
        Route::post('/finder/folder', [ManagerFinderController::class, 'createFolder'])->name('Manager.Finder.createFolder');
        Route::post('/finder/move', [ManagerFinderController::class, 'move'])->name('Manager.Finder.move');

        Route::get('/home', [ManagerHomeController::class, 'index'])->name('Manager.Home.index');
        Route::post('/home/atualizar/dados', [ManagerHomeController::class, 'atualizarInfo'])->name('Manager.Home.atualizarInfo');

        Route::post('/slides/ordenar', [ManagerSlidesController::class, 'ordenar'])->name('Manager.Slides.ordenar');
        Route::post('/slides/visibilidade/{id}', [ManagerSlidesController::class, 'visibilidade'])->name('Manager.Slides.visibilidade');
        Route::post('/slides/excluir/{id}', [ManagerSlidesController::class, 'excluir'])->name('Manager.Slides.excluir');

        Route::get('/slides/adicionar/{tipo}', [ManagerSlidesController::class, 'adicionar'])->name('Manager.Slides.adicionar');
        Route::post('/slides/adicionar/{tipo}', [ManagerSlidesController::class, 'novo'])->name('Manager.Slides.novo');
        Route::get('/slides/editar/{id}', [ManagerSlidesController::class, 'editar'])->name('Manager.Slides.editar');
        Route::post('/slides/editar/{id}', [ManagerSlidesController::class, 'atualizar'])->name('Manager.Slides.atualizar');
        Route::get('/slides/baixar-video/{id}/{video}', [ManagerSlidesController::class, 'baixarVideo'])->name('Manager.Slides.baixarVideo');


        Route::post('/clientes/ordenar', [ManagerClientesController::class, 'ordenar'])->name('Manager.Clientes.ordenar');
        Route::post('/clientes/visibilidade/{id}', [ManagerClientesController::class, 'visibilidade'])->name('Manager.Clientes.visibilidade');
        Route::post('/clientes/excluir/{id}', [ManagerClientesController::class, 'excluir'])->name('Manager.Clientes.excluir');

        Route::get('/clientes/adicionar', [ManagerClientesController::class, 'adicionar'])->name('Manager.Clientes.adicionar');
        Route::post('/clientes/adicionar', [ManagerClientesController::class, 'novo'])->name('Manager.Clientes.novo');
        Route::get('/clientes/editar/{id}', [ManagerClientesController::class, 'editar'])->name('Manager.Clientes.editar');
        Route::post('/clientes/editar/{id}', [ManagerClientesController::class, 'atualizar'])->name('Manager.Clientes.atualizar');


        Route::get('/institucional', [ManagerInstitucionalController::class, 'index'])->name('Manager.Institucional.index');


        Route::post('/selos/ordenar', [ManagerSelosController::class, 'ordenar'])->name('Manager.Selos.ordenar');
        Route::post('/selos/visibilidade/{id}', [ManagerSelosController::class, 'visibilidade'])->name('Manager.Selos.visibilidade');
        Route::post('/selos/excluir/{id}', [ManagerSelosController::class, 'excluir'])->name('Manager.Selos.excluir');

        Route::get('/selos/adicionar', [ManagerSelosController::class, 'adicionar'])->name('Manager.Selos.adicionar');
        Route::post('/selos/adicionar', [ManagerSelosController::class, 'novo'])->name('Manager.Selos.novo');
        Route::get('/selos/editar/{id}', [ManagerSelosController::class, 'editar'])->name('Manager.Selos.editar');
        Route::post('/selos/editar/{id}', [ManagerSelosController::class, 'atualizar'])->name('Manager.Selos.atualizar');
        Route::get('/selos/baixar-video/{id}/', [ManagerSelosController::class, 'baixarVideo'])->name('Manager.Selos.baixarVideo');


        Route::post('/diferenciais/ordenar', [ManagerDiferenciaisController::class, 'ordenar'])->name('Manager.Diferenciais.ordenar');
        Route::post('/diferenciais/visibilidade/{id}', [ManagerDiferenciaisController::class, 'visibilidade'])->name('Manager.Diferenciais.visibilidade');
        Route::post('/diferenciais/excluir/{id}', [ManagerDiferenciaisController::class, 'excluir'])->name('Manager.Diferenciais.excluir');

        Route::get('/diferenciais/adicionar', [ManagerDiferenciaisController::class, 'adicionar'])->name('Manager.Diferenciais.adicionar');
        Route::post('/diferenciais/adicionar', [ManagerDiferenciaisController::class, 'novo'])->name('Manager.Diferenciais.novo');
        Route::get('/diferenciais/editar/{id}', [ManagerDiferenciaisController::class, 'editar'])->name('Manager.Diferenciais.editar');
        Route::post('/diferenciais/editar/{id}', [ManagerDiferenciaisController::class, 'atualizar'])->name('Manager.Diferenciais.atualizar');


        Route::get('/segmentos', [ManagerSegmentosController::class, 'index'])->name('Manager.Segmentos.index');

        Route::post('/segmentos/ordenar', [ManagerSegmentosController::class, 'ordenar'])->name('Manager.Segmentos.ordenar');
        Route::post('/segmentos/visibilidade/{id}', [ManagerSegmentosController::class, 'visibilidade'])->name('Manager.Segmentos.visibilidade');
        Route::post('/segmentos/excluir/{id}', [ManagerSegmentosController::class, 'excluir'])->name('Manager.Segmentos.excluir');

        Route::get('/segmentos/adicionar', [ManagerSegmentosController::class, 'adicionar'])->name('Manager.Segmentos.adicionar');
        Route::post('/segmentos/adicionar', [ManagerSegmentosController::class, 'novo'])->name('Manager.Segmentos.novo');
        Route::get('/segmentos/editar/{id}', [ManagerSegmentosController::class, 'editar'])->name('Manager.Segmentos.editar');
        Route::post('/segmentos/editar/{id}', [ManagerSegmentosController::class, 'atualizar'])->name('Manager.Segmentos.atualizar');


        Route::post('/produtos/categorias/ordenar', [ManagerProdutosCategoriasController::class, 'ordenar'])->name('Manager.Produtos.Categorias.ordenar');
        Route::post('/produtos/categorias/visibilidade/{id}', [ManagerProdutosCategoriasController::class, 'visibilidade'])->name('Manager.Produtos.Categorias.visibilidade');
        Route::post('/produtos/categorias/excluir/{id}', [ManagerProdutosCategoriasController::class, 'excluir'])->name('Manager.Produtos.Categorias.excluir');

        Route::get('/produtos/categorias/adicionar', [ManagerProdutosCategoriasController::class, 'adicionar'])->name('Manager.Produtos.Categorias.adicionar');
        Route::post('/produtos/categorias/adicionar', [ManagerProdutosCategoriasController::class, 'novo'])->name('Manager.Produtos.Categorias.novo');
        Route::get('/produtos/categorias/editar/{id}', [ManagerProdutosCategoriasController::class, 'editar'])->name('Manager.Produtos.Categorias.editar');
        Route::post('/produtos/categorias/editar/{id}', [ManagerProdutosCategoriasController::class, 'atualizar'])->name('Manager.Produtos.Categorias.atualizar');


        Route::get('/produtos', [ManagerProdutosController::class, 'index'])->name('Manager.Produtos.index');

        Route::post('/produtos/ordenar', [ManagerProdutosController::class, 'ordenar'])->name('Manager.Produtos.ordenar');
        Route::post('/produtos/visibilidade/{id}', [ManagerProdutosController::class, 'visibilidade'])->name('Manager.Produtos.visibilidade');
        Route::post('/produtos/excluir/{id}', [ManagerProdutosController::class, 'excluir'])->name('Manager.Produtos.excluir');

        Route::get('/produtos/adicionar', [ManagerProdutosController::class, 'adicionar'])->name('Manager.Produtos.adicionar');
        Route::post('/produtos/adicionar', [ManagerProdutosController::class, 'novo'])->name('Manager.Produtos.novo');
        Route::get('/produtos/editar/{id}', [ManagerProdutosController::class, 'editar'])->name('Manager.Produtos.editar');
        Route::post('/produtos/editar/{id}', [ManagerProdutosController::class, 'atualizar'])->name('Manager.Produtos.atualizar');

        Route::get('/produtos/imagens/{id}', [ManagerImagensProdutosController::class, 'index'])->name('Manager.Produtos.Imagens.index');

        Route::get('/produtos/imagens/adicionar/{id}', [ManagerImagensProdutosController::class, 'adicionar'])->name('Manager.Produtos.Imagens.adicionar');
        Route::post('/produtos/imagens/adicionar/{id}', [ManagerImagensProdutosController::class, 'novo'])->name('Manager.Produtos.Imagens.novo');
        Route::get('/produtos/imagens/editar/{id}', [ManagerImagensProdutosController::class, 'editar'])->name('Manager.Produtos.Imagens.editar');
        Route::post('/produtos/imagens/editar/{id}', [ManagerImagensProdutosController::class, 'atualizar'])->name('Manager.Produtos.Imagens.atualizar');

        Route::post('/produtos/imagens/ordenar/{id}', [ManagerImagensProdutosController::class, 'ordenar'])->name('Manager.Produtos.Imagens.ordenar');
        Route::post('/produtos/imagens/visibilidade/{id}', [ManagerImagensProdutosController::class, 'visibilidade'])->name('Manager.Produtos.Imagens.visibilidade');
        Route::post('/produtos/imagens/excluir/{id}', [ManagerImagensProdutosController::class, 'excluir'])->name('Manager.Produtos.Imagens.excluir');


        Route::get('/opcionais', [ManagerOpcionaisController::class, 'index'])->name('Manager.Opcionais.index');

        Route::get('/opcionais/adicionar/{id?}', [ManagerOpcionaisController::class, 'adicionar'])->name('Manager.Opcionais.adicionar');
        Route::post('/opcionais/adicionar/{id?}', [ManagerOpcionaisController::class, 'novo'])->name('Manager.Opcionais.novo');
        Route::get('/opcionais/editar/{id}', [ManagerOpcionaisController::class, 'editar'])->name('Manager.Opcionais.editar');
        Route::post('/opcionais/editar/{id}', [ManagerOpcionaisController::class, 'atualizar'])->name('Manager.Opcionais.atualizar');

        Route::post('/opcionais/ordenar/{id?}', [ManagerOpcionaisController::class, 'ordenar'])->name('Manager.Opcionais.ordenar');
        Route::post('/opcionais/visibilidade/{id}', [ManagerOpcionaisController::class, 'visibilidade'])->name('Manager.Opcionais.visibilidade');
        Route::post('/opcionais/excluir/{id}', [ManagerOpcionaisController::class, 'excluir'])->name('Manager.Opcionais.excluir');


        Route::get('/opcionais/modelos/{id}', [ManagerOpcionaisModelosController::class, 'index'])->name('Manager.Opcionais.Modelos.index');

        Route::get('/opcionais/modelos/adicionar/{id}', [ManagerOpcionaisModelosController::class, 'adicionar'])->name('Manager.Opcionais.Modelos.adicionar');
        Route::post('/opcionais/modelos/adicionar/{id}', [ManagerOpcionaisModelosController::class, 'novo'])->name('Manager.Opcionais.Modelos.novo');
        Route::get('/opcionais/modelos/editar/{id}', [ManagerOpcionaisModelosController::class, 'editar'])->name('Manager.Opcionais.Modelos.editar');
        Route::post('/opcionais/modelos/editar/{id}', [ManagerOpcionaisModelosController::class, 'atualizar'])->name('Manager.Opcionais.Modelos.atualizar');

        Route::post('/opcionais/modelos/ordenar/{id}', [ManagerOpcionaisModelosController::class, 'ordenar'])->name('Manager.Opcionais.Modelos.ordenar');
        Route::post('/opcionais/modelos/visibilidade/{id}', [ManagerOpcionaisModelosController::class, 'visibilidade'])->name('Manager.Opcionais.Modelos.visibilidade');
        Route::post('/opcionais/modelos/excluir/{id}', [ManagerOpcionaisModelosController::class, 'excluir'])->name('Manager.Opcionais.Modelos.excluir');


        Route::get('/opcionais/categorias', [ManagerOpcionaisCategoriasController::class, 'index'])->name('Manager.Opcionais.Categorias.index');

        Route::get('/opcionais/categorias/adicionar', [ManagerOpcionaisCategoriasController::class, 'adicionar'])->name('Manager.Opcionais.Categorias.adicionar');
        Route::post('/opcionais/categorias/adicionar', [ManagerOpcionaisCategoriasController::class, 'novo'])->name('Manager.Opcionais.Categorias.novo');
        Route::get('/opcionais/categorias/editar/{id}', [ManagerOpcionaisCategoriasController::class, 'editar'])->name('Manager.Opcionais.Categorias.editar');
        Route::post('/opcionais/categorias/editar/{id}', [ManagerOpcionaisCategoriasController::class, 'atualizar'])->name('Manager.Opcionais.Categorias.atualizar');

        Route::post('/opcionais/categorias/ordenar', [ManagerOpcionaisCategoriasController::class, 'ordenar'])->name('Manager.Opcionais.Categorias.ordenar');
        Route::post('/opcionais/categorias/visibilidade/{id}', [ManagerOpcionaisCategoriasController::class, 'visibilidade'])->name('Manager.Opcionais.Categorias.visibilidade');
        Route::post('/opcionais/categorias/excluir/{id}', [ManagerOpcionaisCategoriasController::class, 'excluir'])->name('Manager.Opcionais.Categorias.excluir');


        Route::get('/downloads', [ManagerDownloadsController::class, 'index'])->name('Manager.Downloads.index');

        Route::post('/downloads/ordenar', [ManagerDownloadsController::class, 'ordenar'])->name('Manager.Downloads.ordenar');
        Route::post('/downloads/visibilidade/{id}', [ManagerDownloadsController::class, 'visibilidade'])->name('Manager.Downloads.visibilidade');
        Route::post('/downloads/excluir/{id}', [ManagerDownloadsController::class, 'excluir'])->name('Manager.Downloads.excluir');

        Route::get('/downloads/adicionar/{tipo}', [ManagerDownloadsController::class, 'adicionar'])->name('Manager.Downloads.adicionar');
        Route::post('/downloads/adicionar/{tipo}', [ManagerDownloadsController::class, 'novo'])->name('Manager.Downloads.novo');
        Route::get('/downloads/editar/{id}', [ManagerDownloadsController::class, 'editar'])->name('Manager.Downloads.editar');
        Route::post('/downloads/editar/{id}', [ManagerDownloadsController::class, 'atualizar'])->name('Manager.Downloads.atualizar');
        Route::get('/downloads/baixar-arquivo/{id}/', [ManagerDownloadsController::class, 'baixarArquivo'])->name('Manager.Downloads.baixarArquivo');


        Route::get('/contato', [ManagerContatoController::class, 'index'])->name('Manager.Contato.index');

        Route::get('/contato/visualizar/{id}', [ManagerContatoController::class, 'visualizar'])->name('Manager.Contato.visualizar');
        Route::post('/contato/excluir/{id}', [ManagerContatoController::class, 'excluir'])->name('Manager.Contato.excluir');

        Route::get('/newsletter/visualizar/{id}', [ManagerNewsletterController::class, 'visualizar'])->name('Manager.Newsletter.visualizar');
        Route::post('/newsletter/excluir/{id}', [ManagerNewsletterController::class, 'excluir'])->name('Manager.Newsletter.excluir');

        Route::get('/parcerias/visualizar/{id}', [ManagerParceriasController::class, 'visualizar'])->name('Manager.Parcerias.visualizar');
        Route::post('/parcerias/excluir/{id}', [ManagerParceriasController::class, 'excluir'])->name('Manager.Parcerias.excluir');


        Route::post('/departamentos/ordenar', [ManagerDepartamentosController::class, 'ordenar'])->name('Manager.Departamentos.ordenar');
        Route::post('/departamentos/visibilidade/{id}', [ManagerDepartamentosController::class, 'visibilidade'])->name('Manager.Departamentos.visibilidade');
        Route::post('/departamentos/excluir/{id}', [ManagerDepartamentosController::class, 'excluir'])->name('Manager.Departamentos.excluir');

        Route::get('/departamentos/adicionar', [ManagerDepartamentosController::class, 'adicionar'])->name('Manager.Departamentos.adicionar');
        Route::post('/departamentos/adicionar', [ManagerDepartamentosController::class, 'novo'])->name('Manager.Departamentos.novo');
        Route::get('/departamentos/editar/{id}', [ManagerDepartamentosController::class, 'editar'])->name('Manager.Departamentos.editar');
        Route::post('/departamentos/editar/{id}', [ManagerDepartamentosController::class, 'atualizar'])->name('Manager.Departamentos.atualizar');


        Route::get('/news', [ManagerNewsController::class, 'index'])->name('Manager.News.index');

        Route::post('/posts/ordenar', [ManagerPostsController::class, 'ordenar'])->name('Manager.Posts.ordenar');
        Route::post('/posts/visibilidade/{id}', [ManagerPostsController::class, 'visibilidade'])->name('Manager.Posts.visibilidade');
        Route::post('/posts/excluir/{id}', [ManagerPostsController::class, 'excluir'])->name('Manager.Posts.excluir');

        Route::get('/posts/adicionar', [ManagerPostsController::class, 'adicionar'])->name('Manager.Posts.adicionar');
        Route::post('/posts/adicionar', [ManagerPostsController::class, 'novo'])->name('Manager.Posts.novo');
        Route::get('/posts/editar/{id}', [ManagerPostsController::class, 'editar'])->name('Manager.Posts.editar');
        Route::post('/posts/editar/{id}', [ManagerPostsController::class, 'atualizar'])->name('Manager.Posts.atualizar');


        Route::post('/posts/categorias/ordenar', [ManagerPostsCategoriasController::class, 'ordenar'])->name('Manager.Posts.Categorias.ordenar');
        Route::post('/posts/categorias/visibilidade/{id}', [ManagerPostsCategoriasController::class, 'visibilidade'])->name('Manager.Posts.Categorias.visibilidade');
        Route::post('/posts/categorias/excluir/{id}', [ManagerPostsCategoriasController::class, 'excluir'])->name('Manager.Posts.Categorias.excluir');

        Route::get('/posts/categorias/adicionar', [ManagerPostsCategoriasController::class, 'adicionar'])->name('Manager.Posts.Categorias.adicionar');
        Route::post('/posts/categorias/adicionar', [ManagerPostsCategoriasController::class, 'novo'])->name('Manager.Posts.Categorias.novo');
        Route::get('/posts/categorias/editar/{id}', [ManagerPostsCategoriasController::class, 'editar'])->name('Manager.Posts.Categorias.editar');
        Route::post('/posts/categorias/editar/{id}', [ManagerPostsCategoriasController::class, 'atualizar'])->name('Manager.Posts.Categorias.atualizar');


        Route::get('/politicas/privacidade', [ManagerPoliticasController::class, 'privacidade'])->name('Manager.Politicas.privacidade');
        Route::get('/politicas/cookies', [ManagerPoliticasController::class, 'cookies'])->name('Manager.Politicas.cookies');
        Route::get('/politicas/canal-denuncia', [ManagerPoliticasController::class, 'canalDenuncia'])->name('Manager.Politicas.canalDenuncia');


        Route::get('/trabalhe-conosco', [ManagerTrabalheConoscoController::class, 'index'])->name('Manager.TrabalheConosco.index');
    });
});
