<?php
namespace App\Controller\Component;

require_once("W:\domains\NotifierRESTAPI_Diplom/vendor/iCalendar/zapcallib.php");

use Cake\Controller\Component;
use ZapCalLib\ZCiCal;
use ZapCalLib\ZCiCalNode;
use ZapCalLib\ZCiCalDataNode;
use ZapCalLib\ZDateHelper;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\ArrayTransformerConfig;
use Cake\Datasource\ConnectionManager;


class ZapCalICalComponent extends Component
{
  public function initialize(array $config):void
  {
    parent::initialize($config);
    $this->connection = ConnectionManager::get('default');
  }

  public function getNextNotify($notificationId,$eventId):?string
  {
    $DBInfo = $this->connection
              ->execute('SELECT `notifications`.`id`, `event_id`, `notify_offset`, `notify_time`, `event_details_json`, `ical_raw`
                  FROM `notifications`
                  inner join `events` on `events`.`id` = `notifications`.`event_id`
                  where notifications.`id` = :nid',['nid' => $notificationId])
              ->fetchAll('assoc');

    $ntDate = date_create_from_format("Y-m-d H:i:s", $DBInfo[0]['notify_time'], new \DateTimeZone("UTC"));
    $noDate = date_create_from_format("Y-m-d H:i:s", $DBInfo[0]['notify_offset']);

    //echo $DBInfo[0]['notify_time'] . " " . $ntDate->format('Y-m-d H:i:s') . " ". $ntDate->getTimestamp() . "<br>";
    //echo $DBInfo[0]['notify_offset'] . " " . $noDate->format('Y-m-d H:i:s') . " ". $noDate->getTimestamp() . "<br>";
    //echo (new \DateTime("now"))->setTimezone(new \DateTimeZone("UTC"))->format('Y-m-d H:i:s') . "<br>";

    //$occurenceTime = strval(date_add($ntDate, (new \DateTime('0000-00-00'))->diff($noDate))->format('Y-m-d H:i:s'));
    $offset = (new \DateTime('0000-00-00'))->diff($noDate);
    $occurenceTime = date_add($ntDate, $offset)->getTimestamp();

    $nextOccurenceTime = $this->getOccurencesByRRule($occurenceTime, $DBInfo[0]['ical_raw']);

    echo "$occurenceTime ";
    print_r($nextOccurenceTime);

    if (count($nextOccurenceTime) < 1){
      return null;
    }

    //echo $nextOccurenceTime[1];

    $nextOccurenceTime = date_create_from_format("Y-m-d H:i:s", $nextOccurenceTime[0], new \DateTimeZone("UTC"));
    $nextNotifyTime = strval(date_sub($nextOccurenceTime, $offset)->format('Y-m-d H:i:s'));

    //echo " " . $nextOccurenceTime->format('Y-m-d H:i:s') . " " . $nextOccurenceTime->getTimestamp() . "<br>";
    //echo $nextNotifyTime;

    return $nextNotifyTime;
  }

  public function getOccurencesByRRule($dateStart, $ical, $limit = 200): ?array
  {
    // echo "<pre>";
    // echo $dateStart . "<br>";
    //$dateStart = ZDateHelper::toiCalDateTime($dateStart)."Z";
    $eventDateStart = substr($ical, strripos($ical, "DTSTART:")+8, 16);
    $rrule = stristr(stristr($ical, "FREQ"), "\r\nEND:V", true);
    //echo $dateStart . "<br>";

    $rule = new Rule($rrule, $eventDateStart);
    $transformer = new ArrayTransformer();
    $transformerConfig = new ArrayTransformerConfig();
    $transformerConfig->enableLastDayOfMonthFix();
    //$transformerConfig->setVirtualLimit($limit);
    $transformer->setConfig($transformerConfig);

    $constraint = new \Recurr\Transformer\Constraint\AfterConstraint((new \DateTime())->setTimestamp($dateStart), false);

    $reccol = (($transformer->transform($rule, $constraint))->toArray());
    $dates = [];

    foreach ($reccol as $key => $value) {
      $dates[$key] = $value->getStart()->format("Y-m-d H:i:s");
    }

    //echo $reccol[1]->getStart()->format("Y-m-d H:i:s") . '<br>';

    return $dates;
    //return null;
  }

  public function getRRuleFromObj($eventDetailsData): ?string
  {
    if(isset($obj->until) && isset($obj->count)){
      throw new \Exception("Until and Count at the same time");
    }
    $obj = $eventDetailsData->rrule;
    $rule = (new Rule())
      ->setStartDate($eventDetailsData->dateStart);

    if($obj->freq != "SECONDLY"){
      $rule->setFreq($obj->freq);
    }else {
      throw new \Exception("SECONDLY freq is unsupported");
    }

    if(isset($obj->until)){       $rule->setUntil((new \DateTime())->setTimestamp($obj->until)); }
    elseif(isset($obj->count)) {
      /*if($obj->count > 1){*/        $rule->setCount($obj->count); }
      // else {
      //   throw new \Exception("COUNT = 1 is unsupported");
      // }
    //}

    if(isset($eventDetailsData->dateEnd)){
      $rule->setEndDate($eventDetailsData->dateEnd);
    }
    if(isset($obj->interval)){    $rule->setInterval($obj->interval); }
    if(isset($obj->wkst)){        $rule->setWeekStart($obj->wkst); }
    if(isset($obj->bysecond)){
      //foreach ($obj->bysecond as $value) {
      //  if ($value < 0 || $value > 59){
          throw new \Exception("bySecond unsupported");
      //  }
      //}
      //$rule->setBySecond($obj->bysecond);
    }
    if(isset($obj->byminute)){
      foreach ($obj->byminute as $value) {
        if ($value < 0 || $value > 59){
          throw new \Exception("byMinute must be 0 to 59");
        }
      }
      $rule->setByMinute($obj->byminute);
    }
    if(isset($obj->byhour)){
      foreach ($obj->byhour as $value) {
        if ($value < 0 || $value > 23){
          throw new \Exception("byHour must be 0 to 23");
        }
      }
      $rule->setByHour($obj->byhour);
    }
    if(isset($obj->bymonthday)){
      foreach ($obj->bymonthday as $value) {
        if ($value < -31 || $value > 31 || $value == 0){
          throw new \Exception("bymonthday must be -31 to -1 or 1 to 31");
        }
      }
      $rule->setByMonthDay($obj->bymonthday);
    }
    if(isset($obj->byyearday)){
      if (in_array($rule->getFreq(), [1, 2, 3])){
        throw new \Exception("BYYEARDAY is not allowed in DAILY, WEEKLY or MONTHLY rules");
      }
      foreach ($obj->byyearday as $value) {
        if ($value < -366 || $value > 366 || $value == 0){
          throw new \Exception("byyearday must be 1 to 366 or -366 to -1");
        }
      }
      $rule->setByYearDay($obj->byyearday);
    }
    if(isset($obj->byweekno)){
      if($obj->freq != "YEARLY"){
        throw new \Exception("byweekno works only with YEARLY frequency"); //TODO not sure
      }
      foreach ($obj->byweekno as $value) {
        if ($value < -53 || $value > 53 || $value == 0){
          throw new \Exception("byweekno must be 1 to 53 or -53 to -1");
        }
      }
      $rule->setByWeekNumber($obj->byweekno);
    }
    if(isset($obj->bymonth)){
      foreach ($obj->bymonth as $value) {
        if ($value < 1 || $value > 12){
          throw new \Exception("bymonth must be 1 to 12");
        }
      }
      $rule->setByMonth($obj->bymonth);
    }
    if(isset($obj->bysetpos)){
      if(!(isset($obj->bysecond)||isset($obj->byminute)||isset($obj->byhour)||isset($obj->byday)||isset($obj->bymonthday)||isset($obj->byyearday)||isset($obj->byweekno)||isset($obj->bymonth))){
        throw new \Exception("bysetpos should be used only with any other BY*** rule");
      }
      foreach ($obj->bysetpos as $value) {
        if ($value < -366 || $value > 366 || $value == 0){
          throw new \Exception("bysetpos must be 1 to 366 or -366 to -1");
        }
      }
      $rule->setBySetPosition($obj->bysetpos);
    }
    if(isset($obj->byday)){
      $hasNumber = (preg_match("/\d/", $obj->byday[0]));
      foreach ($obj->byday as $value) {
        if (!preg_match("/\A((\+|-)?([0-9]){1,2})?((SU)|(MO)|(TU)|(WE)|(TH)|(FR)|(SA))\z/", $value)){
          throw new \Exception("byDay must be [[+/-]n]day");
        }else {
          $num = preg_replace("/\D+/", '', $value);
          if (($num != '')&&($num < 1 || $num > 53)){
            throw new \Exception("byDay number modifier must be 1 to 53");
          }
        }
        if (preg_match("/\d/", $value) != $hasNumber){
          throw new \Exception("byDay must not mix DAYS AND DAYS WITH MODIFIERS");
        }
        if (preg_match("/\d/", $value) && isset($obj->byweekno)){
          throw new \Exception("byDay must not be specified with a numeric value with the FREQ rule part set to YEARLY when BYWEEKNO is set");
        }
      }
      $rule->setByDay($obj->byday);
    }

    return $rule->getString();
  }

  public function createICalFromJson($id, $eventDetailsData): string
  {

    $ical = new ZCiCal();
    $event = new ZCiCalNode("VEVENT", $ical->curnode);

    $event->addNode(new ZCiCalDataNode("UID:" . $id));  //id of event
    $event->addNode(new ZCiCalDataNode("SUMMARY:" . $eventDetailsData->name));  //name of event
    if (isset($eventDetailsData->desc)){
      $event->addNode(new ZCiCalDataNode("DESCRIPTION:" . $eventDetailsData->desc));  //description of event
    }
    $event->addNode(new ZCiCalDataNode("DTSTART:" . ZDateHelper::toiCalDateTime($eventDetailsData->dateStart)."Z"));  //starttime
    if (isset($eventDetailsData->desc)){
      $event->addNode(new ZCiCalDataNode("DTEND:" . ZDateHelper::toiCalDateTime($eventDetailsData->dateEnd)."Z"));   //endtime >= starttime
    }
    if(isset($eventDetailsData->rrule)){
        $event->addNode(new ZCiCalDataNode("RRULE:" . $this->getRRuleFromObj($eventDetailsData)));  //reccurense rule of event
    }

    return $ical->export();
  }

  public function createSimpleICal(): string
  {
    $json_in_text = ' {
      "name": "wololo event",
      "event_details_json": {
        "name": "wololo event",
        "desc": "some unneeded description",
        "dateStart": "1588014186",
        "dateEnd": "1588014186",
        "rrule": {
          "freq": "SECONDLY",
          "until": "1588014188",
          "count": 0,
          "interval": 1,
          "bysecond": [
            0
          ],
          "byminute": [
            0
          ],
          "byhour": [
            0
          ],
          "byday": [
            "string"
          ],
          "bymonthday": [
            0
          ],
          "byyearday": [
            0
          ],
          "byweekno": [
            0
          ],
          "bymonth": [
            0
          ],
          "bysetpos": [
            0
          ],
          "wkst": "SU"
        }
      }
    }';

    $json = json_decode($json_in_text);

    $icalobj = new ZCiCal();

    $eventobj1 = new ZCiCalNode("VEVENT", $icalobj->curnode);
    $eventobj1->addNode(new ZCiCalDataNode("UID:" . "id_here"));  //id of event
    $eventobj1->addNode(new ZCiCalDataNode("SUMMARY:" . $json->event_details_json->name));  //name of event
    $eventobj1->addNode(new ZCiCalDataNode("DESCRIPTION:" . $json->event_details_json->desc));  //description of event
    $eventobj1->addNode(new ZCiCalDataNode("DTSTART:" . ZDateHelper::toiCalDateTime($json->event_details_json->dateStart)."Z"));  //starttime
    $eventobj1->addNode(new ZCiCalDataNode("DTEND:" . ZDateHelper::toiCalDateTime($json->event_details_json->dateEnd)."Z"));  //endtime >= starttime
    $eventobj1->addNode(new ZCiCalDataNode("RRULE:" . $this->getRRuleFromObj($json->event_details_json)));  //reccurense rule of event

    return $icalobj->export();
  }


}
