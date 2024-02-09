<?php

namespace Maksatsaparbekov\KuleshovAuth\Synchronization;

interface Synchronization {
    public function sync();
    public function handleResponse();
    public function handleError();
    public function setUser($user);
}