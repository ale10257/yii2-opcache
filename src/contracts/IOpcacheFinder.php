<?php

namespace ale10257\opcache\contracts;


interface IOpcacheFinder
{
    public function getDirectives();

    public function getVersion();

    public function getBlackList();

    public function getFiles();

    public function getStatus();

    public function getFilesDataProvider();

    public function invalidate($file = null);

    public function reset();
}