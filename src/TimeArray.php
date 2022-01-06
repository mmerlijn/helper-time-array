<?php
namespace mmerlijn\helperTimeArray;

class TimeArray
{
    private array $timeArray = [];
    //private array $problemArray = [];
    //private bool $problem = false;

    public function __construct(?array $timeArray=null)
    {
        if($timeArray){
            $this->create($timeArray);
        }
    }

    /** Initialising timeArray
     *
     * @param array $array
     * @return TimeArray|null
     */
    public function create(array $array): TimeArray
    {
        if (!isset($array[0][0])) {
            if (count($array)) {
                $array = [$array];
            }
        }
        $this->timeArray = $array;
        $this->compact();
        return $this;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->timeArray;
    }

    /** adds times to array
     * @param $times
     * @return TimeArray
     */
    public function add(array $times): TimeArray
    {
        if (isset($times[0])) {
            if (is_array($times[0])) {
                foreach ($times as $time) {
                    $this->timeArray[] = $time;
                }
            } else {
                $this->timeArray[] = $times;
            }
            $this->compact();
        }
        return $this;
    }

    /** Given a duration, splits the timeArray in chunks of that duration
     *
     * @param int $duration
     * @return array
     */
    public function split(int $duration): array
    {
        $splitTimes = [];
        foreach ($this->timeArray as $times) {
            for ($i = $times[0] + $duration; $i <= $times[1]; $i += $duration) {
                $splitTimes[] = [$i - $duration, $i];
            }
        }
        return $splitTimes;
    }


    /** substract times from the TimeArray
     * @param array $times
     * @return TimeArray
     */
    public function subtract(array $times): TimeArray
    {
        if (isset($times[0])) {
            if (is_array($times[0])) {
                foreach ($times as $time) {
                    $this->subtract($time);
                }
            } else {
                list($s_, $e_) = $times;
                $length = count($this->timeArray);
                for ($i = 0; $i < $length; $i++) {
                    list($s, $e) = $this->timeArray[$i];
                    //voorliggend
                    //blok     --------
                    //sit 1  -----
                    //sit 2    -----
                    //sit 3  ------------
                    if ($s_ <= $s and $e_ > $s) {
                        if ($e_ < $e) {
                            $this->timeArray[$i] = [$e_, $e];
                        } else {
                            unset($this->timeArray[$i]);
                        }
                        //overliggend
                        //blok          ---------
                        //sit 1            ---
                        //sit 2            --------
                    } elseif ($s_ > $s and $s_ < $e) {
                        $this->timeArray[] = [$s, $s_];
                        if ($e_ < $e) {
                            $this->timeArray[$i] = [$e_, $e];
                        } else {
                            unset($this->timeArray[$i]);
                        }
                    }
                }
            }
            sort($this->timeArray);
        }
        return $this;
    }


    /**joins the timeArray if end time and start time are equal or overlap
     * @return void
     */
    private function compact(): void
    {
        $length = count($this->timeArray);
        sort($this->timeArray);
        for ($i = 1; $i < count($this->timeArray); $i++) {
            list($s_, $e_) = $this->timeArray[$i - 1];
            list($s, $e) = $this->timeArray[$i];
            if ($s_ == $s) {
                if ($e_ > $e) {
                    unset($this->timeArray[$i]);
                } else {
                    unset($this->timeArray[$i - 1]);
                }
                break;
            } elseif ($e_ >= $s) {
                if ($e_ > $e) {
                    $this->timeArray[$i - 1] = [$s_, $e_];
                    unset($this->timeArray[$i]);
                } else {
                    $this->timeArray[$i - 1] = [$s_, $e];
                    unset($this->timeArray[$i]);
                }
                break;
            }
        }
        if ($length != count($this->timeArray)) {
            $this->compact();
        }
    }
// Use of problem array's should be evaluated
//    /** same as compact, but for the problemArray
//     * @return void
//     */
//    private function compactProblem(): void
//    {
//        $length = count($this->problemArray);
//        sort($this->problemArray);
//        for ($i = 1; $i < count($this->problemArray); $i++) {
//            list($s_, $e_) = $this->problemArray[$i - 1];
//            list($s, $e) = $this->problemArray[$i];
//            if ($s_ == $s) {
//                if ($e_ > $e) {
//                    unset($this->problemArray[$i]);
//                } else {
//                    unset($this->problemArray[$i - 1]);
//                }
//                break;
//            } elseif ($e_ >= $s) {
//                if ($e_ > $e) {
//                    $this->problemArray[$i - 1] = [$s_, $e_];
//                    unset($this->problemArray[$i]);
//                } else {
//                    $this->problemArray[$i - 1] = [$s_, $e];
//                    unset($this->problemArray[$i]);
//                }
//                break;
//            }
//        }
//        if ($length != count($this->problemArray)) {
//            $this->compact();
//        }
//    }
//
//    /** search of $times is in de timeArray, set the problemArray and problem if not present
//     * @param array $times
//     * @return TimeArray
//     */
//    public function checkProblem(array $times): TimeArray
//    {
//        $problem = true;
//        list($s_, $e_) = $times;
//        foreach ($this->timeArray as $time) {
//            list($s, $e) = $time;
//            if ($s_ >= $s and $e_ <= $e) {
//                $problem = false;
//            }
//        }
//        if ($problem) {
//            $this->problem = true;
//            $this->problemArray[] = $times;
//        }
//        return $this;
//    }
//
//    /** check for problems with a multiple timesArray as input
//     * @param array $timesArray
//     * @return TimeArray
//     */
//    public function checkProblems(array $timesArray): TimeArray
//    {
//        foreach ($timesArray as $times) {
//            $this->checkProblem($times);
//        }
//        return $this;
//    }
//
//    /** Checks if problems are presented
//     * @return bool
//     */
//    public function isProblem(): bool
//    {
//        return $this->problem;
//    }
//
//    /** Returns a timeArray with problems
//     * @return array
//     */
//    public function getProblems(): array
//    {
//        $this->compactProblem();
//        return $this->problemArray;
//    }

}
