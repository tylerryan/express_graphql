<?php
namespace Concrete\Package\ExpressGraphql;

use Concrete\Core\Express\EntryList;

class ExpressResolver
{

    public static function getIngredientTypes($root, $args) {

        $entity = \Express::getObjectByHandle('ingredient_type');
        $attributes = $entity->getAttributes();

        $list = new EntryList($entity);
        $ingredients = $list->getResults();

        $items = array();
        foreach($ingredients as $ingredient) {
            $item = array('id'=>$ingredient->getID());
            foreach($attributes as $attribute) {
                $item[$attribute->getAttributeKeyHandle()] = $ingredient->getAttribute($attribute->getAttributeKeyHandle());
            }
            $items[] = $item;
        }

        return array('items'=>$items);
    }

}
