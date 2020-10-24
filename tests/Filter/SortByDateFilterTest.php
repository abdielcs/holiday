<?php

/*
 * This file is part of the umulmrum/holiday package.
 *
 * (c) Stefan Kruppa
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace umulmrum\Holiday\Filter;

use umulmrum\Holiday\HolidayTestCase;
use umulmrum\Holiday\Model\Holiday;
use umulmrum\Holiday\Model\HolidayList;

final class SortByDateFilterTest extends HolidayTestCase
{
    /**
     * @var SortByDateFilter
     */
    private $filter;
    /**
     * @var HolidayList
     */
    private $actualResult;

    /**
     * @test
     * @dataProvider getData
     *
     * @param string[] $holidays
     */
    public function it_should_filter_holidays(array $holidays, array $expectedResult): void
    {
        $this->givenASortByDateFilter();
        $this->whenFilterIsCalled($holidays);
        $this->thenACorrectlyFilteredResultShouldBeReturned($expectedResult);
    }

    private function givenASortByDateFilter(): void
    {
        $this->filter = new SortByDateFilter();
    }

    private function whenFilterIsCalled(array $holidays): void
    {
        $holidayList = new HolidayList();
        foreach ($holidays as $index => $date) {
            $holidayList->add(Holiday::create('name'.$index, $date));
        }
        $this->actualResult = $this->filter->filter($holidayList);
    }

    private function thenACorrectlyFilteredResultShouldBeReturned(array $expectedResult): void
    {
        $resultDates = [];
        foreach ($this->actualResult->getList() as $result) {
            $resultDates[] = $result->getFormattedDate('Y-m-d');
        }
        self::assertEquals($expectedResult, $resultDates);
    }

    public function getData(): array
    {
        return [
            [
                [
                    '2016-01-01',
                    '2016-01-02',
                    '2016-01-03',
                ],
                [
                    '2016-01-01',
                    '2016-01-02',
                    '2016-01-03',
                ],
            ],
            [
                [
                    '2016-01-03',
                    '2016-01-02',
                    '2016-01-01',
                ],
                [
                    '2016-01-01',
                    '2016-01-02',
                    '2016-01-03',
                ],
            ],
            [
                [
                    '2016-01-01',
                    '2016-01-01',
                ],
                [
                    '2016-01-01',
                    '2016-01-01',
                ],
            ],
            [
                [
                    '2016-01-01',
                ],
                [
                    '2016-01-01',
                ],
            ],
            [
                [
                    '2016-01-03',
                    '2016-01-01',
                    '2016-01-01',
                ],
                [
                    '2016-01-01',
                    '2016-01-01',
                    '2016-01-03',
                ],
            ],
            [
                [],
                [],
            ],
            [
                [
                    '2016-01-13',
                    '2016-01-13',
                    '2016-01-03',
                    '2016-01-03',
                    '2016-01-01',
                    '2016-01-01',
                ],
                [
                    '2016-01-01',
                    '2016-01-01',
                    '2016-01-03',
                    '2016-01-03',
                    '2016-01-13',
                    '2016-01-13',
                ],
            ],
            [
                [
                    '2016-01-13',
                    '2015-01-13',
                    '2014-01-03',
                    '2013-01-03',
                    '2012-01-01',
                    '2011-01-01',
                ],
                [
                    '2011-01-01',
                    '2012-01-01',
                    '2013-01-03',
                    '2014-01-03',
                    '2015-01-13',
                    '2016-01-13',
                ],
            ],
        ];
    }
}
