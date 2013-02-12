<?php namespace Websublime\Git;
/**
 * ------------------------------------------------------------------------------------
 * Command.php
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
use Websublime\Git\Exception\GitRuntimeException;

class Command {

     /**
     * @var string Real filesystem path of the repository
     */
    protected $dir;

    /**
     * @var string Git command to run
     */
    protected $commandString;

    /**
     * @var boolean Whether to enable debug mode or not
     * When debug mode is on, commands and their output are displayed
     */
    protected $debug;

    /**
     * Instanciate a new Git command
     *
     * @param   string $dir real filesystem path of the repository
     * @param   array $options
     */
    public function __construct($dir, $commandString, $debug = false)
    {
        $commandString = trim($commandString);

        $this->dir            = $dir;
        $this->commandString  = $commandString;
        $this->debug          = $debug;
    }

    public function run()
    {
        $commandToRun = sprintf('cd %s && %s', escapeshellarg($this->dir), $this->commandString);

        if($this->debug) {
            print $commandToRun."\n";
        }

        ob_start();
        passthru($commandToRun, $returnVar);
        $output = ob_get_clean();

        if($this->debug) {
            print $output."\n";
        }

        if(0 !== $returnVar) {
            // Git 1.5.x returns 1 when running "git status"
            if(1 === $returnVar && 0 === strncmp($this->commandString, 'git status', 10)) {
                // it's ok
            }
            else {
                throw new GitRuntimeException(sprintf(
                    'Command %s failed with code %s: %s',
                    $commandToRun,
                    $returnVar,
                    $output
                ), $returnVar);
            }
        }

        return trim($output);
    }
}

/** @end Command.php **/