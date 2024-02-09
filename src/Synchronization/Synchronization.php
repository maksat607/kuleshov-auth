<?php

namespace Maksatsaparbekov\KuleshovAuth\Synchronization;

interface Synchronization {
    public function send();
    public function handleResponse();
    public function handleError();
}