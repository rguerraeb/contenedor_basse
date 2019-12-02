<?php
namespace AppBundle\Helper;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
class UserHelper
{
    protected $encoder;

    public function __construct(UserPasswordEncoder $encoder)
    {
        $this->encoder = $encoder;
    }

    public function encodePassword($user, $password) {
        return $this->encoder->encodePassword($user, $password);
    }
}
?>