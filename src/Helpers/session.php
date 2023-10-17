<?php

use Learn\Session\Session;

function session(): Session
{
    return app()->session;
}
