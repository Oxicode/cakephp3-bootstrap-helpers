<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\PaginatorHelper;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class PaginatorHelperTest extends TestCase {

    /**
     * Instance of PaginatorHelper.
     *
     * @var PaginatorHelper
     */
    public $paginator;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html'
        ]);
        $this->paginator = new PaginatorHelper($view);
        $this->paginator->request = new Request();
        $this->paginator->request->addParams([
            'paging' => [
                'Article' => [
                    'page' => 1,
                    'current' => 9,
                    'count' => 62,
                    'prevPage' => false,
                    'nextPage' => true,
                    'pageCount' => 7,
                    'sort' => null,
                    'direction' => null,
                    'limit' => null,
                ]
            ]
        ]);
        Configure::write('Routing.prefixes', []);
        Router::reload();
        Router::connect('/:controller/:action/*');
        Router::connect('/:plugin/:controller/:action/*');
    }

    public function testPrev() {
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item disabled'
            ]],
            ['a' => [
                'class' => 'page-link',
                'tabindex' => -1
            ]], '&lt;', '/a',
            '/li'
        ], $this->paginator->prev('<'));
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item disabled'
            ]],
            ['a' => [
                'class' => 'page-link',
                'tabindex' => -1
            ]],
            ['i' => [
                'class' => 'fa fa-chevron-left',
                'aria-hidden' => 'true'
            ]],
            '/i', '/a', '/li'
        ], $this->paginator->prev('i:chevron-left'));
    }

    public function testNext() {
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item'
            ]],
            ['a' => [
                'class' => 'page-link',
                'href' => '/index?page=2'
            ]], '&gt;', '/a',
            '/li'
        ], $this->paginator->next('>'));
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item'
            ]],
            ['a' => [
                'class' => 'page-link',
                'href' => '/index?page=2'
            ]],
            ['i' => [
                'class' => 'fa fa-chevron-right',
                'aria-hidden' => 'true'
            ]],
            '/i', '/a', '/li'
        ], $this->paginator->next('i:chevron-right'));
    }

};
