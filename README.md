# instagram-chat-bot

> This will not be autorespond / autosend service. Similar to Facebook chat bot services, this service will use webhooks to communicate with external services to get response based on received message.


This is a draft for instagram chat bot service.

We would create Instagram messenger bot api and service that will respond to questions. This service will not use official api as it is not possible to collect messages data and to reply to incoming messages. Instead it will use unofficial api / library (for ex: https://github.com/LevPasha/Instagram-API-python).


## Webhook service

Service  will use webhooks to communicate with third-party services. In this case, serice would periodically check for new messages (every 15 min or more) and would send webhook as specified in settings. Here we can support only text-based communication between IG DM and third-party service.

## Basic bot service

Basic bot service will use Facebook business basic information to respond to messages:

```
{
  "name": "John Doe's bakery",
  "address": "790 Bushwick Ave Brooklyn, NY 11221, USA",
  "webpage": "www.johndoebakery.com",
  "category": "Local business",
  "contact_phone": "123-456-789"
  "opening_hours_from": "08.00",
  "opening_hours_to": "16.00",
  "parking_place": "no",
  ...
}
```

This data is pulled from FB Pages graph api automatically and can then be used in "minimal bot" service (to get basic info about page). 

```
Customer: Where can i buy your products? / Where can i get your products?

John Doe's bakery: You can buy them here: 790 Bushwick Ave Brooklyn, NY 11221, USA

```

It can also be used to display "business card":


```
Customer: Can i get more details for this page?
John Doe's bakery: John Doe's bakery is located in 790 Bushwick Ave Brooklyn, NY 11221, USA
...
```


## Instructions

1. Clone to local directory
2. Cd into dir
2. `php -f main.php`

