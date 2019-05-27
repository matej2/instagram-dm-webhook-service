# instagram-messenger-bot

## Introduction

When you are running your business is it important to be able to get in contact with customer when he or she is looking to buy. If you miss this opportunity, you might lose a costumer. Facebook has started implementing messenger bots, Instagram, however, still does not have this feature. The fact that you cannot check messages on web application tells that such bot will provide help in managing user relations.


We would create Instagram messenger bot api and service that will respond to questions. This service will not use official api as it is not possible to collect data on messages and to reply to incoming messages. Instead it will use unofficial api / library (for ex: https://github.com/LevPasha/Instagram-API-python).

Responding to DMs would defined by regex selectors. For example:

```
match([regex1,regex2,regex3]).do(function1, function2);
...
```

Basic business info could be stored in JSON files:

```
{
  "name": "John Doe's bakery",
  "address": "790 Bushwick Ave Brooklyn, NY 11221, USA",
  "contact_name": "John Doe",
  "contact_phone": "123-456-789"
  "opening_hours_from": "08.00",
  "opening_hours_to": "16.00",
  "parking_place": "no",
  "delivery_available": "yes"
  ...
}
```

This data can then be used in methods and can be easily edited by anyone.

We plan to implement this in NodeJS or PHP. Later on (in second version) we plan to also implement desktop app in VueJS (Or ReactJS). Also we plan to implement docker files for easier development.


# Alternative 2: We create connection between FB messenger and IG messenger

We create connection between FB messenger and IG messenger. Here we would use unofficial facebook messenger api and our Instagram message unofficial API for accessing IG DMs. Here, the flow would go like this: on IG, the message is read. This message then gets send to FB page. The reply from page is then sent back to sender trought IG messenger. Here the question is how can we get reply from Facebook page.


# Alternative 3: We create connector between IG messenger and thrid-party services

We create service that will use webhooks to communicate with third-party services.