<?php
namespace App\Service\Command\ServiceImpl;

use App\Service\AbstractCommandApplicationService;
use App\Service\AbstractDTO;
use App\Service\AbstractDTOAssembler;
use Infrastructure\Persistence\Database\Mysql\Transaction\TransactionSessionInterface;

class TransactionCommandService extends AbstractCommandApplicationService
{
    protected $session;

    protected $executor;

    public function __construct(TransactionSessionInterface $session, AbstractCommandApplicationService $executor)
    {
        $this->session = $session;
        $this->executor = $executor;
    }

    protected function handle(AbstractDTO $input, AbstractDTOAssembler $output_assembler): void
    {
        $this->session->atomicallyExecute(function () use ($input, $output_assembler) {
           $this->executor->execute($input, $output_assembler);
        });
    }
}