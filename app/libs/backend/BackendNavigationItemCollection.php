<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\backend;

use actra\yuf\layout\NavigationItemCollection;
use app\view\backend\php\clubs;
use app\view\backend\php\events;
use app\view\backend\php\members;
use app\view\backend\php\news;
use app\view\backend\php\overview;
use app\view\backend\php\pages;
use app\view\backend\php\vorstand;
use app\view\backend\php\webmail;

class BackendNavigationItemCollection extends NavigationItemCollection
{
    public function __construct()
    {
        parent::__construct();
        $this->addItem(navigationItem: overview::getNavigationItem());
        $this->addItem(navigationItem: vorstand::getNavigationItem());
        $this->addItem(navigationItem: news::getNavigationItem());
        $this->addItem(navigationItem: members::getNavigationItem());
        $this->addItem(navigationItem: clubs::getNavigationItem());
        $this->addItem(navigationItem: pages::getNavigationItem());
        $this->addItem(navigationItem: events::getNavigationItem());
        $this->addItem(navigationItem: webmail::getNavigationItem());
    }
}