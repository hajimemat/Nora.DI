<?php
declare(strict_types=1);

namespace Nora\DI;

/**
 */
class Name
{
    const ANY = '';

    private $name;
    private $names;

    public function __construct(string $name = null)
    {
        if ($name !== null) {
            $this->setName($name);
        }
    }

    public function __invoke(\ReflectionParameter $parameter) : string
    {
        if ($this->name) {
            return $this->name;
        }
    }

    public function setName(string $name)
    {
        if ($name === self::ANY || preg_match('/^\w+$/', $name)) {
            $this->name = $name;
            return;
        }
        $this->parseName($name);
    }

    private function parseName(string $name)
    {
        $key = explode(',', $name);
        foreach ($key as $v) {
            $exploded = explode('=', $v);
            if (isset($exploded[1])) {
                [$kk, $vv] = $exploded;
                if (isset($kk[0]) && $kk[0] === '$') {
                    $kk = substr($kk, 1);
                }
                $this->names[trim($kk)] = trim($value);
            }
        }
    }

}
