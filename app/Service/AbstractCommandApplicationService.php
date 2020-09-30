<?php
namespace App\Service;

use RuntimeException;

/**
 * HttpServeApplication service abstract.
 * Class AbstractCommandApplicationService.backup
 * @package App\Service
 */
abstract class AbstractCommandApplicationService
{
    /**
     * Service logic.If has error must throw ApplicationServeException instance.
     * If has return value, please put into injected data transformer object.
     * Customer will get returned value through data transformer.
     * @throws ApplicationServeException
     */
    final public function execute(AbstractDTO $input, AbstractDTOAssembler $output_assembler): void
    {
        if (method_exists($this, 'handle')) {
            $this->handle($input, $output_assembler);
        } else {
            throw new RuntimeException("Please implements " . AbstractQueryApplicationService::class . '::handle($input_dto, $output_assembler)');
        }
    }
}