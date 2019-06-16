# instagram-dm-webhook-service

> This will not be autorespond / autosend service. Similar to Facebook chat bot service, this service will use webhooks to communicate with external services to get response based on received message.


*This is a draft for instagram dm webhook service.*

We would create Instagram messenger bot api and service that will respond to questions. This service will not use official api as it is not possible to collect messages data and to reply to incoming messages. Instead it will use unofficial api / library (for ex: https://github.com/LevPasha/Instagram-API-python).


## Contributing
You can submit your commits on `beta` branch. 

### Instructions

1. Clone to local directory
2. Cd into dir
3. Copy and rename `config.example.json` to `config.json`
4. Fill in your configuration (you can use this for testing: https://webhook.site)
5. `php -f main.php`


## Webhook service

Service  will use webhooks to communicate with third-party services. Due to Instagram limitations, we can support only text-based communication between IG DM and third-party service.

### How will it work
We will configure this service to run every 15 minutes. It will check latest messages from every sender. If it will match any of the keywords it will send webhooks as configured.

### Planned features

*Per month / per hour limit checks*

Before calling webhook, service will count already sent webhooks to check (if defined) hourly and monthly quota limits. 

*Wait list*

Furthermore, if limit quotas have been reached, the messgae is sent to wating list in database. Message will be send when quotas are reset.

*Keyword blacklist*

To prevent replying to auto-send bots, service will use keyword checks. If word is blacklisted, service will not send webhooks but it will log event.

*After send method*

After calling webhook, service will call another method enabling you to quickly add other actions in service.
