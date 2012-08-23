<?php
namespace Kendo\Acl;
use Kendo\Acl\RuleLoader;
use Kendo\Acl\MultiRoleInterface;
use Kendo\Acl\AccessObserver;
use Exception;
use InvalidArgumentException;

class Acl
{
    public $loader;
    public $observers = array();

    public function __construct(RuleLoader $loader)
    {
        $this->loader = $loader;
    }

    public function attach(AccessObserver $obs) {
        $this->observers["$obs"] = $obs;
    }

    public function detach(AccessObserver $obs) {
        delete($this->observers["$obs"]);
    }

    public function notifySuccess() 
    {
        foreach( $this->observers as $observer ) {
            $observer->success($this);
        }
    }

    public function notifyFail()
    {
        foreach( $this->observers as $observer ) {
            $observer->fail($this);
        }
    }

    public function can($user,$resource,$operation)
    {
        if( is_string($user) ) {
            $role = $user;
            return $this->loader->authorize($role,$resource,$operation);
        }
        elseif( $user instanceof MultiRoleInterface ) {
            foreach( $user->getRoles() as $role ) {
                if( true === $this->loader->authorize($role,$resource,$operation) )
                    return true;
            }
            return false;
        } else {
            throw new InvalidArgumentException;
        }
    }

    public function cannot($user,$resource,$operation) {
        return ! $this->can($user,$resource,$operation);
    }

    static public function getInstance($loader = null)
    {
        return new self($loader);
    }
}


