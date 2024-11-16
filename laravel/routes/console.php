<?php

use App\Jobs\ClearDeadFiles;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ClearDeadFiles)->daily();
