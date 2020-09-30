<?php
use Infrastructure\Shared\Config\Config;

return function () {
    date_default_timezone_set(Config::get('base.date_default_time_zone'));
};