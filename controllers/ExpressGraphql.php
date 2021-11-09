<?php

/**
 * Handler for GraphQL query requests
 */

namespace Concrete\Package\ExpressGraphql\Controller;

use Concrete\Core\Controller\Controller;
use Concrete\Core\Http\Request;
use Concrete\Package\ExpressGraphql\ExpressResolvers;
use Concrete\Package\ExpressGraphql\ExpressSchema;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExpressGraphql extends Controller
{

    /**
     * Handles GraphQL queries at route /express-graphql/query
     */
    public function query()
    {

        $schema = ExpressSchema::get();

        $body = json_decode(Request::getInstance()->getContent());

        $variables = ($body->variables?$body->variables:null);

        $result = \GraphQL\GraphQL::executeQuery($schema, $body->query, null, array(), $variables);

        $response = new JsonResponse($result);

        $response->send();

    }

}
