<?php

namespace mmerlijn\helperTimeArray\tests\Unit;

use mmerlijn\helperTimeArray\tests\TestCase;
use mmerlijn\helperTimeArray\TimeArray;


final class BasicTest extends TestCase
{
    public function test_create_time_array():void
    {
        $ta = new TimeArray();
        self::assertTrue($ta instanceof TimeArray);
    }
    public function test_initialisation()
    {
        $ta = new TimeArray([500,600]);
        $this->assertSame([[500,600]],$ta->get());
        $ta = new TimeArray([[600,700]]);
        $this->assertSame([[600,700]],$ta->get());
    }
    public function test_create_method()
    {
        $ta = new TimeArray();
        $ta->create([500,600]);
        $this->assertSame([[500,600]],$ta->get());
    }
    public function test_get_result()
    {
        $ta = new TimeArray();
        $this->assertIsArray($ta->get());
    }
    public function test_add_times()
    {
        $ta = new TimeArray();
        $ta = $ta->add([600,720])->add([800,880]);
        $this->assertSame([[600,720],[800,880]],$ta->get());
        $ta = $ta->add([720,800]); //will compact the array
        $this->assertSame([[600,880]],$ta->get());
    }
    public function test_split_method()
    {
        $ta = new TimeArray();
        $ta->add([600,800]);
        $this->assertSame([[600,650],[650,700],[700,750],[750,800]],$ta->split(50));
    }
    public function test_subtract_times()
    {
        $ta = new TimeArray();
        $ta = $ta->add([600,720])->add([730,800])->subtract([700,770]);
        $this->assertSame([[600,700],[770,800]],$ta->get());
        $ta = new TimeArray();
        $ta = $ta->add([600,800])->subtract([700,750]);
        $this->assertSame([[600,700],[750,800]], $ta->get());
    }
    public function test_substract_whole_time()
    {
        $ta = new TimeArray();
        $ta = $ta->create([500,600])->subtract([0,1440])->get();
        $this->assertEquals([], $ta);
    }
    public function test_to_string()
    {
        $ta = new TimeArray();
        $ta = $ta->create([500,600])->add([700,800]);
        $this->assertEquals("[[500,600],[700,800]]", (string)$ta);
    }
    public function test_intersect()
    {
        $ta = new TimeArray([[500,600],[700,800]]);
        $this->assertEquals([[500,600],[700,800]], $ta->intersect([[500,600],[700,800]])->get());

        $ta = new TimeArray([[500,600],[700,800]]);
        $this->assertEquals([[700,800]], $ta->intersect([[700,800]])->get());

        $ta = new TimeArray([[500,600],[700,800]]);
        $this->assertEquals([[550,600],[700,750]], $ta->intersect([[550,750]])->get());

        $ta = new TimeArray();
        $this->assertEquals([], $ta->intersect([[550,750]])->get());

        $ta = new TimeArray([[500,600],[700,800]]);
        $this->assertEquals([[750,800]], $ta->intersect([[750,850]])->get());

        $ta = new TimeArray([[500,600],[700,800]]);
        $this->assertEquals([], $ta->intersect([[800,850]])->get());

        $ta = new TimeArray([[500,600],[700,800]]);
        $this->assertEquals([], $ta->intersect([[600,610],[800,810]])->get());
    }

    public function test_human_readable()
    {
        $ta = new TimeArray([[500,600],[700,800]]);
        $this->assertEquals('8:20-10:00, 11:40-13:20',$ta->forHumans());
    }
}