<?php
namespace ActionKit\ActionTemplate;
use ActionKit\ActionRunner;

interface ActionTemplate
{
    public function register(ActionRunner $runner, $asTemplate, array $options = array());
    public function generate($targetClassName, array $options = array());
}