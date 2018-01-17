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

use Cubiche\Domain\Localizable\LocaleCode;
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
            ->when($constructedLocalizable = new LocalizableUrl(LocaleCode::EN()))
            ->and($constructedLocalizable->addNative('http://foo.com', LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::EN()))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::EN()))
                    ->isTrue()
                ->boolean($localizableUrl->value(LocaleCode::EN())->equals(new Url('http://foo.com')))
                    ->isTrue()
        ;
    }

    /**
     * Test Add method.
     */
    public function testAdd()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->when($localizableUrl->add(new Url('http://foo.com'), LocaleCode::EN()))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::EN()))
                    ->isTrue()
                ->boolean($localizableUrl->value(LocaleCode::EN())->equals(new Url('http://foo.com')))
                    ->isTrue()
        ;
    }

    /**
     * Test ToNative method.
     */
    public function testToNative()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($host = Host::fromNative('foo.com'))
            ->then()
                ->variable($localizableUrl->host())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($fragment = new StringLiteral('#fragmentidentifier'))
            ->then()
                ->variable($localizableUrl->fragmentId())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($password = new StringLiteral('pass'))
            ->then()
                ->variable($localizableUrl->password())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($path = new Path('/bar'))
            ->then()
                ->variable($localizableUrl->path())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($port = new Port(80))
            ->then()
                ->variable($localizableUrl->port())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($queryString = new StringLiteral('?querystring'))
            ->then()
                ->variable($localizableUrl->queryString())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($scheme = new StringLiteral('http'))
            ->then()
                ->variable($localizableUrl->scheme())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()))
            ->when(
                $localizableUrl->addNative(
                    'http://user:pass@foo.com:80/bar?querystring#fragmentidentifier',
                    LocaleCode::EN()
                )
            )
            ->and($user = new StringLiteral('user'))
            ->then()
                ->variable($localizableUrl->user())
                    ->isNull()
                ->and()
                ->when($localizableUrl->setLocale(LocaleCode::EN()))
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
                $localizableUrl = new LocalizableUrl(LocaleCode::EN()),
                $localizableUrl->addNative('http://foo.com', LocaleCode::EN())
            )
            ->when(
                $localizableUrl1 = new LocalizableUrl(LocaleCode::EN()),
                $localizableUrl1->addNative('http://foo.com', LocaleCode::EN())
            )
            ->and(
                $localizableUrl2 = new LocalizableUrl(LocaleCode::EN()),
                $localizableUrl2->addNative('http://bar.com', LocaleCode::EN())
            )
            ->and(
                $localizableUrl3 = new LocalizableUrl(LocaleCode::ES(), LocalizableValueMode::STRICT()),
                $localizableUrl3->addNative('http://foo.com', LocaleCode::EN())
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->then()
                ->string($localizableUrl->__toString())
                    ->isEqualTo('')
                ->and()
                ->when($localizableUrl->addNative('http://foo.com', LocaleCode::EN()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN(), LocalizableValueMode::STRICT()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN(), LocalizableValueMode::STRICT()))
            ->when($localizableUrl->addNative('http://foo.es', LocaleCode::ES()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN(), LocalizableValueMode::ANY()))
            ->when($localizableUrl->addNative('http://foo.es', LocaleCode::ES()))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::ES()))
                   ->isTrue()
                ->string($localizableUrl->toNative())
                    ->isEqualTo('http://foo.es')
        ;

        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::FR(), LocalizableValueMode::ANY()))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::EN()))
            ->and($localizableUrl->addNative('http://foo.es', LocaleCode::ES()))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::EN()))
                    ->isTrue()
                ->string($localizableUrl->toNative())
                    ->isEqualTo('http://foo.com')
        ;

        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::FR(), LocalizableValueMode::ANY()))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::FR()))
                    ->isTrue()
        ;
    }

    /**
     * Test SetMode method.
     */
    public function testSetMode()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN(), LocalizableValueMode::STRICT()))
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
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::EN()))
                    ->isTrue()
        ;
    }

    /**
     * Test SetLocale method.
     */
    public function testSetLocale()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->when($localizableUrl->setLocale(LocaleCode::ES()))
            ->then()
                ->boolean($localizableUrl->locale()->equals(LocaleCode::ES()))
                    ->isTrue()
        ;
    }

    /**
     * Test Remove method.
     */
    public function testRemove()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::EN()))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::EN()))
                    ->isTrue()
                ->and()
                ->when($localizableUrl->remove(LocaleCode::EN()))
                ->then()
                    ->boolean($localizableUrl->has(LocaleCode::EN()))
                        ->isFalse()
        ;
    }

    /**
     * Test Has method.
     */
    public function testHas()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->then()
                ->boolean($localizableUrl->has(LocaleCode::EN()))
                    ->isFalse()
                ->and()
                ->when($localizableUrl->addNative('http://foo.com', LocaleCode::EN()))
                ->then()
                    ->boolean($localizableUrl->has(LocaleCode::EN()))
                        ->isTrue()
        ;
    }

    /**
     * Test Translate method.
     */
    public function testTranslate()
    {
        $this
            ->given($localizableUrl = new LocalizableUrl(LocaleCode::EN()))
            ->when($localizableUrl->addNative('http://foo.com', LocaleCode::EN()))
            ->then()
                ->variable($localizableUrl->translate(LocaleCode::ES()))
                    ->isNull()
                ->array($localizableUrl->translations())
                    ->hasSize(1)
                ->and()
                ->when($localizableUrl->addNative('http://foo.es', LocaleCode::ES()))
                ->then()
                    ->array($localizableUrl->translations())
                        ->hasSize(2)
                    ->string($localizableUrl->translate(LocaleCode::EN()))
                        ->isEqualTo('http://foo.com')
                    ->string($localizableUrl->translate(LocaleCode::ES()))
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
                $localizableString = LocalizableString::fromArray(
                    array('en' => 'http://www.google.com', 'es' => 'http://www.google.es'),
                    'en'
                )
            )
            ->then()
                ->boolean($localizableString->locale()->equals(LocaleCode::EN()))
                    ->isTrue()
                ->string($localizableString->translate(LocaleCode::EN()))
                    ->isEqualTo('http://www.google.com')
                ->string($localizableString->translate(LocaleCode::ES()))
                   ->isEqualTo('http://www.google.es')
        ;
    }

    /**
     * Test toArray method.
     */
    public function testToArray()
    {
        $this
            ->given($localizableString = new LocalizableString(LocaleCode::EN()))
            ->when($localizableString->addNative('http://www.google.com', LocaleCode::EN()))
            ->and($localizableString->addNative('http://www.google.es', LocaleCode::ES()))
            ->then()
                ->array($localizableString->toArray())
                    ->isEqualTo(array('en' => 'http://www.google.com', 'es' => 'http://www.google.es'))
        ;
    }
}
