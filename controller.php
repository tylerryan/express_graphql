<?php

/**
 * Concrete CMS package controller for the Express Graphql
 * Provides a Graphql endpoint for Express entities
 */

namespace Concrete\Package\ExpressGraphql;

use Concrete\Core\Package\Package;

class Controller extends Package
{

    protected $pkgHandle = 'express_graphql';
    protected $appVersionRequired = '8.5';
    protected $pkgVersion = '0.1';

    protected $pkgAutoloaderRegistries = array(
        'src' => '\Concrete\Package\ExpressGraphql'
    );

    public function getPackageDescription()
    {
        return t("Provides a GraphQL endpoint for Express Objects");
    }

    public function getPackageName()
    {
        return t("Express GraphQL");
    }

    public function on_start()
    {
        require $this->getPackagePath() . '/vendor/autoload.php';
        \Route::register('/express-graphql/query', '\Concrete\Package\ExpressGraphql\Controller\ExpressGraphql::query');
    }

}
