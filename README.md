# instagram-dm-webhook-service

> This is not be autorespond / autosend service. Similar to Facebook chat bot service, this service uses webhooks to communicate with external services to get response based on received message.


This is Instagram direct message webhook service. It will read messages from Instagram and send webhooks to chatbot services. It will then reply to message in Instagram dm. Due to Instagram limitations, we can support only text-based communication between IG DM and third-party service.

This service is not using official api as it is not possible to collect messages data and to reply to incoming messages. Instead it uses unofficial api / library


## Contributing
You can pick any [issue marked as a *enchantment*](https://github.com/matej2/instagram-dm-webhook-service/issues?q=is%3Aissue+is%3Aopen+label%3Aenhancement) or just start your own feature. Also any discussion on these features is welcomed as it helps us understand topic. You can submit your commits on `beta` branch. For larger changes (features) we suggest you make separate branch and then make PR to `beta` branch. If you would like, you can also get access to trello board where we have cards with details.


## Setup

### Environment setup
For instagram api to work, you need to [enable php extensions](https://github.com/mgp25/Instagram-API/wiki/Dependencies). You also need to add [certificate and its configuration](https://stackoverflow.com/questions/24611640/curl-60-ssl-certificate-unable-to-get-local-issuer-certificate) in `php.ini`

### Installation

1. Clone to local directory
2. Copy and rename `config.example.json` to `config.json`
3. Fill in your configuration (you can use this for testing: https://webhook.site -  if you are using this, you should also set reply in JSON)
4. `php -f main.php`

### Configuration setup

Apart from standard webhook config, user needs to setup keywords, which will trigger webhook call. Theese are separated with comma. User also needs to setup time wait between messages. This means how much seconds will script wait before processing webhook and sending text reply to instagram direct message. This parameter should be calculated in consideration of maximum number of requests per second.

### Setting up cone job

There is no official limt on how message to send to users in a specific period of time. While it can be problematic to send messages as a request, it is less problematic to send responses (when you are not the first one initiating conversation). The advice is to setup crone job to run service each 15 minutes (file is located at `/var/spool/cron/crontabs/`):

```
* 0/15 * ? * * * php -f /path-to-dm-bot/main.php
```

## How does it work
User should configure this service to run every 15 minutes. It will check latest messages in every thread from every sender. If it will match any of the keywords it will send webhook/s as configured.

## Planned features

- [ ] Per hour limit checks

Before calling webhook, service will count already sent webhooks to check (if defined) hourly and monthly quota limits. 

- [ ] Wait list

Furthermore, if limit quotas have been reached, the messgae is sent to wating list in database. Message will be send when quotas are reset.

- [x] Request pause

User will be able to setup time delay between webhook calls. This is essential as some chatbot services have webhook call limits per second

- [x] Keywords

Service only calls webhook if the message includes specific keyword(s).

- [x] After send method

After calling webhook, service will call another method enabling you to quickly add other actions in service.
