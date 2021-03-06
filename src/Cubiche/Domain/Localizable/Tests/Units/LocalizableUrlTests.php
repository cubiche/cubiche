<?php

/**
 * This file is part of the Cubiche/Localizable component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Localizable\Tests\Units;

use Cubiche\Domain\Locale\LocaleCode;
use Cubiche\Domain\Localizable\LocalizableUrl;
use Cubiche\Domain\Localizable\LocalizableValueMode;
use Cubiche\Domain\System\StringLiteral;
use Cubiche\Domain\Web\Host;
use Cubiche\Domain\Web\Path;
use Cubiche\Domain\Web\Port;
use Cubiche\Domain\Web\Url;

/**
 * LocalizableUrlTests class.
 *
 * Generated by TestGenerator on 2018-01-15 at 13:40:07.
 */
class LocalizableUrlTests extends TestCase
{
    /**
     * Test FromNative method.
     */
    public function testFromNative()
    {
        $this
            ->given($localizable = LocalizableUrl::fromNative('http://foo.com'))
            ->when($constructedLocalizable = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->and($constructedLocalizable->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
            ->then()
                ->object($constructedLocalizable)
                    ->isEqualTo($localizable)
        ;
    }

    /**
     * Test AddNative method.
     */
    public function testAddNative()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::fromNative('en_US')))
                    ->isTrue()
                ->boolean($localizableUrl->value(LocaleCode::fromNative('en_US'))->equals(new Url('http://foo.com')))
                    ->isTrue()
        ;
    }

    /**
     * Test Add method.
     */
    public function testAdd()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->when($localizableUrl->add(new Url('http://foo.com'), LocaleCode::fromNative('en_US')))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::fromNative('en_US')))
                    ->isTrue()
                ->boolean($localizableUrl->value(LocaleCode::fromNative('en_US'))->equals(new Url('http://foo.com')))
                    ->isTrue()
        ;
    }

    /**
     * Test ToNative method.
     */
    public function testToNative()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
            ->then()
                ->string($localizableUrl->toNative())
                    ->isEqualTo('http://foo.com')
        ;
    }

    /**
     * Test Host method.
     */
    public function testHost()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($host = Host::fromNative('foo.com'))
            ->then()
                ->variable($localizableUrl->host())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->host())
                        ->isEqualTo($host)
        ;
    }

    /**
     * Test FragmentId method.
     */
    public function testFragmentId()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($fragment = new StringLiteral('#fragmentidentifier'))
            ->then()
                ->variable($localizableUrl->fragmentId())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->fragmentId())
                        ->isEqualTo($fragment)
        ;
    }

    /**
     * Test Password method.
     */
    public function testPassword()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($password = new StringLiteral('pass'))
            ->then()
                ->variable($localizableUrl->password())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->password())
                        ->isEqualTo($password)
        ;
    }

    /**
     * Test Path method.
     */
    public function testPath()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($path = new Path('/bar'))
            ->then()
                ->variable($localizableUrl->path())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->path())
                        ->isEqualTo($path)
        ;
    }

    /**
     * Test Port method.
     */
    public function testPort()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($port = new Port(80))
            ->then()
                ->variable($localizableUrl->port())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->port())
                        ->isEqualTo($port)
        ;
    }

    /**
     * Test QueryString method.
     */
    public function testQueryString()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($queryString = new StringLiteral('?querystring'))
            ->then()
                ->variable($localizableUrl->queryString())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->queryString())
                        ->isEqualTo($queryString)
        ;
    }

    /**
     * Test Scheme method.
     */
    public function testScheme()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($scheme = new StringLiteral('http'))
            ->then()
                ->variable($localizableUrl->scheme())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->scheme())
                        ->isEqualTo($scheme)
        ;
    }

    /**
     * Test User method.
     */
    public function testUser()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT())
            )
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::fromNative('en_US')
                )
            )
            ->and($user = new StringLiteral('user'))
            ->then()
                ->variable($localizableUrl->user())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::fromNative('en_US')))
                ->then()
                    ->object($localizableUrl->user())
                        ->isEqualTo($user)
        ;
    }

    /**
     * Test Equals method.
     */
    public function testEquals()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')),
                $localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US'))
            )
            ->when(
                $localizableUrl1 = new LocalizableUrl(LocaleCode::fromNative('en_US')),
                $localizableUrl1->addNative('http://foo.com', LocaleCode::fromNative('en_US'))
            )
            ->and(
                $localizableUrl2 = new LocalizableUrl(LocaleCode::fromNative('en_US')),
                $localizableUrl2->addNative('http://bar.com', LocaleCode::fromNative('en_US'))
            )
            ->and(
                $localizableUrl3 = new LocalizableUrl(LocaleCode::fromNative('es_ES'), LocalizableValueMode::STRICT()),
                $localizableUrl3->addNative('http://foo.com', LocaleCode::fromNative('en_US'))
            )
            ->then()
                ->boolean($localizableUrl->equals($localizableUrl1))
                    ->isTrue()
                ->boolean($localizableUrl->equals($localizableUrl2))
                    ->isFalse()
                ->boolean($localizableUrl->equals($localizableUrl3))
                    ->isFalse()
        ;
    }

    /**
     * Test __toString method.
     */
    public function testToString()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->then()
                ->string($localizableUrl->__toString())
                    ->isEqualTo('')
                ->and()
                ->when($localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
                ->then()
                    ->string($localizableUrl->__toString())
                        ->isEqualTo('http://foo.com')
        ;
    }

    /**
     * Test Mode method.
     */
    public function testMode()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US'), LocalizableValueMode::STRICT())
            )
            ->then()
                ->boolean($localizableUrl->mode()->equals(LocalizableValueMode::STRICT()))
                    ->isTrue()
        ;
    }

    /**
     * Test Mode method.
     */
    public function testStrictMode()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US'), LocalizableValueMode::STRICT())
            )
            ->when($localizableUrl->addNative('http://foo.es', LocaleCode::fromNative('es_ES')))
            ->then()
                ->variable($localizableUrl->toNative())
                    ->isNull()
        ;
    }

    /**
     * Test Mode method.
     */
    public function testAnyMode()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US'), LocalizableValueMode::ANY())
            )
            ->when($localizableUrl->addNative('http://foo.es', LocaleCode::fromNative('es_ES')))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::fromNative('es_ES')))
                   ->isTrue()
                ->string($localizableUrl->toNative())
                    ->isEqualTo('http://foo.es')
        ;

        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('fr_FR'), LocalizableValueMode::ANY()))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
            ->and($localizableUrl->addNative('http://foo.es', LocaleCode::fromNative('es_ES')))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::fromNative('en_US')))
                    ->isTrue()
                ->string($localizableUrl->toNative())
                    ->isEqualTo('http://foo.com')
        ;

        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('fr_FR'), LocalizableValueMode::ANY()))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::fromNative('fr_FR')))
                    ->isTrue()
        ;
    }

    /**
     * Test SetMode method.
     */
    public function testSetMode()
    {
        $this
            ->given(
                $localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US'), LocalizableValueMode::STRICT())
            )
            ->when($localizableUrl->setMode(LocalizableValueMode::ANY()))
            ->then()
                ->boolean($localizableUrl->mode()->equals(LocalizableValueMode::ANY()))
                    ->isTrue()
        ;
    }

    /**
     * Test Locale method.
     */
    public function testLocale()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::fromNative('en_US')))
                    ->isTrue()
        ;
    }

    /**
     * Test SetLocale method.
     */
    public function testSetLocale()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->when($localizableUrl->setLocale(LocaleCode::fromNative('es_ES')))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::fromNative('es_ES')))
                    ->isTrue()
        ;
    }

    /**
     * Test Remove method.
     */
    public function testRemove()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::fromNative('en_US')))
                    ->isTrue()
                ->and()
                ->when($localizableUrl->remove(LocaleCode::fromNative('en_US')))
                ->then()
                    ->boolean($localizableUrl->has(LocaleCode::fromNative('en_US')))
                        ->isFalse()
        ;
    }

    /**
     * Test Has method.
     */
    public function testHas()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::fromNative('en_US')))
                    ->isFalse()
                ->and()
                ->when($localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
                ->then()
                    ->boolean($localizableUrl->has(LocaleCode::fromNative('en_US')))
                        ->isTrue()
        ;
    }

    /**
     * Test Translate method.
     */
    public function testTranslate()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::fromNative('en_US')))
            ->then()
                ->variable($localizableUrl->translate(LocaleCode::fromNative('es_ES')))
                    ->isNull()
                ->array($localizableUrl->translations())
                    ->hasSize(1)
                ->and()
                ->when($localizableUrl->addNative('http://foo.es', LocaleCode::fromNative('es_ES')))
                ->then()
                    ->array($localizableUrl->translations())
                        ->hasSize(2)
                    ->string($localizableUrl->translate(LocaleCode::fromNative('en_US')))
                        ->isEqualTo('http://foo.com')
                    ->string($localizableUrl->translate(LocaleCode::fromNative('es_ES')))
                       ->isEqualTo('http://foo.es')
        ;
    }

    /**
     * Test fromArray method.
     */
    public function testFromArray()
    {
        $this
            ->given(
                $localizableString = LocalizableUrl::fromArray(
                    array('en_US' => 'http://www.google.com', 'es_ES' => 'http://www.google.es'),
                    'en_US'
                )
            )
            ->then()
                ->boolean($localizableString->locale()->equals(LocaleCode::fromNative('en_US')))
                    ->isTrue()
                ->string($localizableString->translate(LocaleCode::fromNative('en_US')))
                    ->isEqualTo('http://www.google.com')
                ->string($localizableString->translate(LocaleCode::fromNative('es_ES')))
                   ->isEqualTo('http://www.google.es')
        ;
    }

    /**
     * Test toArray method.
     */
    public function testToArray()
    {
        $this
            ->given($localizableString = new LocalizableUrl(LocaleCode::fromNative('en_US')))
            ->when($localizableString->addNative('http://www.google.com', LocaleCode::fromNative('en_US')))
            ->and($localizableString->addNative('http://www.google.es', LocaleCode::fromNative('es_ES')))
            ->then()
                ->array($localizableString->toArray())
                    ->isEqualTo(array('en_US' => 'http://www.google.com', 'es_ES' => 'http://www.google.es'))
        ;
    }
}
