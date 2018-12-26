<?php

use SuiteCRM\StateCheckerPHPUnitTestCaseAbstract;
use SuiteCRM\StateSaver;

require_once __DIR__ . '/../../../../../include/GoogleSync/GoogleSync.php';

class GoogleSyncTest extends StateCheckerPHPUnitTestCaseAbstract
{
    /** @var UnitTester */
    protected $tester;

    /** @var ReflectionClass */
    protected static $reflection;

    /** @var ReflectionProperty */
    protected static $dbProperty;

    public function setUp()
    {
        parent::setUp();

        // Use reflection to access private properties and methods
        if (self::$reflection === null) {
            self::$reflection = new ReflectionClass(GoogleSync::class);
            self::$dbProperty = self::$reflection->getProperty('db');
            self::$dbProperty->setAccessible(true);
        }
    }

    public function exception_error_handler($errno, $errstr, $errfile, $errline )
    {
        throw new Exception($errstr);
    }

    // GoogleSyncBase.php

 

    //GoogleSyncHelper.php

    public function testSingleEventAction()
    {
        $helper = new GoogleSyncHelper;

        $ret1 = $helper->singleEventAction(null, null);
        $this->assertEquals(false, $ret1);
        // The rest of this method is tested by testPushPullSkip
    }

    public function testGetTimeStrings()
    {
        $helper = new GoogleSyncHelper;

        // Create SuiteCRM Meeting Object
        $CRM_Meeting = new Meeting();

        $CRM_Meeting->id = create_guid();
        $CRM_Meeting->name = 'Unit Test Event';
        $CRM_Meeting->description = 'Unit Test Event';

        // Create Google Event Object
        $Google_Event = new Google_Service_Calendar_Event();

        $Google_Event->setSummary('Unit Test Event');
        $Google_Event->setDescription('Unit Test Event');
        $Google_Event->updated = '2018-01-01 12:00:00 UTC';

        // The event needs a start time method to pass
        $startDateTime = new Google_Service_Calendar_EventDateTime;
        $startDateTime->setDateTime(date(DATE_ATOM, strtotime('2018-01-01 12:00:00 UTC')));
        $Google_Event->setStart($startDateTime);

        $CRM_Meeting->fetched_row['date_modified'] = '2018-01-01 12:00:00';
        $CRM_Meeting->fetched_row['gsync_lastsync'] = strtotime('2018-01-01 12:00:00 UTC');

        $ret = $helper->getTimeStrings($CRM_Meeting, $Google_Event);

        $this->assertEquals('1514808000', $ret['gModified']);
        $this->assertEquals('1514808000', $ret['sModified']);
        $this->assertEquals('1514808000', $ret['lastSync']);
    }

    public function testGetNewestMeetingResponse()
    {
        $this->markTestIncomplete('TODO: Implement Tests');
    }

    public function testCreateSuitecrmReminders()
    {
        $this->markTestIncomplete('TODO: Implement Tests');
    }

    // GoogleSyncExceptions.php

    public function testCustomExceptions()
    {
        $object = new GoogleSync();

        $e = null;
        try {
            throw new E_MissingParameters;
        } catch (Exception $e) {
        }
        $this->assertEquals(1, $e->getCode());
        $this->assertEquals('Missing Parameters When Calling A Method', $e->getMessage());

        $e = null;
        try {
            throw new E_InvalidParameters;
        } catch (Exception $e) {
        }
        $this->assertEquals(2, $e->getCode());
        $this->assertEquals('Invalid Parameters When Calling A Method', $e->getMessage());

        $e = null;
        try {
            throw new E_ValidationFailure;
        } catch (Exception $e) {
        }
        $this->assertEquals(3, $e->getCode());
        $this->assertEquals('Value Failed Validation', $e->getMessage());

        $e = null;
        try {
            throw new E_RecordRetrievalFail;
        } catch (Exception $e) {
        }
        $this->assertEquals(4, $e->getCode());
        $this->assertEquals('Failed To Retrive A SuiteCRM Record', $e->getMessage());

        $e = null;
        try {
            throw new E_TimezoneSetFailure;
        } catch (Exception $e) {
        }
        $this->assertEquals(5, $e->getCode());
        $this->assertEquals('Failed To Set The Timezone', $e->getMessage());

        $e = null;
        try {
            throw new E_NoRefreshToken;
        } catch (Exception $e) {
        }
        $this->assertEquals(6, $e->getCode());
        $this->assertEquals('Refresh Token Missing From Google Client', $e->getMessage());

        $e = null;
        try {
            throw new E_GoogleClientFailure;
        } catch (Exception $e) {
        }
        $this->assertEquals(7, $e->getCode());
        $this->assertEquals('Google Client Failed To Initialize', $e->getMessage());

        $e = null;
        try {
            throw new E_GoogleServiceFailure;
        } catch (Exception $e) {
        }
        $this->assertEquals(8, $e->getCode());
        $this->assertEquals('Google Client Failed To Initialize', $e->getMessage());

        $e = null;
        try {
            throw new E_GoogleCalendarFailure;
        } catch (Exception $e) {
        }
        $this->assertEquals(9, $e->getCode());
        $this->assertEquals('Google Calendar Service Failure', $e->getMessage());

        $e = null;
        try {
            throw new E_GoogleRecordParseFailure;
        } catch (Exception $e) {
        }
        $this->assertEquals(10, $e->getCode());
        $this->assertEquals('Failed To Parse Google Record', $e->getMessage());

        $e = null;
        try {
            throw new E_DbDataError;
        } catch (Exception $e) {
        }
        $this->assertEquals(11, $e->getCode());
        $this->assertEquals('SuiteCRM DB Data Unexpected Or Corrupt', $e->getMessage());
    }
}
