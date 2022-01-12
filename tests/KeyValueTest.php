<?php

use ICal\ICal;
use PHPUnit\Framework\TestCase;

class KeyValueTest extends TestCase
{
    public function testBoundaryCharactersInsideQuotes()
    {
        $checks = array(
            0 => 'ATTENDEE',
            1 => array(
                0 => 'mailto:julien@ag.com',
                1 => array(
                    'PARTSTAT' => 'TENTATIVE',
                    'CN' => 'ju: @ag.com = Ju ; ',
                ),
            ),
        );

        $this->assertLines(
            'ATTENDEE;PARTSTAT=TENTATIVE;CN="ju: @ag.com = Ju ; ":mailto:julien@ag.com',
            $checks
        );
    }

    public function testUtf8Characters()
    {
        $checks = array(
            0 => 'ATTENDEE',
            1 => array(
                0 => 'mailto:juëǯ@ag.com',
                1 => array(
                    'PARTSTAT' => 'TENTATIVE',
                    'CN'       => 'juëǯĻ',
                ),
            ),
        );

        $this->assertLines(
            'ATTENDEE;PARTSTAT=TENTATIVE;CN=juëǯĻ:mailto:juëǯ@ag.com',
            $checks
        );

        $checks = array(
            0 => 'SUMMARY',
            1 => ' I love emojis 😀😁😁 ë, ǯ, Ļ',
        );

        $this->assertLines(
            'SUMMARY: I love emojis 😀😁😁 ë, ǯ, Ļ',
            $checks
        );
    }

    private function assertLines($lines, array $checks)
    {
        $ical = new ICal();

        self::assertEquals($ical->keyValueFromString($lines), $checks);
    }
}
