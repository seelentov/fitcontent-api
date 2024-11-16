<?php

use App\Jobs\ClearDeadFiles;
use App\Jobs\DefaultJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ClearDeadFiles)->daily();
