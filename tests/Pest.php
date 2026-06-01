<?php

use Tests\TestCase;

pest()->extends(TestCase::class)->in('Feature');

beforeEach(function () {
    $this->withoutVite();
});
