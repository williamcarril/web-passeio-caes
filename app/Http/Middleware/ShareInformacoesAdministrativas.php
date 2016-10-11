<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;
use App\Models\Eloquent\Cancelamento;
use App\Models\Eloquent\Passeio;
use App\Models\Eloquent\Agendamento;
class ShareInformacoesAdministrativas {

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
    public function __construct(ViewFactory $view) {
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $this->view->share([
                "cancelamentosNaoVerificados" => Cancelamento::pendente()->count(),
                "passeiosPendentesSemPasseadores" => Passeio::pendente()->agendamentoConfirmado()->semPasseador()->count(),
                "agendamentosPendentes" => Agendamento::pendenteFuncionario()->count()
        ]);
        return $next($request);
    }

}
