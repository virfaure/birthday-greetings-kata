<?php

$service = new BirthdayService();
$service->sendGreetings(new XDate('2008/10/08'), 'localhost', 25);