<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package log4php
 */

/**
 * This is a very simple filter based on string matching.
 *
 * <p>The filter admits two options {@link $stringToMatch} and
 * {@link $acceptOnMatch}. If there is a match (using {@link PHP_MANUAL#strpos}
 * between the value of the {@link $stringToMatch} option and the message
 * of the {@link LoggerLoggingEvent},
 * then the {@link decide()} method returns {@link LoggerFilter::ACCEPT} if
 * the <b>AcceptOnMatch</b> option value is true, if it is false then
 * {@link LoggerFilter::DENY} is returned. If there is no match, {@link LoggerFilter::NEUTRAL}
 * is returned.</p>
 *
 * <p>
 * An example for this filter:
 *
 * {@example ../../examples/php/filter_stringmatch.php 19}
 *
 * <p>
 * The corresponding XML file:
 *
 * {@example ../../examples/resources/filter_stringmatch.xml 18}
 *
 * @version $Revision: 1213283 $
 * @package log4php
 * @subpackage filters
 * @since 0.3
 */
class LoggerFilterNBLogs extends LoggerFilter
{

    /**
     * @var boolean
     */
    protected $acceptOnMatch = true;

    /**
     * @var string
     */
    protected $logsCount;

    /**
     * @var string
     */
    protected $windowSize;

    private $window = NULL;

    /**
     * @param mixed $acceptOnMatch a boolean or a string ('true' or 'false')
     */
    public function setAcceptOnMatch($acceptOnMatch)
    {
        $this->setBoolean('acceptOnMatch', $acceptOnMatch);
    }

    /**
     * @param string $s the string to match
     */
    public function setLogsCount($string)
    {
        $this->setString('logsCount', $string);
    }

    /**
     * @param string $s the string to match
     */
    public function setWindowSize($string)
    {
        $this->setString('windowSize', $string);
    }


    /**
     * @return integer a {@link LOGGER_FILTER_NEUTRAL} is there is no string match.
     */
    public function decide(LoggerLoggingEvent $event)
    {

        if ($this->window === NULL) {
            $this->window = array();
        }

        $time = time();

        $nbLogsInWindow = 0;
        foreach ($this->window as $t => $n) {
            if ($time - $t <= $this->windowSize) {
                $nbLogsInWindow += $n;
            }
        }
        if ($nbLogsInWindow > $this->logsCount) {
            //echo("More than " . $this->logsCount . "($nbLogsInWindow) log events have been logged in the last " . $this->windowSize . " seconds. Deny log : " . $event->getRenderedMessage() . "\n");
            return LoggerFilter::DENY;
        } else {
            if (isset($this->window[$time])) {
                $this->window[$time]++;
            } else {
                $this->window[$time] = 1;
            }
        }

        foreach ($this->window as $t => $n) {
            if ($time - $t > $this->windowSize) {
                unset($this->window[$t]);
            }
        }

        return LoggerFilter::NEUTRAL;
    }
}
