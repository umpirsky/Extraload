<?php

namespace Extraload;

final class Events
{
    const PRE_PROCESS = 'extraload.pre_process';
    const POST_PROCESS = 'extraload.post_process';
    const EXTRACT = 'extraload.extract';
    const TRANSFORM = 'extraload.transform';
    const LOAD = 'extraload.load';

    private function __construct()
    {
    }
}
