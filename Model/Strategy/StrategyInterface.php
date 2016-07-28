<?php
/**
 * Copyright © 2016 OpsWay.
 */
namespace OpsWay\EmailAmazonSES\Model\Strategy;


interface StrategyInterface
{
    public function getConfig();

    public function send();
}