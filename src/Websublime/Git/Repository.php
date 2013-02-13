<?php namespace Websublime\Git;
/**
 * ------------------------------------------------------------------------------------
 * Repository.php
 * ------------------------------------------------------------------------------------
 *
 * @package Websublime
 * @author  Miguel Ramos <miguel.marques.ramos@gmail.com>
 * @link    https://www.websublime.com
 * @version 0.1
 *
 * This file is part of Websublime Project.
 *
 * Copyright (c) 2012 
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
use Websublime\Config\Config,
    Websublime\Git\Exception\InvalidGitRepositoryDirectoryException;

class Repository {

    protected $dateFormat = 'iso';
    protected $logFormat = '"%H|%T|%an|%ae|%ad|%cn|%ce|%cd|%s"';

    protected $catalogue;

    public function __construct(Config $config, $repo='')
    {
        $this->catalogue = $config;

        $repo = empty($repo) ? '.' : $repo.'.';

        $this->catalogue->add($repo,'repo.config.name');

        $this->IsValidGitRepo();
    }

    public function IsValidGitRepo($path = null)
    {
        if(!is_null($path)){
            if(!file_exists($this->dir.'/.git/HEAD')) {
                throw new InvalidGitRepositoryDirectoryException($path.' is not a valid Git repository');
            }
        }

        $config = $this->catalogue->get('repo.config.name');
        var_dump($this->catalogue);

        if(!$this->catalogue->exist('options.'.$config.'settings.dir')){
            throw new \InvalidArgumentException('dir option doesn\'t exist in the catalogue');
        } else {
            if(!file_exists($this->catalogue->get('options.settings.dir').'/.git/HEAD')) {
                throw new InvalidGitRepositoryDirectoryException($this->catalogue->get('options.settings.dir').' is not a valid Git repository');
            }
        }

        return true;
    }
}

/** @end Repository.php **/