<?php

namespace routes;

use test\modules as test;
use routes\security;
use model\database;

class route
{
    public static string $main = "https://leonesgrupoinmobiliario.com/";
    private string $name;
    private string $type;
    private bool $protected;
    public function __construct($name, $protected = false, $type = "struct")
    {
        $this->name = $name;
        $this->protected = $protected;
        $this->type = $type;
    }
    public function get_name(): string
    {
        return $this->name;
    }
    public function get_protected():bool{
        return $this->protected;
    }
    public function get_type(): string
    {
        return $this->type;
    }
    public function landing(): void
    {
        echo "pagina " . test::test() . " test render " . $this->name;
    }
}
