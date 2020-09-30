<?php
namespace App\Http\IO;

use Framework\AbstractInterface\Http\Server\IoContextInterface;

interface IOContextFactoryInterface
{
    /**
     * Create a singleton instance of IOContextInterface.
     * @return IoContextInterface
     */
    public static function get(): IoContextInterface;
}