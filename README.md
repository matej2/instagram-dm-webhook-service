# instagram-messenger-bot

## Introduction

When you are running your business is it important to be able to get in contact with customer when he or she is looking to buy. If you miss this opportunity, you might lose a costumer. Facebook has started implementing messenger bots, Instagram, however, still does not have this feature. The fact that you cannot check messages on web application tells that such bot will provide help in managing user relations.

## Goal
Create Instagram messenger bot api and service that will respond to questions. Mentoined service will not use official api as it is not possible to collect data on messages and to reply to incoming messages. Instead it will use unofficial api / library (https://github.com/LevPasha/Instagram-API-python)

## Technology
We plan to implement this in NodeJS. Later on (in second version) we plan to also implement desktop app in VueJS (Or ReactJS). Also we plan to implement docker files.

## User support
Apart from the github issues, we will implement anonymous issue reporting on webpage and support trought Facebook page.

## Organization, other
We will help tracking work with trello and trought chat (Messenger ot Trello)

## Investigation / issues
So far, there is no similar project that would read or write messages based on preconfigured settings. 

* Check for similar services
* check for scrappers
* Check for libaries / APIs
* To be discussed: Password storage and security (implemented in second version)
* To be discussed: Advanced ready-to-use real-world examples (implemented in second version)


# Option 2: We create connection between FB messenger and IG messenger

The goal is to simply create connection between theese software, Here only connection between business pages would be possible. Here we would use official facebook messenger api and our Instagram message unofficial API. In this case there is possibility that the page which is using our service can be converted to a high-MPS Page.
