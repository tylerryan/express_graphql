<?php

/**
 * Maps Express data objects to a Graphql Schema
 */

namespace Concrete\Package\ExpressGraphql;

use Concrete\Core\Package\ItemCategory\ExpressEntity;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

class ExpressSchema
{

    /**
     * gets the GraphQL schema object for all public Express entities
     * @TODO implement caching...
     * @return Schema
     */
    public static function get()
    {
        $fields = array();
        $entities = self::getExpressEntities();
        foreach ($entities as $entity) {
            $fields['get'.app('helper/text')->camelcase($entity->getPluralHandle())] = self::getEntitySchemaType($entity);
        }

        $query = new ObjectType([
                                   'name' => 'Query',
                                   'fields' => $fields
                               ]);

        return new Schema(['query' => $query]);
    }

    /**
     * Returns a list of public express entities
     * @return ExpressEntity[]
     */
    protected static function getExpressEntities()
    {
        $entityManager = \Core::make(EntityManagerInterface::class);
        $repo = $entityManager->getRepository('\Concrete\Core\Entity\Express\Entity');
        $entities = $repo->findBy(array('include_in_public_list' => true));
        return $entities;
    }

    /**
     * @param ExpressEntity $entity
     * @return ObjectType
     */
    protected static function getEntitySchemaType($entity)
    {

        $th = app('helper/text');

        $attributes = $entity->getAttributes();

        $fields = array('id'=>Type::int());
        foreach($attributes as $attribute) {
            $fields[$attribute->getAttributeKeyHandle()] = Type::string(); // @TODO map different attribute types
        }

        $result_item = new ObjectType([
                                         'name' => $th->camelcase($entity->getHandle()),
                                         'fields' => $fields
                                      ]);

        $result_items = new ObjectType([
                                          'name' => $th->camelcase($entity->getPluralHandle()),
                                          'fields' => [
                                              'items' => Type::listof($result_item)
                                          ]
                                      ]);

        // @TODO build out default resolver to handle express types dynamically
        $results = [
            'type' => $result_items,
            'resolve' => function($root, $args) {
                    return ExpressResolver::getIngredientTypes($root, $args);
                }
        ];

        return $results;

    }

}
