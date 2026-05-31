<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cache:cleanup')->daily();
