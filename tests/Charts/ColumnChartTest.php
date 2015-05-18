<?php

namespace Khill\Lavacharts\Tests\Charts;

use \Khill\Lavacharts\Charts\ColumnChart;
use \Mockery as m;

class ColumnChartTest extends ChartTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->cc = new ColumnChart('MyTestChart', $this->partialDataTable);
    }

    public function testInstanceOfColumnChartWithType()
    {
        $this->assertInstanceOf('\Khill\Lavacharts\Charts\ColumnChart', $this->cc);
    }

    public function testTypeColumnChart()
    {
        $chart = $this->cc;

        $this->assertEquals('ColumnChart', $chart::TYPE);
    }

    public function testLabelAssignedViaConstructor()
    {
        $this->assertEquals('MyTestChart', $this->cc->label);
    }

    public function testAxisTitlesPositionValidValues()
    {
        $this->cc->axisTitlesPosition('in');
        $this->assertEquals('in', $this->cc->getOption('axisTitlesPosition'));

        $this->cc->axisTitlesPosition('out');
        $this->assertEquals('out', $this->cc->getOption('axisTitlesPosition'));

        $this->cc->axisTitlesPosition('none');
        $this->assertEquals('none', $this->cc->getOption('axisTitlesPosition'));
    }

    /**
     * @expectedException \Khill\Lavacharts\Exceptions\InvalidConfigValue
     */
    public function testAxisTitlesPositionWithBadValue()
    {
        $this->cc->axisTitlesPosition('happymeal');
    }

    /**
     * @dataProvider nonStringProvider
     * @expectedException \Khill\Lavacharts\Exceptions\InvalidConfigValue
     */
    public function testAxisTitlesPositionWithBadType($badTypes)
    {
        $this->cc->axisTitlesPosition($badTypes);
    }

    public function testBarGroupWidthWithInt()
    {
        $this->cc->barGroupWidth(200);

        $bar = $this->cc->getOption('barGroupWidth');

        $this->assertEquals(200, $bar['groupWidth']);
    }

    public function testBarGroupWidthWithPercent()
    {
        $this->cc->barGroupWidth('33%');

        $bar = $this->cc->getOption('barGroupWidth');

        $this->assertEquals('33%', $bar['groupWidth']);
    }

    /**
     * @dataProvider nonIntOrPercentProvider
     * @expectedException \Khill\Lavacharts\Exceptions\InvalidConfigValue
     */
    public function testBarGroupWidthWithBadTypes($badTypes)
    {
        $this->cc->barGroupWidth($badTypes);
    }

    public function testHorizontalAxis()
    {
        $mockHorizontalAxis = m::mock('Khill\Lavacharts\Configs\HorizontalAxis');
        $mockHorizontalAxis->shouldReceive('toArray')->once()->andReturn([
            'hAxis' => []
        ]);

        $this->cc->hAxis($mockHorizontalAxis);

        $this->assertTrue(is_array($this->cc->getOption('hAxis')));
    }

    public function testIsStacked()
    {
        $this->cc->isStacked(true);

        $this->assertTrue($this->cc->getOption('isStacked'));
    }

    /**
     * @dataProvider nonBoolProvider
     * @expectedException \Khill\Lavacharts\Exceptions\InvalidConfigValue
     */
    public function testIsStackedWithBadType($badTypes)
    {
        $this->cc->isStacked($badTypes);
    }

    public function testVerticalAxis()
    {
        $mockVerticalAxis = m::mock('Khill\Lavacharts\Configs\VerticalAxis');
        $mockVerticalAxis->shouldReceive('toArray')->once()->andReturn([
            'vAxis' => []
        ]);

        $this->cc->vAxis($mockVerticalAxis);

        $this->assertTrue(is_array($this->cc->getOption('vAxis')));
    }

    public function nonIntOrPercentProvider()
    {
        return [
            [3.2],
            [true],
            [false],
            [[]],
            [new \stdClass]
        ];
    }
}
