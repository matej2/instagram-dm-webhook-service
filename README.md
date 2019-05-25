# instagram-messenger-bot

## Introduction

When you are running your business is it important to be able to get in contact with customer when he or she is looking to buy. If you miss this opportunity, you might lose a costumer. Facebook has started implementing messenger bots, Instagram, however, still does not have this feature. The fact that you cannot check messages on web application tells that such bot will provide help in managing user relations.


We would create Instagram messenger bot api and service that will respond to questions. This service will not use official api as it is not possible to collect data on messages and to reply to incoming messages. Instead it will use unofficial api / library (for ex: https://github.com/LevPasha/Instagram-API-python).

Responding to DMs would defined by regex selectors. For example:

```
match([regex1,regex2,regex3]).do(function1, function2);
...
```

We plan to implement this in NodeJS. Later on (in second version) we plan to also implement desktop app in VueJS (Or ReactJS). Also we plan to implement docker files.


# Option 2: We create connection between FB messenger and IG messenger

The goal is to simply create connection between theese software. Here we would use official facebook messenger api and our Instagram message unofficial API for accessing IG DMs. In this case there is possibility that the page which is using our service can be converted to a high-MPS Page.
