<?php
  $this->assign('title',"docs");
  $this->assign('css','<link rel="stylesheet" href="/assets/css/htmlstyle.css">');
 ?>

<!doctype html>
<html>
  <head>
    <title>Notifier RESTful API</title>
    <link rel="stylesheet" href="/assets/css/htmlstyle.css">
  </head>
  <body>
  <h1>Notifier RESTful API</h1>
    <div class="app-desc">This API allows you to notify your users about events.</div>
    <div class="app-desc">Version: 1.0.0</div>

  <h2><a name="__Methods">Methods</a></h2>
  [ Jump to <a href="#__Models">Models</a> ]

  <h3>Table of Contents </h3>
  <div class="method-summary"></div>
  <h4><a href="#Events">Events</a></h4>
  <ul>
  <li><a href="#closeEvent"><code><span class="http-method">delete</span> /api/events/{eventid}</code></a></li>
  <li><a href="#createEvent"><code><span class="http-method">post</span> /api/events/new</code></a></li>
  <li><a href="#editEvent"><code><span class="http-method">put</span> /api/events/{eventid}</code></a></li>
  <li><a href="#getEvent"><code><span class="http-method">get</span> /api/events/{eventid}</code></a></li>
  <li><a href="#getEventNotifications"><code><span class="http-method">get</span> /api/events/{eventid}/notifications</code></a></li>
  </ul>
  <h4><a href="#Notifications">Notifications</a></h4>
  <ul>
  <li><a href="#createNotification"><code><span class="http-method">post</span> /api/notifications/new</code></a></li>
  <li><a href="#deleteNotification"><code><span class="http-method">delete</span> /api/notifications/{notificationid}</code></a></li>
  <li><a href="#editNotification"><code><span class="http-method">put</span> /api/notifications/{notificationid}</code></a></li>
  <li><a href="#getEventNotifications"><code><span class="http-method">get</span> /api/events/{eventid}/notifications</code></a></li>
  <li><a href="#getNotification"><code><span class="http-method">get</span> /api/notifications/{notificationid}</code></a></li>
  </ul>

  <h1><a name="Events">Events</a></h1>
  <div class="method"><a name="closeEvent"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="delete"><code class="huge"><span class="http-method">delete</span> /api/events/{eventid}</code></pre></div>
    <div class="method-summary">delete event object (<span class="nickname">closeEvent</span>)</div>
    <div class="method-notes">delete specific event object by id, this id becomes unavailable</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">eventid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of event to delete format: varchar(40)</div>    </div>  <!-- field-items -->







    <!--Todo: process Response Object and its headers, schema, examples -->



    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">204</h4>
    success
        <a href="#"></a>
    <h4 class="field-label">404</h4>
    event not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    event was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="createEvent"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="post"><code class="huge"><span class="http-method">post</span> /api/events/new</code></pre></div>
    <div class="method-summary">create new event object (<span class="nickname">createEvent</span>)</div>
    <div class="method-notes">you describe event in request body and get new event object as response</div>


    <h3 class="field-label">Consumes</h3>
    This API call consumes the following media types via the <span class="header">Content-Type</span> request header:
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Request body</h3>
    <div class="field-items">
      <div class="param">body <a href="#CreateEventData">CreateEventData</a> (required)</div>

            <div class="param-desc"><span class="param-type">Body Parameter</span> &mdash; General information about event </div>
                </div>  <!-- field-items -->




    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      <a href="#EventObject">EventObject</a>

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>{
  "event_details_json" : {
    "rrule" : {
      "bymonthday" : [ 2, 2 ],
      "bysetpos" : [ 2, 2 ],
      "wkst" : "SU",
      "freq" : "SECONDLY",
      "count" : 0,
      "bysecond" : [ 1, 1 ],
      "byminute" : [ 5, 5 ],
      "byhour" : [ 5, 5 ],
      "byyearday" : [ 7, 7 ],
      "byday" : [ "byday", "byday" ],
      "byweekno" : [ 9, 9 ],
      "bymonth" : [ 3, 3 ],
      "until" : "until",
      "interval" : 6
    },
    "dateStart" : "dateStart",
    "name" : "name",
    "dateEnd" : "dateEnd",
    "desc" : "desc"
  },
  "ical_raw" : "ical_raw",
  "name" : "name",
  "id" : "id",
  "status" : 4
}</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">201</h4>
    new object successfully created
        <a href="#EventObject">EventObject</a>
    <h4 class="field-label">400</h4>
    creation error occured
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="editEvent"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="put"><code class="huge"><span class="http-method">put</span> /api/events/{eventid}</code></pre></div>
    <div class="method-summary">modify event object (<span class="nickname">editEvent</span>)</div>
    <div class="method-notes">edit any field of specific event object and get modified event object as response</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">eventid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of event to update format: varchar(40)</div>    </div>  <!-- field-items -->

    <h3 class="field-label">Consumes</h3>
    This API call consumes the following media types via the <span class="header">Content-Type</span> request header:
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Request body</h3>
    <div class="field-items">
      <div class="param">body <a href="#CreateEventData">CreateEventData</a> (required)</div>

            <div class="param-desc"><span class="param-type">Body Parameter</span> &mdash; New general information about event </div>
                </div>  <!-- field-items -->




    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      <a href="#EventObject">EventObject</a>

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>{
  "event_details_json" : {
    "rrule" : {
      "bymonthday" : [ 2, 2 ],
      "bysetpos" : [ 2, 2 ],
      "wkst" : "SU",
      "freq" : "SECONDLY",
      "count" : 0,
      "bysecond" : [ 1, 1 ],
      "byminute" : [ 5, 5 ],
      "byhour" : [ 5, 5 ],
      "byyearday" : [ 7, 7 ],
      "byday" : [ "byday", "byday" ],
      "byweekno" : [ 9, 9 ],
      "bymonth" : [ 3, 3 ],
      "until" : "until",
      "interval" : 6
    },
    "dateStart" : "dateStart",
    "name" : "name",
    "dateEnd" : "dateEnd",
    "desc" : "desc"
  },
  "ical_raw" : "ical_raw",
  "name" : "name",
  "id" : "id",
  "status" : 4
}</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">200</h4>
    success
        <a href="#EventObject">EventObject</a>
    <h4 class="field-label">400</h4>
    update event error occured
        <a href="#"></a>
    <h4 class="field-label">404</h4>
    event not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    event was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="getEvent"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="get"><code class="huge"><span class="http-method">get</span> /api/events/{eventid}</code></pre></div>
    <div class="method-summary">get event object (<span class="nickname">getEvent</span>)</div>
    <div class="method-notes">you get specific event object by id</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">eventid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of event to return format: varchar(40)</div>    </div>  <!-- field-items -->






    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      <a href="#EventObject">EventObject</a>

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>{
  "event_details_json" : {
    "rrule" : {
      "bymonthday" : [ 2, 2 ],
      "bysetpos" : [ 2, 2 ],
      "wkst" : "SU",
      "freq" : "SECONDLY",
      "count" : 0,
      "bysecond" : [ 1, 1 ],
      "byminute" : [ 5, 5 ],
      "byhour" : [ 5, 5 ],
      "byyearday" : [ 7, 7 ],
      "byday" : [ "byday", "byday" ],
      "byweekno" : [ 9, 9 ],
      "bymonth" : [ 3, 3 ],
      "until" : "until",
      "interval" : 6
    },
    "dateStart" : "dateStart",
    "name" : "name",
    "dateEnd" : "dateEnd",
    "desc" : "desc"
  },
  "ical_raw" : "ical_raw",
  "name" : "name",
  "id" : "id",
  "status" : 4
}</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">200</h4>
    success
        <a href="#EventObject">EventObject</a>
    <h4 class="field-label">404</h4>
    event not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    event was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="getEventNotifications"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="get"><code class="huge"><span class="http-method">get</span> /api/events/{eventid}/notifications</code></pre></div>
    <div class="method-summary">get notifications related to  event object (<span class="nickname">getEventNotifications</span>)</div>
    <div class="method-notes">get array of all notification objects related to specific event object</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">eventid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of event format: varchar(40)</div>    </div>  <!-- field-items -->






    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      array[<a href="#NotificationObject">NotificationObject</a>]

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>[ {
  "notify_time" : "notify_time",
  "delivery_types" : [ {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  }, {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  } ],
  "notify_offset" : "2000-01-23T04:56:07.000+00:00",
  "id" : "id",
  "event" : {
    "event_details_json" : {
      "rrule" : {
        "bymonthday" : [ 2, 2 ],
        "bysetpos" : [ 2, 2 ],
        "wkst" : "SU",
        "freq" : "SECONDLY",
        "count" : 0,
        "bysecond" : [ 1, 1 ],
        "byminute" : [ 5, 5 ],
        "byhour" : [ 5, 5 ],
        "byyearday" : [ 7, 7 ],
        "byday" : [ "byday", "byday" ],
        "byweekno" : [ 9, 9 ],
        "bymonth" : [ 3, 3 ],
        "until" : "until",
        "interval" : 6
      },
      "dateStart" : "dateStart",
      "name" : "name",
      "dateEnd" : "dateEnd",
      "desc" : "desc"
    },
    "ical_raw" : "ical_raw",
    "name" : "name",
    "id" : "id",
    "status" : 4
  },
  "title" : "title",
  "status" : 0
}, {
  "notify_time" : "notify_time",
  "delivery_types" : [ {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  }, {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  } ],
  "notify_offset" : "2000-01-23T04:56:07.000+00:00",
  "id" : "id",
  "event" : {
    "event_details_json" : {
      "rrule" : {
        "bymonthday" : [ 2, 2 ],
        "bysetpos" : [ 2, 2 ],
        "wkst" : "SU",
        "freq" : "SECONDLY",
        "count" : 0,
        "bysecond" : [ 1, 1 ],
        "byminute" : [ 5, 5 ],
        "byhour" : [ 5, 5 ],
        "byyearday" : [ 7, 7 ],
        "byday" : [ "byday", "byday" ],
        "byweekno" : [ 9, 9 ],
        "bymonth" : [ 3, 3 ],
        "until" : "until",
        "interval" : 6
      },
      "dateStart" : "dateStart",
      "name" : "name",
      "dateEnd" : "dateEnd",
      "desc" : "desc"
    },
    "ical_raw" : "ical_raw",
    "name" : "name",
    "id" : "id",
    "status" : 4
  },
  "title" : "title",
  "status" : 0
} ]</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">200</h4>
    success

    <h4 class="field-label">404</h4>
    event not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    event was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <h1><a name="Notifications">Notifications</a></h1>
  <div class="method"><a name="createNotification"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="post"><code class="huge"><span class="http-method">post</span> /api/notifications/new</code></pre></div>
    <div class="method-summary">create new notification object (<span class="nickname">createNotification</span>)</div>
    <div class="method-notes">you describe notification in request body and get new notification object as response</div>


    <h3 class="field-label">Consumes</h3>
    This API call consumes the following media types via the <span class="header">Content-Type</span> request header:
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Request body</h3>
    <div class="field-items">
      <div class="param">body <a href="#CreateNotificationData">CreateNotificationData</a> (required)</div>

            <div class="param-desc"><span class="param-type">Body Parameter</span> &mdash; General information about notification </div>
                </div>  <!-- field-items -->




    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      <a href="#NotificationObject">NotificationObject</a>

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>{
  "notify_time" : "notify_time",
  "delivery_types" : [ {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  }, {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  } ],
  "notify_offset" : "2000-01-23T04:56:07.000+00:00",
  "id" : "id",
  "event" : {
    "event_details_json" : {
      "rrule" : {
        "bymonthday" : [ 2, 2 ],
        "bysetpos" : [ 2, 2 ],
        "wkst" : "SU",
        "freq" : "SECONDLY",
        "count" : 0,
        "bysecond" : [ 1, 1 ],
        "byminute" : [ 5, 5 ],
        "byhour" : [ 5, 5 ],
        "byyearday" : [ 7, 7 ],
        "byday" : [ "byday", "byday" ],
        "byweekno" : [ 9, 9 ],
        "bymonth" : [ 3, 3 ],
        "until" : "until",
        "interval" : 6
      },
      "dateStart" : "dateStart",
      "name" : "name",
      "dateEnd" : "dateEnd",
      "desc" : "desc"
    },
    "ical_raw" : "ical_raw",
    "name" : "name",
    "id" : "id",
    "status" : 4
  },
  "title" : "title",
  "status" : 0
}</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">201</h4>
    new object successfully created
        <a href="#NotificationObject">NotificationObject</a>
    <h4 class="field-label">400</h4>
    creation error occured
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="deleteNotification"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="delete"><code class="huge"><span class="http-method">delete</span> /api/notifications/{notificationid}</code></pre></div>
    <div class="method-summary">delete notification object (<span class="nickname">deleteNotification</span>)</div>
    <div class="method-notes">delete specofic notification object by id, this id becomes unavailable</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">notificationid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of notification to delete format: varchar(40)</div>    </div>  <!-- field-items -->







    <!--Todo: process Response Object and its headers, schema, examples -->



    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">204</h4>
    success
        <a href="#"></a>
    <h4 class="field-label">404</h4>
    notification not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    notification was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="editNotification"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="put"><code class="huge"><span class="http-method">put</span> /api/notifications/{notificationid}</code></pre></div>
    <div class="method-summary">modify notification object (<span class="nickname">editNotification</span>)</div>
    <div class="method-notes">edit any field of specific notification object and get modified notification object as response</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">notificationid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of notification to update format: varchar(40)</div>    </div>  <!-- field-items -->

    <h3 class="field-label">Consumes</h3>
    This API call consumes the following media types via the <span class="header">Content-Type</span> request header:
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Request body</h3>
    <div class="field-items">
      <div class="param">body <a href="#CreateNotificationData">CreateNotificationData</a> (required)</div>

            <div class="param-desc"><span class="param-type">Body Parameter</span> &mdash; New general information about notification </div>
                </div>  <!-- field-items -->




    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      <a href="#NotificationObject">NotificationObject</a>

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>{
  "notify_time" : "notify_time",
  "delivery_types" : [ {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  }, {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  } ],
  "notify_offset" : "2000-01-23T04:56:07.000+00:00",
  "id" : "id",
  "event" : {
    "event_details_json" : {
      "rrule" : {
        "bymonthday" : [ 2, 2 ],
        "bysetpos" : [ 2, 2 ],
        "wkst" : "SU",
        "freq" : "SECONDLY",
        "count" : 0,
        "bysecond" : [ 1, 1 ],
        "byminute" : [ 5, 5 ],
        "byhour" : [ 5, 5 ],
        "byyearday" : [ 7, 7 ],
        "byday" : [ "byday", "byday" ],
        "byweekno" : [ 9, 9 ],
        "bymonth" : [ 3, 3 ],
        "until" : "until",
        "interval" : 6
      },
      "dateStart" : "dateStart",
      "name" : "name",
      "dateEnd" : "dateEnd",
      "desc" : "desc"
    },
    "ical_raw" : "ical_raw",
    "name" : "name",
    "id" : "id",
    "status" : 4
  },
  "title" : "title",
  "status" : 0
}</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">200</h4>
    success
        <a href="#NotificationObject">NotificationObject</a>
    <h4 class="field-label">404</h4>
    notification not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    notification was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="getEventNotifications"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="get"><code class="huge"><span class="http-method">get</span> /api/events/{eventid}/notifications</code></pre></div>
    <div class="method-summary">get notifications related to  event object (<span class="nickname">getEventNotifications</span>)</div>
    <div class="method-notes">get array of all notification objects related to specific event object</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">eventid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of event format: varchar(40)</div>    </div>  <!-- field-items -->






    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      array[<a href="#NotificationObject">NotificationObject</a>]

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>[ {
  "notify_time" : "notify_time",
  "delivery_types" : [ {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  }, {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  } ],
  "notify_offset" : "2000-01-23T04:56:07.000+00:00",
  "id" : "id",
  "event" : {
    "event_details_json" : {
      "rrule" : {
        "bymonthday" : [ 2, 2 ],
        "bysetpos" : [ 2, 2 ],
        "wkst" : "SU",
        "freq" : "SECONDLY",
        "count" : 0,
        "bysecond" : [ 1, 1 ],
        "byminute" : [ 5, 5 ],
        "byhour" : [ 5, 5 ],
        "byyearday" : [ 7, 7 ],
        "byday" : [ "byday", "byday" ],
        "byweekno" : [ 9, 9 ],
        "bymonth" : [ 3, 3 ],
        "until" : "until",
        "interval" : 6
      },
      "dateStart" : "dateStart",
      "name" : "name",
      "dateEnd" : "dateEnd",
      "desc" : "desc"
    },
    "ical_raw" : "ical_raw",
    "name" : "name",
    "id" : "id",
    "status" : 4
  },
  "title" : "title",
  "status" : 0
}, {
  "notify_time" : "notify_time",
  "delivery_types" : [ {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  }, {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  } ],
  "notify_offset" : "2000-01-23T04:56:07.000+00:00",
  "id" : "id",
  "event" : {
    "event_details_json" : {
      "rrule" : {
        "bymonthday" : [ 2, 2 ],
        "bysetpos" : [ 2, 2 ],
        "wkst" : "SU",
        "freq" : "SECONDLY",
        "count" : 0,
        "bysecond" : [ 1, 1 ],
        "byminute" : [ 5, 5 ],
        "byhour" : [ 5, 5 ],
        "byyearday" : [ 7, 7 ],
        "byday" : [ "byday", "byday" ],
        "byweekno" : [ 9, 9 ],
        "bymonth" : [ 3, 3 ],
        "until" : "until",
        "interval" : 6
      },
      "dateStart" : "dateStart",
      "name" : "name",
      "dateEnd" : "dateEnd",
      "desc" : "desc"
    },
    "ical_raw" : "ical_raw",
    "name" : "name",
    "id" : "id",
    "status" : 4
  },
  "title" : "title",
  "status" : 0
} ]</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">200</h4>
    success

    <h4 class="field-label">404</h4>
    event not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    event was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>
  <div class="method"><a name="getNotification"></a>
    <div class="method-path">
    <a class="up" href="#__Methods">Up</a>
    <pre class="get"><code class="huge"><span class="http-method">get</span> /api/notifications/{notificationid}</code></pre></div>
    <div class="method-summary">get notification object (<span class="nickname">getNotification</span>)</div>
    <div class="method-notes">you get specific notification object by id</div>

    <h3 class="field-label">Path parameters</h3>
    <div class="field-items">
      <div class="param">notificationid (required)</div>

            <div class="param-desc"><span class="param-type">Path Parameter</span> &mdash; ID of notification to return format: varchar(40)</div>    </div>  <!-- field-items -->






    <h3 class="field-label">Return type</h3>
    <div class="return-type">
      <a href="#NotificationObject">NotificationObject</a>

    </div>

    <!--Todo: process Response Object and its headers, schema, examples -->

    <h3 class="field-label">Example data</h3>
    <div class="example-data-content-type">Content-Type: application/json</div>
    <pre class="example"><code>{
  "notify_time" : "notify_time",
  "delivery_types" : [ {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  }, {
    "name" : "name",
    "destination" : "destination",
    "checked" : true,
    "text" : "text"
  } ],
  "notify_offset" : "2000-01-23T04:56:07.000+00:00",
  "id" : "id",
  "event" : {
    "event_details_json" : {
      "rrule" : {
        "bymonthday" : [ 2, 2 ],
        "bysetpos" : [ 2, 2 ],
        "wkst" : "SU",
        "freq" : "SECONDLY",
        "count" : 0,
        "bysecond" : [ 1, 1 ],
        "byminute" : [ 5, 5 ],
        "byhour" : [ 5, 5 ],
        "byyearday" : [ 7, 7 ],
        "byday" : [ "byday", "byday" ],
        "byweekno" : [ 9, 9 ],
        "bymonth" : [ 3, 3 ],
        "until" : "until",
        "interval" : 6
      },
      "dateStart" : "dateStart",
      "name" : "name",
      "dateEnd" : "dateEnd",
      "desc" : "desc"
    },
    "ical_raw" : "ical_raw",
    "name" : "name",
    "id" : "id",
    "status" : 4
  },
  "title" : "title",
  "status" : 0
}</code></pre>

    <h3 class="field-label">Produces</h3>
    This API call produces the following media types according to the <span class="header">Accept</span> request header;
    the media type will be conveyed by the <span class="header">Content-Type</span> response header.
    <ul>
      <li><code>application/json</code></li>
    </ul>

    <h3 class="field-label">Responses</h3>
    <h4 class="field-label">200</h4>
    success
        <a href="#NotificationObject">NotificationObject</a>
    <h4 class="field-label">404</h4>
    notification not found by this id
        <a href="#"></a>
    <h4 class="field-label">410</h4>
    notification was deleted, this id is not available anymore
        <a href="#"></a>
  </div> <!-- method -->
  <hr/>

  <h2><a name="__Models">Models</a></h2>
  [ Jump to <a href="#__Methods">Methods</a> ]

  <h3>Table of Contents</h3>
  <ol>
    <li><a href="#CreateEventData"><code>CreateEventData</code></a></li>
    <li><a href="#CreateNotificationData"><code>CreateNotificationData</code></a></li>
    <li><a href="#DeliveryTypeData"><code>DeliveryTypeData</code></a></li>
    <li><a href="#EventDetailsData"><code>EventDetailsData</code></a></li>
    <li><a href="#EventObject"><code>EventObject</code></a></li>
    <li><a href="#NotificationObject"><code>NotificationObject</code></a></li>
    <li><a href="#RRule"><code>RRule</code></a></li>
  </ol>

  <div class="model">
    <h3><a name="CreateEventData"><code>CreateEventData</code></a> <a class="up" href="#__Models">Up</a></h3>

    <div class="field-items">
      <div class="param">name (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(80)</div>
<div class="param">event_details_json (optional)</div><div class="param-desc"><span class="param-type"><a href="#EventDetailsData">EventDetailsData</a></span>  </div>
    </div>  <!-- field-items -->
  </div>
  <div class="model">
    <h3><a name="CreateNotificationData"><code>CreateNotificationData</code></a> <a class="up" href="#__Models">Up</a></h3>

    <div class="field-items">
      <div class="param">event_id (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(40)</div>
<div class="param">notify_offset (optional)</div><div class="param-desc"><span class="param-type"><a href="#DateTime">Date</a></span>  format: date-time</div>
<div class="param">title (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(80)</div>
<div class="param">delivery_types (optional)</div><div class="param-desc"><span class="param-type"><a href="#DeliveryTypeData">array[DeliveryTypeData]</a></span>  </div>
    </div>  <!-- field-items -->
  </div>
  <div class="model">
    <h3><a name="DeliveryTypeData"><code>DeliveryTypeData</code></a> <a class="up" href="#__Models">Up</a></h3>

    <div class="field-items">
      <div class="param">name (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(45)</div>
<div class="param">checked (optional)</div><div class="param-desc"><span class="param-type"><a href="#boolean">Boolean</a></span>  </div>
<div class="param">destination (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span> mail adress or phone number etc </div>
<div class="param">text (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span> text of nottification based on delivery type </div>
    </div>  <!-- field-items -->
  </div>
  <div class="model">
    <h3><a name="EventDetailsData"><code>EventDetailsData</code></a> <a class="up" href="#__Models">Up</a></h3>
    <div class='model-description'>TODO</div>
    <div class="field-items">
      <div class="param">name (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  </div>
<div class="param">desc (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  </div>
<div class="param">dateStart (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: timestamp</div>
<div class="param">dateEnd (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: timestamp</div>
<div class="param">rrule (optional)</div><div class="param-desc"><span class="param-type"><a href="#RRule">RRule</a></span>  </div>
    </div>  <!-- field-items -->
  </div>
  <div class="model">
    <h3><a name="EventObject"><code>EventObject</code></a> <a class="up" href="#__Models">Up</a></h3>

    <div class="field-items">
      <div class="param">id (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(40)</div>
<div class="param">name (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(80)</div>
<div class="param">event_details_json (optional)</div><div class="param-desc"><span class="param-type"><a href="#EventDetailsData">EventDetailsData</a></span>  </div>
<div class="param">ical_raw (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span> описание события в формате ical format: ical</div>
<div class="param">status (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">Integer</a></span> status of event object format: Int32</div>
        <div class="param-enum-header">Enum:</div>
        <div class="param-enum">0</div><div class="param-enum">1</div>
    </div>  <!-- field-items -->
  </div>
  <div class="model">
    <h3><a name="NotificationObject"><code>NotificationObject</code></a> <a class="up" href="#__Models">Up</a></h3>

    <div class="field-items">
      <div class="param">id (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(40)</div>
<div class="param">event (optional)</div><div class="param-desc"><span class="param-type"><a href="#EventObject">EventObject</a></span>  </div>
<div class="param">notify_offset (optional)</div><div class="param-desc"><span class="param-type"><a href="#DateTime">Date</a></span>  format: date-time</div>
<div class="param">notify_time (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: timestamp</div>
<div class="param">title (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: varchar(80)</div>
<div class="param">delivery_types (optional)</div><div class="param-desc"><span class="param-type"><a href="#DeliveryTypeData">array[DeliveryTypeData]</a></span>  </div>
<div class="param">status (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">Integer</a></span> status of notification object format: Int32</div>
        <div class="param-enum-header">Enum:</div>
        <div class="param-enum">0</div><div class="param-enum">1</div><div class="param-enum">2</div>
    </div>  <!-- field-items -->
  </div>
  <div class="model">
    <h3><a name="RRule"><code>RRule</code></a> <a class="up" href="#__Models">Up</a></h3>

    <div class="field-items">
      <div class="param">freq (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  </div>
        <div class="param-enum-header">Enum:</div>
        <div class="param-enum">SECONDLY</div><div class="param-enum">MINUTELY</div><div class="param-enum">HOURLY</div><div class="param-enum">DAILY</div><div class="param-enum">WEEKLY</div><div class="param-enum">MONTHLY</div><div class="param-enum">YEARLY</div>
<div class="param">until (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  format: timestamp</div>
<div class="param">count (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">Integer</a></span>  </div>
<div class="param">interval (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">Integer</a></span>  </div>
<div class="param">bysecond (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: 0..59</div>
<div class="param">byminute (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: 0..59</div>
<div class="param">byhour (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: 0..23</div>
<div class="param">byday (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">array[String]</a></span>  format: [[+/-]1..53]MO/TU/WE/TH/FR/SA/SU</div>
<div class="param">bymonthday (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: +/- 1..31</div>
<div class="param">byyearday (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: +/- 1..366</div>
<div class="param">byweekno (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: +/- 1..53</div>
<div class="param">bymonth (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: 1..12</div>
<div class="param">bysetpos (optional)</div><div class="param-desc"><span class="param-type"><a href="#integer">array[Integer]</a></span>  format: +/- 1..366</div>
<div class="param">wkst (optional)</div><div class="param-desc"><span class="param-type"><a href="#string">String</a></span>  </div>
        <div class="param-enum-header">Enum:</div>
        <div class="param-enum">SU</div><div class="param-enum">MO</div><div class="param-enum">TU</div><div class="param-enum">WE</div><div class="param-enum">TH</div><div class="param-enum">FR</div><div class="param-enum">SA</div>
    </div>  <!-- field-items -->
  </div>
  </body>
</html>
