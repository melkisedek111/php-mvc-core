<?php

namespace thecore\phpmvc\middlewares;

abstract class BaseMiddleware {
    abstract public function execute();
}