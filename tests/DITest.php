<?php
namespace Nora\DI;

/**
 * モジュールテスト
 */
use PHPUnit\Framework\TestCase;

class Mock
{
    public $words;

    public function __construct(Words $words, $cnt = 0)
    {
        $this->words = $words;
    }
}

class Words
{
    public $lang;

    public function __invoke($lang = "ja")
    {
        return ([
            "ja" => "はい",
            "en" => "yes"
        ])[$lang];
    }

    public function setLang(Lang $lang)
    {
        $this->lang = $lang;
    }
}

class Lang
{
}

class ModuleTest extends TestCase
{
    public function testDI()
    {
        $injector = new Injector(new class extends Module {
            public function configure() {
                $this->bind(Mock::class)->to(Mock::class);
                $this->bind(Words::class)->toConstructor(
                    Words::class,
                    Name::ANY,
                    (new InjectionPoints)->addMethod('setLang')
                );
                $this->bind(Lang::class)->to(Lang::class);
            }
        });
        //$instance = $injector->getInstance(SampleInterface::class);
        $this->assertEquals(
            'はい',
            ($injector->getInstance(Mock::class)->words)("ja")
        );
        $this->assertInstanceOf(
            Lang::class,
            $injector->getInstance(Mock::class)->words->lang
        );
    }
}
