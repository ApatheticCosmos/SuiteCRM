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

    public function test__construct()
    {
        // Set up object for testing
        global $sugar_config;
        // base64 encoded of {"web":"test"}
        $sugar_config['google_auth_json'] = 'eyJ3ZWIiOiJ0ZXN0In0=';

        // Set Log Level
        // if (!empty($_SERVER['GSYNC_LOGLEVEL'])) {
        //     $expectedLogLevel = $_SERVER['GSYNC_LOGLEVEL'];
        // } else {
        //     $_SERVER['GSYNC_LOGLEVEL'] = 'debug';
        //     $expectedLogLevel = 'debug';
        // }

        $object = new GoogleSync();

        // Test GoogleSync::timezone
        $timezone = self::$reflection->getProperty('timezone');
        $timezone->setAccessible(true);
        $this->assertNotEmpty($timezone->getValue($object));
        $this->assertEquals("string", gettype($timezone->getValue($object)));

        // Test GoogleSync::authJson
        $authJson = self::$reflection->getProperty('authJson');
        $authJson->setAccessible(true);
        $this->assertNotEmpty($authJson->getValue($object));
        $this->assertEquals("array", gettype($authJson->getValue($object)));

        // Test GoogleSync::db
        $expectedClass = DBManager::class;
        $actualClass = self::$dbProperty->getValue($object);
        $this->assertInstanceOf($expectedClass, $actualClass);

        // Test log level
        // $actualLogLevel = LoggerManager::getLogLevel();
        // $this->assertEquals($expectedLogLevel, $actualLogLevel);
    }

    public function testGetAuthJson()
    {
        $this->markTestIncomplete('TODO: Implement Tests');
        $state = new SuiteCrm\StateSaver();
        $state->pushGlobals();

        $method = self::$reflection->getMethod('getAuthJson');
        $method->setAccessible(true);
    
        // Set up object for testing
        global $sugar_config;

        // base64 encoded of {"web":"test"}
        $sugar_config['google_auth_json'] = 'eyJ3ZWIiOiJ0ZXN0In0=';

        $object = new GoogleSync();

        $expectedAuthJson = json_decode(base64_decode('eyJ3ZWIiOiJ0ZXN0In0'), true);
        $actualAuthJson = $method->invoke($object);

        $state->popGlobals();

        $this->assertEquals($expectedAuthJson, $actualAuthJson);
        $this->assertArrayHasKey('web', $actualAuthJson);

    }

    public function testSetClient()
    {
        $method = self::$reflection->getMethod('setClient');
        $method->setAccessible(true);
        $object = new GoogleSync();
        try {
            $method->invoke($object, null);
        } catch (Exception $e) {}
        $this->assertEquals(3, $e->getCode());
    }

    public function testGetClient()
    {
        $method = self::$reflection->getMethod('getClient');
        $method->setAccessible(true);
        $object = new GoogleSync();
        try {
            $method->invoke($object, null);
        } catch (Exception $e) {}
        $this->assertEquals(3, $e->getCode());
    }

    public function testGetGoogleClient()
    {
        $method = self::$reflection->getMethod('getGoogleClient');
        $method->setAccessible(true);
        $object = new GoogleSync();
        try {
            $method->invoke($object, null);
        } catch (Exception $e) {}
        $this->assertEquals('invalid json token', $e->getMessage());
    }

    public function testInitUserService()
    {
        $method = self::$reflection->getMethod('initUserService');
        $method->setAccessible(true);
        $object = new GoogleSync();
        try {
            $method->invoke($object, null);
        } catch (Exception $e) {}
        $this->assertEquals(3, $e->getCode());
    }

    public function testGetUserMeetings()
    {
        $this->markTestIncomplete('TODO: Implement Tests');
        $state = new SuiteCRM\StateSaver();
        $state->pushTable('meetings');
        $state->pushTable('meetings_cstm');
        $state->pushTable('users');
        $state->pushTable('user_preferences');
        $state->pushTable('aod_indexevent');
        $state->pushTable('aod_index');
        $state->pushTable('vcals');
        $state->pushTable('tracker');

        // Create a User
        $user = new User();
        $user->last_name = 'UNIT_TESTS';
        $user->user_name = 'UNIT_TESTS';
        $user->save();

        // Create three meetings and save them to the DB for testing.
        $meeting1 = new Meeting();
        $meeting1->name = 'UNIT_TEST_1';
        $meeting1->assigned_user_id = $user->id;
        $meeting1->status = 'Not Held';
        $meeting1->type = 'Sugar';
        $meeting1->description = 'test description';
        $meeting1->duration_hours = 1;
        $meeting1->duration_minutes = 1;
        $meeting1->date_start = '2016-02-11 17:30:00';
        $meeting1->date_end = '2016-02-11 17:30:00';
        $meeting1->save();

        $meeting2 = new Meeting();
        $meeting2->name = 'UNIT_TEST_2';
        $meeting2->assigned_user_id = $user->id;
        $meeting2->status = 'Not Held';
        $meeting2->type = 'Sugar';
        $meeting2->description = 'test description';
        $meeting2->duration_hours = 1;
        $meeting2->duration_minutes = 1;
        $meeting2->date_start = '2016-02-11 17:30:00';
        $meeting2->date_end = '2016-02-11 17:30:00';
        $meeting2->save();

        $meeting3 = new Meeting();
        $meeting3->name = 'UNIT_TEST_3';
        $meeting3->assigned_user_id = $user->id;
        $meeting3->status = 'Not Held';
        $meeting3->type = 'Sugar';
        $meeting3->description = 'test description';
        $meeting3->duration_hours = 1;
        $meeting3->duration_minutes = 1;
        $meeting3->date_start = '2016-02-11 17:30:00';
        $meeting3->date_end = '2016-02-11 17:30:00';
        $meeting3->save();

        $method = self::$reflection->getMethod('getUserMeetings');
        $method->setAccessible(true);

        $object = new GoogleSync();

        $return_count = $method->invoke($object, $user->id);

        // Test for invalid user id exception handling
        try {
            $caught = false;
            $return = $method->invoke($object, 'INVALID');
        } catch (Exception $e) {
            $caught = true;
        }

        $state->popTable('meetings');
        $state->popTable('meetings_cstm');
        $state->popTable('users');
        $state->popTable('user_preferences');
        $state->popTable('aod_indexevent');
        $state->popTable('aod_index');
        $state->popTable('vcals');
        $state->popTable('tracker');

        $this->assertEquals(3, count($return_count));
        $this->assertTrue($caught);
    }

    public function testSetUsersGoogleCalendar()
    {
        $method = self::$reflection->getMethod('setUsersGoogleCalendar');
        $method->setAccessible(true);
        $object = new GoogleSync();
        $this->assertEquals(false, $method->invoke($object));
    }

    public function testGetSuiteCRMCalendar()
    {
        $method = self::$reflection->getMethod('getSuiteCRMCalendar');
        $method->setAccessible(true);
        $object = new GoogleSync();

        set_error_handler(array($this, 'exception_error_handler'));

        try {
            $method->invoke($object, null);
        }
        catch (Error $e) {}
        catch (Exception $e) {}
        set_error_handler(null);
        $this->assertContains('GoogleSyncBase::getSuiteCRMCalendar()', $e->getMessage());
    }

    public function testGetUserGoogleEvents()
    {
        $method = self::$reflection->getMethod('getUserGoogleEvents');
        $method->setAccessible(true);
        $object = new GoogleSync();
        $this->assertEquals(false, $method->invoke($object));
    }

    public function testIsServiceExists()
    {
        $method = self::$reflection->getMethod('isServiceExists');
        $method->setAccessible(true);
        $object = new GoogleSync();
        $this->assertEquals(false, $method->invoke($object));
    }

    public function testIsCalendarExists()
    {
        $method = self::$reflection->getMethod('isCalendarExists');
        $method->setAccessible(true);
        $object = new GoogleSync();
        $this->assertEquals(false, $method->invoke($object));
    }

    public function testGetGoogleEventById()
    {
        $method = self::$reflection->getMethod('getGoogleEventById');
        $method->setAccessible(true);
        $object = new GoogleSync();
        try {
            $method->invoke($object, null);
        } catch (Exception $e) {}
        
        $this->assertEquals(1, $e->getCode());
        $this->assertEquals('event ID is empty', $e->getMessage());
    }

    public function testGetMeetingByEventId()
    {
        $this->markTestIncomplete('TODO: Implement Tests');
        $state = new SuiteCRM\StateSaver();
        $state->pushTable('meetings');
        $state->pushTable('meetings_cstm');
        $state->pushTable('vcals');
        $state->pushTable('aod_indexevent');
        $state->pushTable('aod_index');
        $state->pushTable('tracker');

        $db = DBManagerFactory::getInstance();

        $method = self::$reflection->getMethod('getMeetingByEventId');
        $method->setAccessible(true);

        $object = new GoogleSync();

        // Create three meetings and save them to the DB for testing.
        $meeting1 = new Meeting();
        $meeting1->name = 'test1';
        $meeting1->assigned_user_id = '666';
        $meeting1->status = 'Not Held';
        $meeting1->type = 'Sugar';
        $meeting1->description = 'test description';
        $meeting1->duration_hours = 1;
        $meeting1->duration_minutes = 1;
        $meeting1->date_start = '2016-02-11 17:30:00';
        $meeting1->date_end = '2016-02-11 17:30:00';
        $meeting1_id = $meeting1->save();

        $meeting2 = new Meeting();
        $meeting2->name = 'test2';
        $meeting2->assigned_user_id = '666';
        $meeting2->status = 'Not Held';
        $meeting2->type = 'Sugar';
        $meeting2->description = 'test description';
        $meeting2->duration_hours = 1;
        $meeting2->duration_minutes = 1;
        $meeting2->date_start = '2016-02-11 17:30:00';
        $meeting2->date_end = '2016-02-11 17:30:00';
        $meeting2_id = $meeting2->save();

        $meeting3 = new Meeting();
        $meeting3->name = 'test3';
        $meeting3->assigned_user_id = '666';
        $meeting3->status = 'Not Held';
        $meeting3->type = 'Sugar';
        $meeting3->description = 'test description';
        $meeting3->duration_hours = 1;
        $meeting3->duration_minutes = 1;
        $meeting3->date_start = '2016-02-11 17:30:00';
        $meeting3->date_end = '2016-02-11 17:30:00';
        $meeting3_id = $meeting3->save();

        // Give meeting 1 a gsync_id
        $sql = "UPDATE meetings SET gsync_id = 'valid_gsync_id' WHERE id = '{$meeting1_id}'";
        $res1 = $db->query($sql);
        
        // Give meetings 2 and 3 a duplicate gsync_id
        $sql = "UPDATE meetings SET gsync_id = 'duplicate_gsync_id' WHERE id = '{$meeting2_id}' OR id = '{$meeting3_id}'";
        $res2 = $db->query($sql);
        
        $ret3 = $method->invoke($object, 'valid_gsync_id');

        $return = null;
        try {
            $method->invoke($object, 'duplicate_gsync_id');
        } catch (Exception $e11) {}

        $ret4 = $method->invoke($object, 'NOTHING_MATCHES');
        
        $state->popTable('meetings');
        $state->popTable('meetings_cstm');
        $state->popTable('vcals');
        $state->popTable('aod_indexevent');
        $state->popTable('aod_index');
        $state->popTable('tracker');

        $this->assertEquals(true, $res1);
        $this->assertEquals(true, $res2);
        $this->assertInstanceOf('Meeting', $ret3);
        $this->assertInstanceOf('SugarBean', $ret3);
        $this->assertEquals($meeting1_id, $ret3->id);
        $this->assertEquals('E_DbDataError', get_class($e11));
        $this->assertEquals('More than one meeting matches Google Id!', $e11->getMessage());
        $this->assertEquals(11, $e11->getCode());
        $this->assertEquals(null, $ret4);
    }

    public function testSetGService()
    {
        $method = self::$reflection->getMethod('setGService');
        $method->setAccessible(true);
        $object = new GoogleSync();
        $this->assertEquals(false, $method->invoke($object));
    }

    public function testPushEvent()
    {
        $method = self::$reflection->getMethod('pushEvent');
        $method->setAccessible(true);
        $object = new GoogleSync();

        set_error_handler(array($this, 'exception_error_handler'));

        try {
            $method->invoke($object, null, null);
        }
        catch (Error $e) {}
        catch (Exception $e) {}
        set_error_handler(null);
        $this->assertContains('GoogleSyncBase::pushEvent()', $e->getMessage());
    }

    public function testReturnExtendedProperties()
    {
        $method = self::$reflection->getMethod('returnExtendedProperties');
        $method->setAccessible(true);

        $object = new GoogleSync();

        // BEGIN: Create Google Event Object
        $Google_Event = new Google_Service_Calendar_Event();

        $Google_Event->setSummary('Unit Test Event');
        $Google_Event->setDescription('Unit Test Event');
        $Google_Event->setLocation('123 Seseme Street');

        // Set start date/time
        $startDateTime = new Google_Service_Calendar_EventDateTime;
        $startDateTime->setDateTime(date(DATE_ATOM, strtotime('2018-01-01 01:00:00 UTC')));
        $startDateTime->setTimeZone('Etc/UTC');
        $Google_Event->setStart($startDateTime);

        // Set end date/time
        $endDateTime = new Google_Service_Calendar_EventDateTime;
        $endDateTime->setDateTime(date(DATE_ATOM, strtotime('2018-01-01 02:00:00 UTC')));
        $endDateTime->setTimeZone('Etc/UTC');
        $Google_Event->setEnd($endDateTime);

        // Set extended properties
        $extendedProperties = new Google_Service_Calendar_EventExtendedProperties;
        $private = array();
        $private['suitecrm_id'] = 'INVALID';
        $private['suitecrm_type'] = 'INVALID';
        $private['remain_unchanged'] = 'VALID';
        $extendedProperties->setPrivate($private);
        $Google_Event->setExtendedProperties($extendedProperties);

        // Set popup reminder
        $reminders_remote = new Google_Service_Calendar_EventReminders;
        $reminders_remote->setUseDefault(false);
        $reminders_array = array();
        $reminder_remote = new Google_Service_Calendar_EventReminder;
        $reminder_remote->setMethod('popup');
        $reminder_remote->setMinutes('15');
        $reminders_array[] = $reminder_remote;
        $reminders_remote->setOverrides($reminders_array);
        $Google_Event->setReminders($reminders_remote);

        // END: Create Google Event Object

        // Create SuiteCRM Meeting Object
        $CRM_Meeting = new Meeting();

        $CRM_Meeting->id = 'FAKE_MEETING_ID';
        $CRM_Meeting->name = 'Unit Test Event';
        $CRM_Meeting->description = 'Unit Test Event';
        $CRM_Meeting->location = '123 Sesame Street';
        $CRM_Meeting->date_start = '2018-01-01 12:00:00';
        $CRM_Meeting->date_end = '2018-01-01 13:00:00';
        $CRM_Meeting->module_name = 'Meeting';

        $return = $method->invoke($object, $Google_Event, $CRM_Meeting);
        $returnPrivate = $return->getPrivate();

        $this->assertEquals('FAKE_MEETING_ID', $returnPrivate['suitecrm_id']);
        $this->assertEquals('Meeting', $returnPrivate['suitecrm_type']);
        $this->assertEquals('VALID', $returnPrivate['remain_unchanged']);
    }

    public function testPullEvent()
    {
        $method = self::$reflection->getMethod('pullEvent');
        $method->setAccessible(true);
        $object = new GoogleSync();

        set_error_handler(array($this, 'exception_error_handler'));

        try {
            $method->invoke($object, null, null);
        }
        catch (Error $e) {}
        catch (Exception $e) {}
        set_error_handler(null);
        $this->assertContains('GoogleSyncBase::pullEvent()', $e->getMessage());
    }

}
