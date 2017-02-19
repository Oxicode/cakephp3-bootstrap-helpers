<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\NavbarHelper;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class NavbarHelperTest extends TestCase {

    /**
     * Instance of the NavbarHelper.
     *
     * @var NavbarHelper
     */
    public $navbar;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html'
        ]);
        $view->loadHelper('Form', [
            'className' => 'Bootstrap.Form'
        ]);
        $this->navbar = new NavbarHelper($view);
    }

    public function testCreate() {
        // Test default:
        $result = $this->navbar->create(null);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded navbar-toggleable-md'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'navbar-toggler navbar-toggler-right',
                'data-toggle' => 'collapse',
                'data-target' => '#navbarSupportedContent',
                'aria-controls' => 'navbarSupportedContent',
                'aria-expanded' => 'false',
                'aria-label' => __('Toggle navigation')
            ]],
            ['span' => ['class' => 'navbar-toggle-icon']], '/span',
            '/button',
            ['div' => [
                'class' => 'collapse navbar-collapse',
                'id' => 'navbarSupportedContent'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test non responsive:
        $result = $this->navbar->create(null, ['responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test brand and non responsive:
        $result = $this->navbar->create('Brandname', ['responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded'
            ]],
            ['a' => [
                'class' => 'navbar-brand',
                'href' => '/',
            ]], 'Brandname', '/a'
        ];
        $this->assertHtml($expected, $result);

        // Test brand and responsive:
        $result = $this->navbar->create('Brandname');
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded navbar-toggleable-md'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'navbar-toggler navbar-toggler-right',
                'data-toggle' => 'collapse',
                'data-target' => '#navbarSupportedContent',
                'aria-controls' => 'navbarSupportedContent',
                'aria-expanded' => 'false',
                'aria-label' => __('Toggle navigation')
            ]],
            ['span' => ['class' => 'navbar-toggle-icon']], '/span',
            '/button',
            ['a' => [
                'class' => 'navbar-brand',
                'href' => '/',
            ]], 'Brandname', '/a',
            ['div' => [
                'class' => 'collapse navbar-collapse',
                'id' => 'navbarSupportedContent'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test container
        $result = $this->navbar->create(null, ['container' => true, 'responsive' => false]);
        $expected = [
            ['div' => [
                'class' => 'container'
            ]],
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test inverted
        $result = $this->navbar->create(null, ['inverse' => true, 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-inverse bg-inverse'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test sticky
        $result = $this->navbar->create(null, ['sticky' => true, 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded sticky-top'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test fixed top
        $result = $this->navbar->create(null, ['fixed' => 'top', 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded fixed-top'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test fixed bottom
        $result = $this->navbar->create(null, ['fixed' => 'bottom', 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-faded fixed-bottom'
            ]]
        ];
        $this->assertHtml($expected, $result);
    }

    public function testEnd() {
        // Test responsive without container
        $this->navbar->create(null);
        $result = $this->navbar->end();
        $expected = ['/div', '/nav'];
        $this->assertHtml($expected, $result);

        // Test non-responsive without container
        $this->navbar->create(null, ['responsive' => false]);
        $result = $this->navbar->end();
        $expected = ['/nav'];
        $this->assertHtml($expected, $result);

        // Test responsive with container
        $this->navbar->create(null, ['container' => true]);
        $result = $this->navbar->end();
        $expected = ['/div', '/nav', '/div'];
        $this->assertHtml($expected, $result);

        // Test non responsive with container
        $this->navbar->create(null, ['responsive' => false, 'container' => true]);
        $result = $this->navbar->end();
        $expected = ['/nav', '/div'];
        $this->assertHtml($expected, $result);

    }

    public function testText() {
        // Normal test
        $result = $this->navbar->text('Some text');
        $expected = [
            ['span' => ['class' => 'navbar-text']],
            'Some text',
            '/span'
        ];
        $this->assertHtml($expected, $result);

        // Custom options
        $result = $this->navbar->text('Some text', ['class' => 'my-class']);
        $expected = [
            ['span' => ['class' => 'navbar-text my-class']],
            'Some text',
            '/span'
        ];
        $this->assertHtml($expected, $result);
    }

    public function testMenu() {
        // TODO: Add test for this...
        $this->navbar->config('autoActiveLink', false);
        // Basic test:
        $this->navbar->create(null);
        $result = $this->navbar->beginMenu(['class' => 'my-menu']);
        $result .= $this->navbar->link('Link', '/', ['class' => 'active']);
        $result .= $this->navbar->link('Blog', ['controller' => 'pages', 'action' => 'test']);
        $result .= $this->navbar->beginMenu('Dropdown');
        $result .= $this->navbar->header('Header 1');
        $result .= $this->navbar->link('Action');
        $result .= $this->navbar->link('Another action');
        $result .= $this->navbar->link('Something else here');
        $result .= $this->navbar->divider();
        $result .= $this->navbar->header('Header 2');
        $result .= $this->navbar->link('Another action');
        $result .= $this->navbar->endMenu();
        $result .= $this->navbar->endMenu();
        $expected = [
            ['ul' => ['class' => 'navbar-nav my-menu']],
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['class' => 'nav-link', 'href' => '/']], 'Link', '/a', '/li',
            ['li' => ['class' => 'nav-item']],
            ['a' => ['class' => 'nav-link', 'href' => '/pages/test']], 'Blog', '/a', '/li',
            ['li' => ['class' => 'nav-item dropdown']],
            ['a' => ['href' => '#', 'class' => 'nav-link dropdown-toggle', 'data-toggle' => 'dropdown',
                     'aria-haspopup' => 'true', 'aria-expanded' => 'false']],
            'Dropdown', '/a',
            ['div' => ['class' => 'dropdown-menu']],
            ['h6' => ['class' => 'dropdown-header']], 'Header 1', '/h6',
            ['a' => ['href' => '/', 'class' => 'dropdown-item']], 'Action', '/a',
            ['a' => ['href' => '/', 'class' => 'dropdown-item']], 'Another action', '/a',
            ['a' => ['href' => '/', 'class' => 'dropdown-item']], 'Something else here', '/a',
            ['div' => ['class' => 'dropdown-divider']], '/div',
            ['h6' => ['class' => 'dropdown-header']], 'Header 2', '/h6',
            ['a' => ['href' => '/', 'class' => 'dropdown-item']], 'Another action', '/a',
            '/div',
            '/li',
            '/ul'
        ];
        $this->assertHtml($expected, $result);

        // TODO: Add more tests...
    }

    public function testAutoActiveLink() {
        $this->navbar->create(null);
        $this->navbar->beginMenu('');

        // Active and correct link:
        $this->navbar->config('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['class' => 'nav-link', 'href' => '/']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Active and incorrect link but more complex:
        $this->navbar->config('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['class' => 'nav-link', 'href' => '/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Unactive and correct link:
        $this->navbar->config('autoActiveLink', false);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['class' => 'nav-link', 'href' => '/']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Unactive and incorrect link:
        $this->navbar->config('autoActiveLink', false);
        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['class' => 'nav-link', 'href' => '/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Customt tests

        Router::scope('/', function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
        });
        Router::fullBaseUrl('');
        Configure::write('App.fullBaseUrl', 'http://localhost');
        $request = new Request();
        $request->addParams([
            'action' => 'view',
            'plugin' => null,
            'controller' => 'pages',
            'pass' => ['1']
        ]);
        $request->base = '/cakephp';
        $request->here = '/cakephp/pages/view/1';
        Router::setRequestInfo($request);

        $this->navbar->config('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages', [
            'active' => ['action' => false, 'pass' => false]
        ]);
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['class' => 'nav-link', 'href' => '/cakephp/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/cakephp/pages', 'class' => 'nav-link']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // More custom tests...
        Router::scope('/', function (RouteBuilder $routes) {
            $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']); // (1)
            $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']); // (2)
            $routes->fallbacks(DashedRoute::class);
        });
        Router::fullBaseUrl('');
        Configure::write('App.fullBaseUrl', 'http://localhost');
        $request = new Request();
        $request->addParams([
            'action' => 'display',
            'plugin' => null,
            'controller' => 'pages',
            'pass' => ['faq']
        ]);
        $request->base = '/cakephp';
        $request->here = '/cakephp/pages/faq';
        Router::setRequestInfo($request);

        $this->navbar->config('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages', [
            'active' => ['action' => false, 'pass' => false]
        ]);
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['href' => '/cakephp/pages', 'class' => 'nav-link']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages/credits');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/cakephp/pages/credits', 'class' => 'nav-link']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages/faq');
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['href' => '/cakephp/pages/faq', 'class' => 'nav-link']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);
    }

};
