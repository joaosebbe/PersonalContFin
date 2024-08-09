<?php

namespace App\Console\Commands;

use App\Http\Controllers\NotificacaoController;
use Illuminate\Console\Command;

class NotificacaoWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notificacao-whatsapp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notifica = NotificacaoController::NotificaWhatsapp();
        $this->info('Comando executado com sucesso!\n Resposta: ' . $notifica);
    }
}
