<?php
/**
 * This file is part of the Atline templating system package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2017 by Adam Banaszkiewicz
 *
 * @license   MIT License
 * @copyright Copyright (c) 2017, Adam Banaszkiewicz
 * @link      https://github.com/requtize/code-timer
 */

namespace Requtize\CodeTimer;

/**
 * @author Adam Banaszkiewicz https://github.com/requtize
 */
class CodeTimer
{
    const CAT_DEF_COLOR = '#dedede';

    protected $sections = [];
    protected $stops = [];
    protected $openedSections = [];
    protected $openedStops = [];
    protected $startTime;
    protected $endTime;
    protected $categories = [];
    protected $sectionsStack = [];

    public function setCategoryColor($category, $color)
    {
        $this->categories[$category]['color'] => $color;

        return $this;
    }

    public function getCategoryColor($category)
    {
        return isset($this->categories[$category]['color']) : $this->categories[$category]['color'] : self::CAT_DEF_COLOR;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function begin()
    {
        $this->startTime = microtime(true);

        return $this;
    }

    public function end()
    {
        foreach($this->openedSections as $name)
            $this->closeSection($name);

        foreach($this->openedStops as $name)
            $this->stop($name);

        $this->endTime = microtime(true);

        return $this;
    }

    public function openSection($name, $category = null)
    {
        $this->openedSections[$name] = [
            'start'    => microtime(true) - $this->startTime,
            'duration' => null,
            'memory'   => 17431234,
            'category' => $category,
            'category-color' => $this->getCategoryColor($category)
        ];

        array_push($this->sectionsStack, $name);

        return $this;
    }

    public function closeSection($name)
    {
        if(isset($this->openedSections[$name]) === false)
        {
            throw new Exception("Cannot close not opened section '{$name}'.");
        }

        $section = $this->openedSections[$name];
        $section['duration'] = microtime(true) - $this->startTime - $section['start'];

        $this->sections[] = $section;

        unset($this->openedSections[$name]);

        array_pop($this->sectionsStack);

        return $this;
    }

    public function start($name, $category = null)
    {
        $this->openedStops[$name] = [
            'start'    => microtime(true) - $this->startTime,
            'duration' => null,
            'memory'   => 17431234,
            'category' => $category,
            'category-color' => $this->getCategoryColor($category),
            'section'  => end($this->sectionsStack)
        ];

        return $this;
    }

    public function stop($name)
    {
        if(isset($this->openedStops[$name]) === false)
        {
            throw new Exception("Cannot stop '{$name}', when is not started.");
        }

        $stop = $this->openedStops[$name];
        $stop['duration'] = microtime(true) - $this->startTime - $stop['start'];

        $this->stops[] = $stop;

        unset($this->openedStops[$name]);

        return $this;
    }

    public function exportArray()
    {
        return [
            'total-time' => [
                'start'    => $this->startTime,
                'end'      => $this->endTime,
                'duration' => $this->endTime - $this->startTime
            ],
            'sections' => $this->sections
            'stops'    => $this->stops
        ];
    }

    public function exportJson()
    {
        return json_encode($this->exportArray());
    }
}
