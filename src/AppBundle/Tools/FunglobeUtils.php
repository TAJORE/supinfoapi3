<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/22/17
 * Time: 7:19 AM
 */

namespace AppBundle\Tools;


use Symfony\Component\DependencyInjection\ContainerInterface;

class FunglobeUtils
{
    /**
     * Encoder le mot de passe de l'utilisateur
     *
     * @param ContainerInterface $container
     * @param object $object
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function encodePassword($container, $object, $password, $salt)
    {
        $factory = $container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($object);
        $password = $encoder->encodePassword($password, $salt);

        return $password;
    }
}