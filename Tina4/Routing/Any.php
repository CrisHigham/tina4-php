<?php

namespace Tina4;
/**
 * Tina4 - This is not a 4ramework.
 * Copy-right 2007 - current Tina4 (Andre van Zuydam)
 * License: MIT https://opensource.org/licenses/MIT
 *
 * Class Any Sets the $method variable in Class Route to ANY using the definition in Class Tina4Php
 * The Any route intercepts any type of call that can be made to the web server e.g. Any::add()
 * @package Tina4
 */
class Any extends Route
{
    /**
     * @var string Sets $method to ANY
     */
    public static string $method = TINA4_ANY;
}
