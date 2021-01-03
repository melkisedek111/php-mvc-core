<?php

namespace thecore\phpmvc;

use thecore\phpmvc\database\DbModel;


abstract class UserModel extends DbModel {
    abstract public function getDisplayname(): string;
}