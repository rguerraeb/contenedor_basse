<?php
namespace AppBundle\Helper;

class EntityHelper
{
    public function __construct()
    {
    }

    /**
     * Persists and removes the many to many relations of entity found in form
     *
     * @param string entity name
     * @param Entity $entity entity that has realtions
     * @param array $objs Entities that were selected in forms
     * @param array $accessMethods methods to get attribute to compare from form object
     * @param array $oAccessMethods methods to get attribute to compare from Promo Relation entity
     * @param boolean $isReference store object or attribute from form object
     * @param EntityManager $em doctrine manager to "save" changes
     */
    public function emPersistRemove(
        $entityName, $entity, $objs, $class, $accessMethods, $oAccessMethods, $isReference, $em) {
        // Get original ones, asuming find by entity works
        $originals = call_user_func_array(
            array(
                $em->getRepository('AppBundle:' . $entityName . $class),
                'findBy' . $entityName
            ),
            array(
                $entity
            )
        );

        // Find which ones to add
        foreach ($objs as $obj) {
            $attr = $this->accessMethods($obj, $accessMethods);
            $inArray = false;
            foreach ($originals as $original) {
                $originalAttr = $this->accessMethods($original, $oAccessMethods);
                if ($attr == $originalAttr) {
                    $inArray = true;
                    break;
                }
            }

            if (! $inArray) {
                if ($isReference) {
                    $params = $obj;
                }
                else {
                    $params = $attr;
                }

                // Needs to be created
                $objClass = 'AppBundle\Entity\\' . $entityName . $class;
                $newObj = new $objClass;
                call_user_func(
                    array(
                        $newObj,
                        'set' . $class
                    ),
                    $params
                );

                // Asuming set entity exists
                call_user_func_array(
                    array(
                        $newObj,
                        'set' . $entityName
                    ),
                    array(
                        $entity
                    )
                );
                $em->persist($newObj);
            }
        }

        // Find which ones to delete
        foreach ($originals as $original) {
            $originalAttr = $this->accessMethods($original, $oAccessMethods);

            $inArray = false;
            foreach ($objs as $obj) {
                $attr = $this->accessMethods($obj, $accessMethods);
                if ($attr == $originalAttr) {
                    $inArray = true;
                    break;
                }
            }

            if (!$inArray) {
                // Remove
                $em->remove($original);
            }
        }

        // Return without flush
        return $em;
    }

    /**
     * Tries to call functions from object
     *
     * @param Object $obj object to start executing methods
     * @param array $methods methods to execute starting on $obj
     * @return mixed return value of last method called
     */
    public function accessMethods($obj, $methods) {
        $activeObj = $obj;
        foreach ($methods as $method) {
            $activeObj = call_user_func(
                array(
                    $activeObj,
                    $method
                )
            );
        }

        return $activeObj;
    }

    /**
     * Call functions from objects to get attribute
     *
     * @param array $objs objects to executing methods
     * @param array $methods methods to execute  on object, uses the same methods for all $objs
     * @return array return value of last method called of each object
     */
    public function objectsToAttributes($objs, $methods) {
        $attributes = array();

        foreach ($objs as $obj) {
            // Call methods
            array_push($attributes, $this->accessMethods($obj, $methods));
        }

        return $attributes;
    }
}
?>