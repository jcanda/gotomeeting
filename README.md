# GotoMeeting API Client Library

This package is a GotoMeeting API service to interact with the [Logmein GotoMeeting API](https://goto-developer.logmeininc.com/content/gotomeeting-api-reference).

## Contributions and Bug

Please create a pull request for any changes, update or bugs. Thanks!


## Config

Before you can use the service provider you have configure it. You can create and App with API access keys here: [GotoMeeting Developer portal](https://goto-developer.logmeininc.com). Look for the `My Apps` menu.

Note that you need to have an active or trial account for the API to function properly. Just dev credentials alone might not work.

The provider currently only support `Direct` authentication. An OAuth2 authentication will be added at a later stage.

```
GOTO_DIRECT_USER=test@test.com
GOTO_CONSUMER_SECRET=testpassword
GOTO_CONSUMER_KEY=123123123123
```


## Authentication Token Caching

The authentication token is cached! So caching the token results in one less round trip to GotoMeeting servers. You can use the following mehtod to refresh the cached authentication token:

```php

//pass in "true" to force authentication token refresh
$gotoResponse = GotoMeeting::state(false);

//or explicity call the refreshToken method
$gotoResponse = GotoMeeting::refreshToken();
```

## Usage

When using this package you'll notice it is closely aligned to the API documentation schemas etc. as can be found here: [GotoMeeting API Reference](https://goto-developer.logmeininc.com/content/gotomeeting-api-reference). It is recommended that you also keep an eye on the official API reference while implementing with this package.

## Responses

The package automatically returns the response body so you can just access the expected results for example:

```php
//create a meeting
$gotoResponse = GotoMeeting::createMeeting($eventParams);
```

the API will send back a JSON response which will look like this:

```json
[
    {
        "meetingid": "4255157664015486220"
    }
]
```

You can now on $gotoResponse directly access the properties in the response object:

```php
$gotoResponse->meetingid
```

## Exception Handling and Logging

When using the package methods it is recommended to call them within a `try`, `catch` block. For example:
 
```php
try {
    $gotoResponse = GotoMeeting::createMeeting($eventParams);
} catch (GotoException $e) {
    //do something, go somewhere or notifify someone
}
``` 

The package will automatically log most errors for you to the Laravel log file, so you don't need to log them again. For example:

```php
[2017-09-21 00:14:38] local.ERROR: GOTOMEETING: DELETE - Not Found (404): Meeting with specified key does not exist.
```

## GotoMeeting Resources

### getUpcomingMeetings

Returns the list of all upcoming Meetings

```php
GotoMeeting::getUpcomingMeetings();
```

### getAllMeetings

Returns the list of all upcoming Meetings

```php
$parameters = [
    'fromTime' => Carbon::now()->subYears(5)->toW3cString(), //"2017-06-01T00:00:00Z",
    'toTime'   => Carbon::now()->addYears(5)->toW3cString(),
];

GotoMeeting::getAllMeetings($parameters);
```

### createMeeting

Create a Meeting - date format standard: W3C - ISO 8601

```php
//Some of the body parameters are set per default but can explicitly be overridden.
$eventParams = [
    'subject'             => 'XXXXX Test XXXXX*',   //required
    'description'         => 'Test Description*',   //required
    'startTime'           => Carbon::now()->addDays(2)->toW3cString(),              //required  eg "2016-03-23T19:00:00Z"
    'endTime'             => Carbon::now()->addDays(2)->addHour()->toW3cString(),   //require eg "2016-03-23T20:00:00Z"
    'timeZone'            => 'Europe/Berlin',   //if not given the config('app.timezone) from the framework will be used
    'type'                => 'single_session',  //if not given the default is single_session
    'isPasswordProtected' => false,             //if not given the default is false
];

$gotoResponse = GotoMeeting::createMeeting($eventParams);
```

### updateMeeting

Update a Meeting - date format standard: W3C - ISO 8601, method returns `true` or `false`

```php
//Some of the body parameters are set per default but can explicitly be overridden.
$eventParams = [
    'subject'             => 'XXXXX UPDATE Test2 XXXXX**',  //required
    'startTime'           => Carbon::now()->addDays(3)->toW3cString(),              //required  eg "2016-03-23T19:00:00Z"
    'endTime'             => Carbon::now()->addDays(3)->addHour()->toW3cString(),   //require eg "2016-03-23T20:00:00Z"
    "conferencecallinfo"  => 'VoIP',			// if not given the default is VoIP
    'timeZone'            => 'America/New_York',    //if not given the config('app.timezone) from the framework will be used
    'meetingtype'         => 'scheduled',      //if not given the default is scheduled
    'passwordrequired' => false,                  //if not given the default is false
];

$gotoResponse = GotoMeeting::updateMeeting($meetingKey, $eventParams, $sendNotification = true);
```

### getMeeting

Return a specific Meeting by meetingKey

```php
GotoMeeting::getMeeting($meetingKey);
```

#### deleteMeeting

Delete a specific Meeting by meetingKey, method returns `true` or `false`

```php
GotoMeeting::deleteMeeting($meetingKey);
```

Your contribution or bug fixes are welcome!

Enjoy!

JCanda
