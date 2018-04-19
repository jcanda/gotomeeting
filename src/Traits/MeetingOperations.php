<?php

namespace Jcanda\Gotomeeting\Traits;

use Jcanda\Gotomeeting\Entity\Meeting as MeetingEntity;

trait MeetingOperations
{
    /*
     * Gets upcoming meetings for the current authenticated organizer.
     * 
     */

    function getUpcomingMeetings()
    {
        $path = $this->getPathRelativeToOrganizer('upcomingMeetings');

        return $this->sendRequest('GET', $path, $parameters = null, $payload = null);
    }
    /*
     * Get historical meetings for the currently authenticated organizer that started within the specified date/time range.
     * Remark: Meetings which are still ongoing at the time of the request are NOT contained in the result array.
     */

    function getHistoricalMeetings($parameters = null)
    {
        $path = $this->getPathRelativeToOrganizer('historicalMeetings');

        return $this->sendRequest('GET', $path, $parameters, $payload = null);
    }
    /*
     * Retrieve information on a specific Meeting.
     */

    function getMeeting($meetingKey)
    {
        $path = sprintf('meetings/%s', $meetingKey);

        return $this->sendRequest('GET', $path);
    }
    /*
     * Creates a single meeting.
     * The response provides a string joinURL and meetingid a number format for the new meeting.
     * Once a meeting has been created with this method, you can accept registrations.
     */

    function createMeeting($payloadArray)
    {
        $path = sprintf('meetings');

        $meetingObject = new MeetingEntity($payloadArray);

        return $this->sendRequest('POST', $path, $parameters = null, $payload = $meetingObject);
    }
    /*
     * Updates a meeting. The call requires at least one of the parameters in the request body.
     * The request completely replaces the existing session, series, or sequence and so must include the full
     * definition of each as for the Create call.
     */

    function updateMeeting($meetingKey, $payloadArray)
    {
        $path = sprintf('meetings/%s', $meetingKey);

        $meetingObject = new MeetingEntity($payloadArray);

        return $this->sendRequest('PUT', $path, $parameters = null, $payload = $meetingObject);
    }
    /*
     * Delete a specific meeting.
     */

    function deleteMeeting($meetingKey)
    {
        $path = sprintf('meetings/%s', $meetingKey);

        return $this->sendRequest('DELETE', $path);
    }
    /*
     * Returns a host URL that can be used to start a meeting.
     * When this URL is opened in a web browser, the GoToMeeting client will be downloaded and launched and the meeting will start.
     * The end user is not required to login to a client.
     */

    function startMeeting($meetingKey)
    {
        $path = sprintf('meetings/%s/start', $meetingKey);
        return $this->sendRequest('GET', $path);
    }
}