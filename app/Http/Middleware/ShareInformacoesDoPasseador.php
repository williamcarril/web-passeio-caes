<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
class ShareInformacoesDoPasseador {

    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new error binder instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @return void
     */
    public function __construct(ViewFactory $view, AuthFactory $auth) {
        $this->view = $view;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $passeador = $this->auth->guard("walker")->user();
        $this->view->share([
            "passeiosPendentes" => !is_null($passeador) ? $passeador->passeios()->agendamentoConfirmado()->pendente()->count() : 0
        ]);
        return $next($request);
    }

}
